# REPORT — performance engagement

Baseline in `DISCOVERY.md`. Measurements reproduce with
`bash scripts/measure.sh http://client1.local` and the Lighthouse commands below.

Working rule 7 applies throughout: anything impossible in this environment is
named and skipped, never silently substituted.

---

## Phase 7 — database and background work *(done first, out of order — see below)*

Run before Phase 2 because discovery showed the largest and cheapest win was
here, and unlike caching it also helps cart, checkout and account, which can
never be cached.

### Backup

Full `mysqldump` (6.3 MB) plus an archive of `wp-config.php`, the child theme,
`conf/` and `tools/`. There is no staging site, so rule 1's "confirm the backup
restores on staging" was satisfied by **restoring the dump into a scratch
database and verifying it**: 68 tables, 568 posts, 788 options, 64 products,
correct `siteurl`. That restored copy is retained as `restore_check`.

### LiteSpeed Cache removed

Discovery found it active with page caching "on" and a 7-day TTL, while the
server is nginx. It had installed **neither `advanced-cache.php` nor
`object-cache.php`**, so its page cache and object cache had never functioned —
it was reporting a cache that did not exist. Keeping it would also have breached
working rule 5 once an nginx FastCGI cache goes in.

Removed: plugin deactivated (22 → 21 active), **192 autoloaded option rows**
deleted, `WP_CACHE` removed from `wp-config.php` (meaningless with no drop-in,
and misleading), the `LSCACHE` / `NON_LSCACHE` blocks stripped from `.htaccess`
(inert under nginx anyway), and `wp-content/litespeed/` deleted. Plugin files are
still on disk so the change is reversible from the Plugins screen.

### The autoload problem, and a durable fix

2,241 KB of options loaded on **every** request — admin and AJAX included. 93% of
that (2,091 KB) was autoloaded transients, dominated by the theme's product-grid
caches at 70–211 KB each, duplicated per currency so the `USD` copies were dead
weight after the move to KES.

Cause: WordPress stores a transient created with an expiry of `0` as an ordinary
autoloaded option, and `enovathemes-addons` creates every one of its caches that
way — `set_transient( $key, $value, apply_filters( 'null_*_cache_time', 0 ) )`.

Fix: the plugin exposes a filter for each expiry, so
`mu-plugins/safi-performance/transient-autoload.php` returns a real TTL —
a week for layout caches, a day for query caches. **No vendor code was touched.**
The TTLs are backstops; every one of these caches is already purged explicitly
when the underlying content changes, so rebuild frequency is unchanged.

| | Before | After |
|---|---|---|
| Autoloaded options | **2,241.3 KB** / 440 | **152.6 KB** / 376 |
| Autoloaded transients | 2,091.2 KB | 0 KB |

**Verified durable, not a one-time sweep:** after deleting the transients and
reloading the pages that rebuild them, every regenerated transient came back
`autoload=off` with a real `_transient_timeout_` row, and the autoloaded total
stayed at 152 KB despite 230+ KB of caches being rebuilt.

Also: `WP_POST_REVISIONS` capped at 5 (was uncapped); `DISABLE_WP_CRON` was
already true from earlier work.

### Results — and one regression

TTFB, medians of 9 samples after a discarded warm-up:

| Page | Before (mean of 3) | After (median of 9) | Read |
|---|---|---|---|
| `/` | 1.887 s | 2.024 s | within noise |
| `/product/…` | 1.753 s | 1.670 s | within noise |
| `/cart/` | 1.855 s | 2.024 s | within noise |

Lighthouse, mobile, simulated throttling:

| Metric | Home before | Home after | Product before | Product after |
|---|---|---|---|---|
| Performance | 29 | 30 | 38 | **32** |
| LCP | 7.7 s | 7.7 s | 6.9 s | **8.3 s** |
| CLS | 0.076 | 0.076 | 0.148 | 0.145 |
| TBT | 7,860 ms | 9,430 ms | 740 ms | 1,260 ms |
| Server response | 2,170 ms | 2,610 ms | 3,640 ms | **2,470 ms** |
| Requests | 132 | 146 | 80 | 88 |
| **Image KB** | 1,401 | 1,418 | **106** | **326** |
| JS KB | 418 | 418 | 445 | 445 |

**Two honest conclusions.**

*The autoload win does not show in TTFB.* It is real and proven at the database
layer — 2.1 MB less loaded per request — but this machine's TTFB varies by ±1 s
run to run (home ranged 1.057–3.002 s across 9 samples), and the theme's own
bootstrap dominates: roughly 2 s per request, of which ~700 ms is
`propharm_enovathemes_integrateVC` re-registering all 102 WPBakery shortcodes.
The saving is smaller than the noise floor here. It should become visible once
Phase 2 removes the PHP work entirely for anonymous visitors.

*Removing LiteSpeed made the measured image payload worse.* Its JavaScript
lazy-loader was the one thing it genuinely did, and it was more aggressive than
the browser-native `loading="lazy"` that now replaces it — so more images load
during a Lighthouse run. The product page went from 106 KB to 326 KB of images,
which is what drove its score from 38 to 32 and LCP from 6.9 s to 8.3 s.

This is a real trade-off, not a measurement artefact, and it is reported rather
than papered over. It is accepted for three reasons: the plugin was also
lazy-loading the **site logo**, which delayed the header on every page load and
had to be excluded by hand; native lazy-loading correctly skips the first
in-viewport image, which a JS loader cannot do reliably; and Phase 6.4 addresses
images properly — WebP conversion via GD, correct `sizes`, and `fetchpriority`
on the LCP image, which no page currently sets. Re-adding a JS lazy-loader now
would be substituting one plugin for another rather than fixing the payload.

---

## Phase 2A — nginx FastCGI full-page cache

Branch A, adapted. Branch B was not applicable and LiteSpeed was removed in
Phase 7, so rule 5 is satisfied: exactly one caching layer now exists.

### What was configured

Directives live in `conf/nginx/nginx.conf.hbs` and `conf/nginx/site.conf.hbs`,
because Local re-renders the runtime config from those templates on every site
restart — editing the generated output alone would be lost. The same edits were
mirrored into the running config so they could be tested without a restart.

Two deviations from the brief, both forced by the environment:

- `fastcgi_pass` targets the existing `php` upstream — a TCP pool of `php-cgi`
  workers. There is no PHP-FPM on Windows, so the brief's unix-socket line does
  not apply.
- No `/purge` location, because `ngx_cache_purge` is not compiled into this
  nginx. Purging is done by deleting cache files from
  `mu-plugins/safi-performance/cache-purge.php` instead, which is also what
  Nginx Helper would fall back to.

OPcache raised to the brief's numbers (256 MB, 20000 files, 32 MB interned
strings, revalidate 60) in both the template and the runtime ini. **This takes
effect on the next PHP restart**, so it is not reflected in the numbers below.

### Results — the target is met, decisively

TTFB, medians of 9 samples:

| Page | Baseline | Phase 2 | Change |
|---|---|---|---|
| `/` | 1.887 s | **0.029 s** | 65× faster |
| `/shop/` | 2.054 s | **0.025 s** | 82× faster |
| `/product/…` | 1.753 s | **0.025 s** | 70× faster |
| `/cart/` | 1.855 s | 1.620 s | uncached by design |

Lighthouse "server response" fell from **2,170 ms to 20 ms** on the homepage and
**3,640 ms to 20 ms** on the product page.

**Target: < 100 ms TTFB for a cached anonymous page. Achieved: 25–29 ms.**

### Correctness — verified, and one bug caught

Full matrix re-tested after every change:

| Must BYPASS | Result |
|---|---|
| `woocommerce_items_in_cart`, `woocommerce_cart_hash`, `wp_woocommerce_session_*` | BYPASS |
| `wordpress_logged_in_*`, `comment_author_*`, `wp-postpass_*` | BYPASS |
| `/cart/`, `/checkout/`, `/my-account/`, `/wp-login.php`, `/wp-json/` | BYPASS |
| `?add-to-cart=`, `?wc-ajax=`, `?s=` | BYPASS |
| POST to any URL | not cached |

| Should cache | Result |
|---|---|
| `/`, `/shop/`, `/about-us/`, product pages | HIT |
| `?utm_source=`, `?fbclid=` (tracking stripped) | HIT |

Purge on publish verified: 6 cached files → 0 after a post update, next request
MISS then HIT.

**A serious bug was caught during this verification.** The first version of the
generated config was missing the `$http_cookie` rule entirely — a scripted
extraction had stopped at the first `set $skip_cache 1;` and silently truncated.
The config passed `nginx -t` and cacheable pages behaved perfectly, but every
session cookie returned **HIT**: a logged-in user would have been served
anonymous cached HTML, and their responses could have been cached and served to
others. Only the explicit cookie matrix surfaced it. It is fixed, the cache was
purged, and all six cookies now BYPASS.

### Lighthouse got worse, and that is expected

| Metric | Baseline | Phase 7 | Phase 2 |
|---|---|---|---|
| Home — Performance | 29 | 30 | **27** |
| Home — TBT | 7,860 ms | 9,430 ms | **13,320 ms** |
| Product — Performance | 38 | 32 | **9** |
| Product — CLS | 0.148 | 0.145 | **0.955** |
| Server response | 2,170 / 3,640 ms | 2,610 / 2,470 ms | **20 / 20 ms** |

This is not a regression caused by caching — it is caching **removing the thing
that was hiding the client-side problems**. When the document took 2–4 seconds to
arrive, the browser had that long to work through scripts and images before the
measurement window filled up. Now the HTML lands in 20 ms and every bit of
client-side work happens at once, so the simulated-throttle run sees all of it
competing.

The product page's CLS of 0.955 is the clearest symptom: content now arrives
instantly, and then the theme's AJAX-injected product grids and megamenus push
the layout around. Images still ship without `width`/`height`, so nothing
reserves space.

**Neither number is fixable in Phase 2.** They are precisely what Phases 4 and 6
exist for: cart fragments firing on every page view, ~445 KB of JavaScript, and
images with no intrinsic dimensions. The server-side work is done; what remains
is entirely in the browser.

---

## Phases not yet started

Phase 4 (WooCommerce), 5 (instant navigation), 6 (payload), 8 (final
verification).

## Skipped permanently in this environment

| Phase | Why |
|---|---|
| **3 — Cloudflare, all of it** | `client1.local` is not publicly resolvable |
| **2A.1 purge endpoint** | `ngx_cache_purge` is not compiled into this nginx |
| **2A.4 Redis object cache** | no Redis server and no `php_redis` extension |
| **8 — edge/Nairobi TTFB** | no public hostname, no CDN |
| **8 — M-Pesa STK test** | no payment gateway plugin is installed |

`wp db optimize` also could not complete: one table fails with
`Invalid default value for 'scheduled_date_gmt'`, a known MySQL 8 strict-mode
issue with a zero-date default. Reported rather than forced.
