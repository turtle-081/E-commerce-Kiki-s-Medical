# PLATFORM

How this site is built, where everything lives, and the things that will bite you
if nobody tells you about them first.

For *what was changed and why*, see `REPORT.md`. For *how to undo any of it*, see
`ROLLBACK.md`. For *patches made to third-party code*, see `PATCHES.md`. This
file is the map, not the history.

---

## The stack

| | |
|---|---|
| Site | Kiki's Medical Equipment and Hospital Supplies (WooCommerce store, KES) |
| Local domain | `client1.local` |
| WordPress | 7.0.2 |
| WooCommerce | 10.8.1 |
| PHP | 8.2.29 (NTS, Visual C++ 2019 x64) |
| MySQL | 8.4.0 |
| nginx | 1.26.1 |
| Theme | `propharm` 3.1 with `propharm-child` — **all edits go in the child** |
| Page builders | WPBakery (`js_composer` 8.7.2) **and** Elementor, both active |
| Host | Local by Flywheel on Windows 11 |

`twentytwentythree/four/five` are stock WordPress themes; nothing uses them.

### Local service ports

Local assigns these per site. This one is site id `cPNju-zlO`:

| Service | Port(s) |
|---|---|
| nginx (HTTP) | 10005 |
| MySQL | 10004 |
| PHP (php-cgi) | 10002, 10003, 10006, 10007, 10008, 10009 |
| Mailpit (web / SMTP) | 10000 / 10001 |

**There is no PHP-FPM.** Local on Windows runs a TCP pool of `php-cgi.exe`
processes, one per port above — six of them. That count *is* the concurrency
limit for the whole site, and it is configured in Local's own
`%APPDATA%\Local\sites.json` under `services.php.ports.cgi`, not in any file in
this repo. It was raised from 2 to 6 during the performance work; at 2, images
appeared to load slowly because requests were starving for a worker, not because
the images were large.

---

## Repo layout

```
app/public/                     WordPress root
  wp-config.php                 tracked deliberately (see below)
  wp-content/
    mu-plugins/safi-performance/  all performance work lives here
    themes/propharm-child/        all theme customisation lives here
    plugins/                      includes locally patched vendor code
conf/                           Local's config TEMPLATES  <- edit these
scripts/measure.sh              TTFB / cache-status harness
scripts/lh.sh                   Lighthouse runner (N runs per page)
scripts/lh-summary.py           medians + spread + deltas between runs
tools/                          one-off, reversible change scripts
reports/                        raw Lighthouse JSON (evidence, prunable)
DISCOVERY.md REPORT.md ROLLBACK.md PATCHES.md PLATFORM.md
```

### Why `wp-config.php` and `conf/` are tracked

The performance brief required that every config file changed goes into version
control. Both were un-ignored for that reason. The repository is private.

If this is ever made public, `wp-config.php` must be removed from history first —
it contains database credentials and WordPress salts. They are local-only values,
but they are still secrets.

---

## The critical gotcha: `conf/` is a template directory

Every file in `conf/` ends in `.hbs`. **Local regenerates its runtime nginx and
PHP config from these templates every time the site restarts.**

The runtime copies live outside the repo, at:

```
%APPDATA%\Local\run\cPNju-zlO\conf\nginx\
%APPDATA%\Local\run\cPNju-zlO\conf\php\
```

Editing a runtime file gets you a working change that silently disappears on the
next restart. Editing only the template gets you a change that does nothing until
you restart.

**So: edit the `.hbs` template, then mirror the same edit into the runtime file
and reload nginx** — that gives you both immediate effect and persistence. Or
edit the template and restart the site in Local.

Reloading nginx without a restart:

```bash
NGX="/c/Users/Turtle/AppData/Roaming/Local/lightning-services/nginx-1.26.1+3/bin/win32"
CONF="C:/Users/Turtle/AppData/Roaming/Local/run/cPNju-zlO/conf/nginx/nginx.conf"
PFX="C:/Users/Turtle/AppData/Roaming/Local/run/cPNju-zlO/nginx/"
"$NGX/nginx.exe" -t -c "$CONF" -p "$PFX" && "$NGX/nginx.exe" -s reload -c "$CONF" -p "$PFX"
```

Note the binary is under `bin/win32/` even on 64-bit Windows.

---

## Caching

Two layers, and they interact.

### 1. nginx FastCGI page cache

Configured in `conf/nginx/nginx.conf.hbs` and `conf/nginx/site.conf.hbs`. Cache
files live in `app/nginx-cache/` (not tracked).

An anonymous `GET` of a normal page is served from disk in ~30 ms. Everything
transactional bypasses it: any request carrying a WooCommerce session,
`wordpress_logged_in_*`, or a comment/postpass cookie; `/cart/`, `/checkout/`,
`/my-account/`, `/wp-login.php`, `/wp-json/`; any non-GET; and anything with a
query string other than stripped tracking parameters.

Every response carries diagnostics:

```bash
curl -s -o /dev/null -D - http://client1.local/shop/ | grep -i x-fastcgi-cache
# X-FastCGI-Cache: HIT | MISS | BYPASS
```

`X-Cache-Skipped: 1` tells you the bypass rules fired.

To empty it by hand:

```bash
rm -rf "app/nginx-cache"/*
```

It also purges automatically on publish, product stock/price changes, menu and
widget edits, and from a **Purge page cache** item in the admin bar — see
`mu-plugins/safi-performance/cache-purge.php`.

### 2. The theme's transient cache

`enovathemes-addons` caches rendered headers, footers, megamenus and dynamic CSS
in transients. **This one catches people out**: the TTL is a week, so if you
change a header or footer and nothing appears to happen, this is why. Purging the
nginx cache does not help — the stale markup is coming from the database.

```bash
php tools/flush-theme-caches.php   # empties the nginx page cache too
```

Do this after **any** header, footer, menu or theme-option change.

**The two caches must be flushed together, and the tool now does that itself.**
Clearing the transients alone leaves the theme to rebuild them on the next
render — and a rebuild that happens under load can emit the page *without* its
megamenus, which nginx will then cache and serve as a `HIT` for 24 hours. See
"Things that will bite you".

### 3. WebP content negotiation (not really a cache, but it lives here)

`tools/make-webp.php` writes `foo.png.webp` beside every `foo.png` and
`foo.jpg` in uploads that converts at least 15% smaller. nginx serves the
sibling only when the request's `Accept` header allows it:

```nginx
map $http_accept $webp_suffix { default ""; "~*image/webp" ".webp"; }   # nginx.conf.hbs
try_files $uri$webp_suffix $uri =404;                                    # site.conf.hbs
```

**Page URLs and markup are untouched** — the page still asks for `foo.png`.
Check which one you are getting:

```bash
curl -s -o /dev/null -D - -H 'Accept: image/webp' \
  http://client1.local/wp-content/uploads/kiki-logo.png | grep -i content-type
```

Two things to know:

- **New uploads are not converted automatically.** nginx falls through to the
  original, so nothing breaks — the image is just heavier. Re-run the tool; it
  is idempotent and skips anything already current.
- **`Vary: Accept` is required** and is set. Without it a shared cache could
  hand a WebP to a client that cannot display it.

---

## The performance layer

Everything lives in `app/public/wp-content/mu-plugins/safi-performance/`. Each
file is independent — delete one to disable exactly that change, and nothing else
breaks. `safi-performance.php` at the top level is only a loader (WordPress
auto-loads top-level mu-plugin files only, so it globs the subdirectory).

| Module | What it does |
|---|---|
| `transient-autoload.php` | Gives the theme's transients a real TTL so WordPress stops autoloading them (2,241 KB → 153 KB of autoloaded options) |
| `cache-purge.php` | Empties the nginx cache when content changes |
| `woo-fragments.php` | Drops `wc-cart-fragments` off cacheable pages; replaces the cart badge with a Store API call gated on the cart cookie |
| `woo-assets.php` | Loads WooCommerce assets only where products actually render |
| `speculation-rules.php` | Raises core's speculation rules to `prerender`/`moderate` and adds the WooCommerce exclusions core lacks |
| `slider-assets.php` | Loads Slider Revolution only on pages that contain a slider |
| `font-loading.php` | `display=optional` on Google Fonts so the header cannot reflow mid-render |
| `script-loading.php` | Asks core to defer scripts; core refuses where ordering would break |
| `block-assets.php` | Drops `wp-block-library` (17 KB, render-blocking) on pages with no block markup; cart, checkout and my-account are exempt unconditionally |
| `product-image-priority.php` | Makes the single product page's main gallery image `eager` instead of `lazy` — it is the LCP element and WooCommerce lazy-loads it |

**Nothing here modifies WooCommerce, the checkout template, or any payment
gateway.** That constraint was set by the engagement brief and should be kept.

---

## `tools/` conventions

One-off scripts that change database content. The house style, worth keeping:

- Back up what you are about to change into post meta, so the change is
  reversible without a database restore.
- Support `--revert`.
- Be idempotent — re-running must not clobber a real original with a modified one.

`inline-footer.php`, `inline-header-megamenu.php` and `inline-mobile-header.php`
are the cleanest examples. `check-patches.sh` verifies the vendor patches in
`PATCHES.md` are still applied — **run it after updating any plugin or theme.**

Three that are not one-off content changes and are worth knowing about:

| Tool | Use |
|---|---|
| `make-webp.php` | Generates the WebP siblings. Idempotent; `--dry-run` and `--revert`. **Re-run after a bulk upload** — new images are simply served unconverted until you do |
| `check-image-attrs.py` | Snapshots every image's `loading`/`fetchpriority`/`decoding` across three pages and diffs two snapshots. Run it before and after anything that touches image markup — it is what proves a change did not repeat the Phase 6.5 LCP regression |
| `flush-theme-caches.php` | Clears the theme transients **and** the page cache. Never flush one without the other |

Running a script needs Local's PHP *with Local's `php.ini`*, otherwise `mysqli`
is missing and WordPress refuses to boot:

```bash
PHP="/c/Users/Turtle/AppData/Roaming/Local/lightning-services/php-8.2.29+0/bin/win64/php.exe"
INI="/c/Users/Turtle/AppData/Roaming/Local/run/cPNju-zlO/conf/php"
"$PHP" -c "$INI" tools/<script>.php
```

WP-CLI is not installed.

---

## Measuring

```bash
bash scripts/measure.sh http://client1.local 5
```

TTFB over N runs per path plus cache status, with a discarded warm-up. It uses
`GET` with `-D -` rather than `curl -I` on purpose: a `HEAD` is not a `GET`, so
the bypass rules skip it and the cache always looks broken.

Lighthouse:

```bash
npx --yes lighthouse "http://client1.local/shop/" --preset=perf --form-factor=mobile \
  --throttling-method=simulate --screenEmulation.mobile \
  --chrome-flags="--headless=new --no-sandbox --disable-gpu" --quiet \
  --output=json --output-path=./reports/whatever.json
```

**Run it at least three times and report the spread.** Scores on this machine
have swung by 30 points between identical runs. Any single number is not a
result.

---

## Things that will bite you

- **Deferred scripts change whether JS hydration runs at all**, not just when.
  Deferring scripts silently broke the homepage footer, which was being painted
  by an AJAX call. Anything on this site that paints via JavaScript — slider,
  megamenus, mini-cart, product grids — needs checking after any change to script
  loading. Bisect by temporarily renaming `script-loading.php`.
- **Slider Revolution builds its markup client-side.** Parsing the served HTML
  for slider elements reports zero sliders on every page *including* the one that
  has a slider. Check the live DOM, not the source.
- **The theme disables responsive images site-wide.**
  `enovathemes-addons.php:1862` sets `wp_calculate_image_srcset` to
  `__return_empty_array`, which is why `srcset` is absent everywhere and why
  images ship at full size. It is registered from a *named* function on `init`,
  so it can be lifted with one `remove_action` and needs no vendor edit — but
  read the next two entries before deciding you want to.
- **Restoring responsive images makes this site heavier, not lighter.**
  Lighthouse computes "larger than it needs to be" against CSS pixels, and its
  mobile profile is 412 px at DPR 2.625. A correct `srcset` therefore asks for
  ~2.6x more pixels than the theme's hardcoded sizes do, and the browser
  *upgrades*: the main product image went 600x600 (16 KB) to 768x768 (22 KB) in
  all three runs. Measured in Phase 6.7 and reverted. The theme's crude fixed
  sizes are already below what proper responsive images would request.
- **`sizes="auto"` cannot work on most images here.** The product grids are
  carousels, and 12 of 13 images tested had a layout width of **zero** at the
  moment the browser picks a candidate, so `auto` has nothing to measure and
  falls back to the largest. Check `img.currentSrc` and
  `getBoundingClientRect().width` in the live DOM before assuming a srcset is
  being honoured.
- **Lighthouse's "unused CSS" is viewport-specific and overstates the win.**
  It is measured on a 412 px mobile run, so every desktop `@media
  (min-width: ...)` rule counts as unused: 39% of `dynamic-styles-cached.css`
  and 14% of the theme stylesheet by raw bytes. `js_composer.min.css` reads
  "100% unused" on mobile while containing the entire `.vc_col-sm-*` grid that
  desktop layout depends on. Dequeuing on that evidence would break the site at
  desktop widths.
- **`error_reporting` excludes `E_DEPRECATED`.** An empty `error.log` does not
  mean there are no deprecations. Toggle `WP_DEBUG` to actually check.
- **An incomplete render can get promoted into the page cache, and then it is
  everyone's page for 24 hours.** The theme rebuilds its header, footer and
  megamenu transients lazily. A render that triggers that rebuild while the site
  is busy can emit the page *without* its megamenus, and nginx will happily
  store it and serve it as a `HIT` until the entry expires.

  Observed during Phase 6.8: the homepage cached at 276 KB instead of 622 KB,
  missing every megamenu and the mobile header, and four Lighthouse runs
  measured that page before the document size gave it away. `tools/flush-theme-caches.php`
  now empties the nginx cache itself for this reason, but the general rule is
  broader:

  ```bash
  # after any theme-cache flush, or any measurement that looks too good
  curl -s http://client1.local/ | wc -c      # expect ~622000, not ~276000
  ```

  **Check the document size before trusting a measurement.** In a Lighthouse
  report it is `audits['network-requests'].details.items[] .resourceSize` for
  the `Document` entry. A page that suddenly got faster and smaller by half did
  not get optimised; it lost content.
- **Browser-tool console output accumulates across navigations.** An error listed
  after visiting five pages may have come from any of them. Use a fresh tab
  before concluding a page is broken.
- **`js_composer` can be inactive while the theme still calls `WPBMap`.** Six
  call sites are guarded locally; see `PATCHES.md`.

---

## Outstanding work

The engagement is complete as far as this environment allows. Three of the
brief's six targets are met; the three that are not are blocked by what the page
is built from, not by anything left untuned.

`REPORT.md` has the per-phase status and the measurements. This is what someone
picking it up next actually needs.

### What would actually move the numbers now

**Not images, and not CSS.** Both are finished and both turned out smaller than
the brief assumed:

- Responsive images were tried twice and reverted twice. The second attempt
  (6.7) touched only `srcset`/`sizes`, never a loading attribute, and still
  failed: 12 of 13 images had a **layout width of zero** when the browser picks
  a candidate, because they sit in carousels that are not laid out yet, and
  restoring core's srcset made the main product image *bigger* on a DPR 2.625
  mobile profile. Do not try a third time without reading that section first.
- The "156 KB of unused CSS" is largely desktop media queries counted as unused
  on a 412 px run. The 17 KB that was genuinely removable is gone.

**What is left is the JavaScript**, ~417 KB of it, on a page running WPBakery and
Elementor and Slider Revolution simultaneously. LCP is 6.6-8.9 s against a 2.5 s
target and the remaining cost is execution, not transfer. Closing that gap means
changing what the page is made of — a rebuild decision, not a tuning one.

### Two loose ends that are small and real

- **The WhatsApp widget shifts the page.** An intermittent CLS of 0.069, from a
  container `wp-whatsapp-chat` injects with JavaScript. It is the only thing
  between this site and the CLS target. A CSS fix was tried and was redundant —
  see the note in `brand.css` before attempting another.
- **Two uncached PHP requests per homepage view remain**: `megamenu_load` (the
  sidebar category flyouts) and `update_mini_cart_contents`. Neither blocks
  first paint. The other two were removed in Phase 8.

### Phase 8 — final verification

The before/after deliverable is written. Two of the brief's checks cannot run
here and need a real deployment: **edge TTFB from Nairobi** (no public hostname,
no CDN) and the **M-Pesa STK push test** (no payment gateway plugin installed).

### Phase 3 — Cloudflare

Skipped entirely. `client1.local` is not publicly resolvable, so none of it
applies until this is on a real domain.

### Smaller items, not phase work

- **Two uncached PHP requests per page view remain** — `update_mini_cart_contents`
  (321 B) and `megamenu_load` (~10.7 KB). Both need `controller.js` patches:
  the mini-cart fires whenever `body.woocommerce-js` is present, and the theme's
  JS collects *every* `.menu-item.mm-true` regardless of the per-item AJAX flag.
  Getting to zero means patching vendor JavaScript.
- **Two demo menu items link to `/nothing`** (HTTP 404), labelled "404 Error
  Page" in the navigation.
- **Every image has `alt="One"`** — the site name, not a description. An
  accessibility problem rather than a performance one, but it should be fixed
  before handover.
- **Structured data has not been audited.** WooCommerce and Yoast emit `Product`,
  `Offer`, `BreadcrumbList` and `Organization` JSON-LD, but nobody has checked
  that `priceCurrency` followed the KES conversion. If it still says `USD`,
  search results would advertise wrong prices.
- **The PR into `master` is not open.** Nothing has merged forward.

### Before resuming

Re-measure first — do not trust the numbers in `REPORT.md` to still hold after
any WordPress, WooCommerce, theme or plugin update:

```bash
bash scripts/measure.sh http://client1.local 5
bash tools/check-patches.sh
```

The second one matters: `PATCHES.md` documents patches to third-party code that
**any plugin or theme update will silently revert**.

---

## Not in this repo

Machine-level state a fresh clone will not reproduce:

- **PHP worker count** — `%APPDATA%\Local\sites.json`, `services.php.ports.cgi`.
  Six workers; the default of two is a hard concurrency ceiling.
- **GitHub CLI 2.98.0** — installed at `C:\Program Files\GitHub CLI\` to open a
  pull request. Not required by anything here; remove with
  `winget uninstall --id GitHub.cli` if unwanted.
- **The `restore_check` database** — a verified restore of the pre-engagement
  dump, kept as proof the backup works. See `ROLLBACK.md`.

---

## Branches

`restore/local-environment` carries all of this work. `master` is an ancestor of
it, so it merges without conflict but is many commits behind. Nothing has merged
the work forward yet.
