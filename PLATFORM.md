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
php tools/flush-theme-caches.php
rm -rf "app/nginx-cache"/*      # then clear the page cache too
```

Do this after **any** header, footer, menu or theme-option change.

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
  images ship at full size.
- **`error_reporting` excludes `E_DEPRECATED`.** An empty `error.log` does not
  mean there are no deprecations. Toggle `WP_DEBUG` to actually check.
- **Browser-tool console output accumulates across navigations.** An error listed
  after visiting five pages may have come from any of them. Use a fresh tab
  before concluding a page is broken.
- **`js_composer` can be inactive while the theme still calls `WPBMap`.** Six
  call sites are guarded locally; see `PATCHES.md`.

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
