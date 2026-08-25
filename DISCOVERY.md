# DISCOVERY — performance engagement, Phase 1

Read-only. Nothing in this document has been changed on the site.

Measured 25 August 2026 against `http://client1.local`.

---

## Phase 0 — environment

```
SITE_URL             = http://client1.local        (local development only, not public)
WP_PATH              = C:\Users\Turtle\Local Sites\client1\app\public
WEB_SERVER           = nginx 1.26.1 (Windows build, bundled with Local)
HOSTING              = Local by Flywheel, Windows 11 development machine
SSH_ROOT_ACCESS      = n/a — local filesystem access, but no Linux service layer
CLOUDFLARE           = no, and cannot be added (hostname is not publicly resolvable)
CLOUDFLARE_API_TOKEN = n/a
WOOCOMMERCE          = yes, 10.8.1
PAYMENT_GATEWAY      = none installed (no M-Pesa/Daraja plugin present)
STAGING_URL          = none — this environment *is* the development copy
```

**This is a development environment, not the production target.** That single fact
removes or reshapes most of Phases 2A and 3. See "What cannot be done here".

---

## 1.1 Environment detail

| | |
|---|---|
| WordPress | **7.0.2** (≥ 6.8, so core Speculation Rules in Phase 5 is available) |
| PHP | 8.2.29, NTS, Visual C++ x64 |
| Theme | Propharm Child 1.0 → parent Propharm 3.1 — **classic, not block** |
| Active plugins | **22** |
| Extensions present | OPcache, GD, mbstring, mysqli, curl, intl, zip (52 total) |
| Extensions absent | **Redis**, **Imagick** |
| GD image support | JPEG, PNG, **WebP**, **AVIF** — so Phase 6.4 WebP output is possible via GD |
| Object cache | **none** — DB-backed transients only |
| Page cache | **none active** (see LiteSpeed note below) |
| `WP_CACHE` | on — set by the LiteSpeed plugin, but nothing is caching |
| `DISABLE_WP_CRON` | **already true** (set earlier in this project) |
| `WP_POST_REVISIONS` | `true` — uncapped |

### PHP process model — important

Local on Windows does **not run PHP-FPM**. The theme's own
`conf/php/php-fpm.d/www.conf.hbs` says so in its first line. Instead nginx
round-robins over separate `php-cgi.exe` processes, one per port listed in
`%APPDATA%\Local\sites.json → services.php.ports.cgi`.

That array was `[10002, 10003]` — two concurrent PHP requests on a 12-core
machine. It was raised to **six** earlier in this project. Any Phase 2 config
that references `fastcgi_pass unix:/run/php/php8.x-fpm.sock` does not apply;
the upstream here is a TCP pool.

### OPcache — currently under the brief's targets

| Setting | Now | Brief asks for |
|---|---|---|
| `opcache.enable` | 1 | 1 |
| `memory_consumption` | **128** | 256 |
| `max_accelerated_files` | **10000** | 20000 |
| `interned_strings_buffer` | **8** | 32 |
| `validate_timestamps` | 1 | 1 |
| `revalidate_freq` | **2** | 60 |
| `save_comments` | 1 | 1 |

`memory_limit` 256M. `max_execution_time` is **1200** on the web SAPI — high
enough that an abandoned request keeps a worker busy for 20 minutes.

### nginx modules

| Module | Status |
|---|---|
| `ngx_cache_purge` | **NOT FOUND** — the Phase 2A.1 `/purge` endpoint is impossible |
| `http_realip` | present |
| `http_v2` | present |
| `http_ssl` | present |

---

## 1.2 Plugin audit

22 active. No plugin has been removed — this is a recommendation list only.

| Plugin | Ver | Recommendation | Why |
|---|---|---|---|
| `woocommerce` | 10.8.1 | **keep** | core to the site |
| `js_composer` (WPBakery) | 8.7.2 | **keep, reluctantly** | the entire site is built in it; removing means rebuilding every page |
| `elementor` | 4.1.4 | **remove — verify first** | a *second* page builder loading alongside WPBakery. No page content appears to use it |
| `revslider` | 6.7.54 | **replace** | biggest single payload contributor: 167 KB JS + 76 KB FontAwesome + ~430 KB of slider PNGs on the homepage alone |
| `litespeed-cache` | 7.8.1 | **remove or repurpose** | page cache is inert on nginx (see below). Currently only doing lazy-load |
| `enovathemes-addons` | 3.1 | keep | theme dependency |
| `redux-framework` | 4.5.13 | keep | theme options dependency |
| `wordpress-seo` (Yoast) | 27.9 | keep | only SEO plugin — no duplication |
| `contact-form-7` | 6.1.6 | **replace** | brief flags this; loads on every page for one form |
| `google-listings-and-ads` | 3.7.2 | **remove unless used** | heavy WooCommerce marketing extension |
| `google-site-kit` | 1.182.0 | **remove unless used** | analytics wrapper; adds admin + frontend weight |
| `webappick-product-feed-for-woocommerce` | 6.6.39 | **remove unless used** | product feed generator |
| `webappick-pdf-invoice-for-woocommerce` | 3.7.85 | keep if invoicing is used | admin-side mostly |
| `wp-whatsapp-chat` | 8.6.0 | **replace** | brief explicitly calls this out — should be a plain `<a href="https://wa.me/…">` with inline SVG |
| `mailchimp-for-wp` | 4.13.1 | keep if the newsletter is used | footer signup depends on it |
| `variation-swatches-for-woocommerce` | 2.2.7 | keep | product variations depend on it |
| `disco` | 1.3.53 | review | unclear purpose; carries a patched vendor library |
| `safe-svg` | 2.4.0 | keep | small, security-relevant |
| `classic-editor` | 1.7.0 | keep | pairs with the classic theme |
| `regenerate-thumbnails` | 3.1.6 | **remove after use** | utility, not needed at runtime |
| `one-click-demo-import` | 3.4.1 | **remove** | demo import is finished |
| `envato-market` | 2.0.3 | keep | theme licence/updates |

**Two page builders and a slider plugin are the story here.** The brief's closing
caveat applies directly: with WPBakery in place there is a CSS/JS floor that
Phase 6 cannot get under without changing themes.

### LiteSpeed Cache — a rule 5 conflict already exists

`litespeed.conf.cache = 1` with a 7-day TTL, but **the server is nginx, not
LiteSpeed**. LSCache cannot do page caching without a LiteSpeed server, so that
setting does nothing. What it *is* doing is lazy-loading images (`media-lazy=1`),
which caused a real bug earlier: it was lazy-loading the site logo.

Minification and defer are both **off** (`optm-css_min`, `optm-js_min`,
`optm-js_defer` all empty/0).

So the site has a caching plugin installed that provides no caching. Under rule 5
a choice is needed before Phase 2: either remove it and use nginx FastCGI cache,
or keep it purely as an optimisation layer with page cache explicitly off.

---

## 1.3 Baseline measurements

Reproduce with `bash scripts/measure.sh http://client1.local 3`.

### Server timing (curl, 3 runs after a discarded warm-up)

| Path | Mean TTFB | HTML size | Cache header |
|---|---|---|---|
| `/` | **1.887 s** | 287 KB | none |
| `/shop/` | **2.054 s** | 585 KB | none |
| `/product/advil-minis-liquid-cap-x-90/` | **1.753 s** | 312 KB | none |
| `/cart/` | **1.855 s** | 369 KB | none |

No `X-FastCGI-Cache`, no `X-LiteSpeed-Cache`, no `Age`. **Every request runs the
full PHP stack.** Against the brief's target of < 100 ms TTFB for a cached
anonymous page, the gap is roughly 20×.

HTML alone is 287–585 KB before a single asset loads.

### Lighthouse — mobile, simulated throttling

| Metric | Homepage | Product page | Target |
|---|---|---|---|
| **Performance** | **29** | **38** | ≥ 90 |
| FCP | 3.0 s | 3.1 s | — |
| **LCP** | **7.7 s** | **6.9 s** | < 2.5 s |
| **CLS** | 0.076 | **0.148** | < 0.05 |
| TBT | **7,860 ms** | 740 ms | — |
| Speed Index | 15.1 s | 9.5 s | — |
| Server response | 2,170 ms | 3,640 ms | — |
| Total weight | 2,209 KB | 831 KB | — |

Reports: `reports/baseline-home.report.json`, `reports/baseline-product.json`.

### Payload breakdown

| Type | Homepage | Product page |
|---|---|---|
| Total requests | 132 | 80 |
| Images | 60 req / **1,401 KB** | 15 req / 106 KB |
| **Scripts** | 36 req / **418 KB** | 41 req / **445 KB** |
| Stylesheets | 24 req / 219 KB | 17 req / 205 KB |
| Fonts | 4 req / 106 KB | 2 req / 23 KB |
| Document | 44 KB | 48 KB |

**The product page ships 445 KB of JavaScript against a 300 KB target** — 145 KB
over, before any third-party tags are added.

### Top 10 heaviest requests (homepage)

| Size | Type | Resource |
|---|---|---|
| 94 KB | script | `revslider/public/js/sr7.js` |
| 82 KB | image | `uploads/revslider/slider-1/slider-1-as6.png` |
| 82 KB | image | `uploads/revslider/slider-1/slider-1-as5.png` |
| 78 KB | stylesheet | `themes/propharm/style.css` |
| 76 KB | image | `uploads/revslider/slider-1/slider-1-asset-1.png` |
| 76 KB | font | RevSlider's bundled FontAwesome |
| 73 KB | script | `revslider/public/js/libs/tptools.js` |
| 68 KB | image | `uploads/revslider/slider-1/slider-1-asset-2.png` |
| 65 KB | image | `uploads/revslider/slider-1/slider-1-as7.png` |
| 56 KB | image | `uploads/revslider/slider-1/slider-1-as8.png` |

**Nine of the ten heaviest requests belong to Slider Revolution.** Roughly
600 KB of the homepage's 2,209 KB is one slider.

Lighthouse opportunities: 194 KB unused CSS, 109 KB unused JavaScript.

---

## Findings that matter most

### 1. 2.2 MB of autoloaded options, loaded on every request

| autoload value | Options | Size |
|---|---|---|
| `on` | 267 | **2,162 KB** |
| `auto` | 365 | 81 KB |
| `off` | 155 | 1,016 KB |

The heaviest autoloaded entries are all the theme's own product-grid caches:

```
_transient_et_products__KESgridarrowstopfalsetru…   211.5 KB
_transient_et_products__USDgridarrowstopfalsetru…   211.3 KB
_transient_et_products__KESgridarrowstopfalsetru…   140.8 KB
…12 more in the 70–140 KB range
```

Two problems at once. These are **transients stored with `autoload = on`**, so
every request — including admin and AJAX — pulls them into memory whether the
page shows a product grid or not. And they are duplicated per currency: the
`USD` variants are stale leftovers from before the store moved to KES.

This is squarely the Phase 7 trigger ("if autoloaded options total > 1 MB").

### 2. There is no page cache at all

Nothing is cached. Every anonymous page view runs WordPress end to end. The
brief's target of "0 uncached PHP requests per anonymous page view" is currently
"100% uncached".

### 3. The theme multiplies PHP requests per page view

The theme's "cache queries" option is on, which renders each page as a shell and
fetches megamenus, footer, mobile header, products and posts through separate
`/ajax-api/` endpoints — each paying a **full WordPress bootstrap**. Measured
earlier in this project: about 2 s per bootstrap, of which ~700 ms is
`propharm_enovathemes_integrateVC` re-registering all 102 WPBakery shortcodes.

One page view was measured at **five to eight PHP requests**, roughly 10–15 s of
cumulative server time. Turning the option off was measured and made things
*worse* (those requests run concurrently; serialising them into one is slower in
wall-clock terms), so this needs solving with caching, not by flipping the option.

### 4. CLS is worst on the product page

0.148 against a 0.05 target. LiteSpeed's "add missing sizes" is off, so images
ship without `width`/`height` and reserve no space.

---

## What cannot be done here — and why

Per working rule 7, stated explicitly rather than substituted.

| Brief step | Status | Reason |
|---|---|---|
| **Phase 2A.1** nginx FastCGI cache | **Partially possible** | `fastcgi_cache` works, but `fastcgi_pass unix:/run/php/…-fpm.sock` does not — there is no PHP-FPM. The upstream is a TCP pool of `php-cgi` workers |
| **Phase 2A.1** `/purge` endpoint | **Impossible** | `ngx_cache_purge` is not compiled into this nginx |
| **Phase 2A.2** Nginx Helper purge method | **Restricted** | must use `unlink_files`, since the purge module is absent |
| **Phase 2A.4** Redis object cache | **Impossible** | no Redis server, no `php_redis` extension on this stack |
| **Phase 3** Cloudflare (all of it) | **Impossible** | `client1.local` is not publicly resolvable. Nothing to put an edge in front of |
| **Phase 6.4** Imagick WebP | **Use GD instead** | Imagick is configured in `php.ini` but the DLL is missing — this is the `php_imagick.dll` warning in the error log. GD has WebP *and* AVIF |
| **Phase 8** "TTFB from Nairobi / at CDN edge" | **Not measurable** | no public hostname, no CDN |
| M-Pesa STK test | **Impossible** | no payment gateway plugin is installed |

### One further constraint specific to Local

nginx config at `%APPDATA%\Local\run\<site-id>\conf\nginx\` is **generated** and
is overwritten whenever the site restarts. The persistent, version-controlled
source is `conf/nginx/*.hbs` in the project. Any Phase 2A work must go in the
`.hbs` templates, not the generated output — a lesson already learned in this
project when a hand-edit to the generated `site.conf` had to be re-applied.

`conf/` is currently gitignored, which conflicts with working rule 3 (every
config file changed goes into git). That needs resolving before Phase 2.

---

## Recommended order from here

The measurements point somewhere slightly different from the brief's default
sequencing, because the biggest wins here are not where the brief assumes.

1. **Phase 7 first, not last.** 2.2 MB of autoloaded options is a bigger, cheaper
   win than anything in Phase 6, and it speeds up the uncached cart/checkout
   path that caching can never help.
2. **Phase 2A**, adapted — FastCGI cache against the TCP upstream, `unlink_files`
   purging, OPcache raised to the brief's numbers. Resolve the LiteSpeed question
   first (rule 5).
3. **Phase 4** — cart fragments are firing on every page view and will defeat the
   cache the moment it exists.
4. **Phase 6**, focused on Slider Revolution. Nine of the ten heaviest homepage
   requests are one plugin; nothing else in Phase 6 comes close.
5. **Phase 5** last, as the brief says — prerendering uncached pages would make
   things worse.

Phase 3 is skipped entirely. Phase 8's edge metrics are not measurable here.

**A caveat to set expectations:** the brief asks this site to feel like a static
Next.js app. With WPBakery rendering every page, Slider Revolution on the
homepage, and 22 active plugins, caching can deliver the TTFB target for
anonymous visitors — but Lighthouse ≥ 90 and < 300 KB of JS on a product page are
unlikely without removing Slider Revolution and one of the two page builders.
That is a scope conversation, not a config change.
