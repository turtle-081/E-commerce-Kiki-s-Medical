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

## Phase 4 — WooCommerce and theme AJAX

The goal for this phase was the brief's "0 uncached PHP requests per anonymous
page view". The page cache from Phase 2 made the *document* free; this phase is
about the requests that fire after it.

### Starting point

A single anonymous `/shop/` view made **8 uncached PHP requests** behind the
cached HTML:

| Source | What it was |
|---|---|
| `?wc-ajax=get_refreshed_fragments` | WooCommerce cart fragments |
| 5–6 × `/ajax-api/…` | theme `cache-queries` mode — footer, megamenus, product grids |
| `admin-ajax.php` | mini-cart contents |

### 4.1 / 4.2 — cart fragments

`wc-cart-fragments` is dequeued everywhere except `/cart/` and `/checkout/`,
which are uncached by design. Its only real job on other pages is the header cart
count, so that is replaced with ~1 KB of inlined JavaScript that reads
`wc/store/v1/cart` — **but only when the `woocommerce_items_in_cart` cookie is
present**. A visitor browsing without a cart makes zero requests for this;
previously every visitor made one.

Verified end to end: anonymous shop page HIT with an empty badge → add product →
all three Woo cookies set → Store API returns `items_count: 1`, `total: 2890
KES` → shop page now BYPASS → cart page correct.

### 4.3 / 4.4 — asset scoping

The brief warns that `is_woocommerce()` is false on pages that merely *display*
products, and that dequeuing there breaks add-to-cart. That is exactly this site
— the homepage alone has 35 add-to-cart links. So `safi_page_needs_woo()` also
scans `post_content` for the theme's product shortcodes rather than relying on
a hand-written allowlist.

Confirmed: Woo assets retained on `/`, `/shop/`, `/product/…`, `/cart/`;
dequeued on `/about-us/`, `/faq/`, `/delivery-information/`.

Marketplace suggestions and background image regeneration are disabled.
**`woocommerce_admin_disabled` is deliberately NOT set** — the brief requires
confirmation first, and it has not been given. It is the largest remaining
admin-side win and is worth asking about.

### 4.5 / 4.6 — the theme's own AJAX (the bigger win)

`cache-queries` was turned **off**. My earlier recommendation to leave it on was
made before a page cache existed, and it no longer holds: the `/ajax-api/`
endpoints can never be cached, so with cached HTML in front they were pure
overhead. Turning it off eliminated all 5–6 of them and the content now renders
inline. TTFB was unchanged (25–60 ms, still HIT).

That left three `admin-ajax.php` calls, identified by logging the actual actions
server-side rather than guessing:

| Action | Payload | Fixed? |
|---|---|---|
| `megamenu_load` | 62 KB | mostly — see below |
| `mobile_load` | 2.5 KB | yes |
| `update_mini_cart_contents` | 321 B | no |

Both fixable ones were **theme settings, not code**: the header-button shortcode
carries `megamenu_ajax="true"` and the mobile container carries `async="true"`,
each of which emits an empty placeholder and fetches its contents on every page
view. Deferring like that is sensible with no page cache; with one it is exactly
backwards, because the inline copy is baked into the cached response for free
while the AJAX copy is an uncached PHP hit — roughly 1.4 s each on this machine —
every single time.

Flipped via `tools/inline-header-megamenu.php` and `tools/inline-mobile-header.php`,
both of which store the original `post_content` in post meta and support
`--revert`. No vendor code was touched, and the same toggles exist in the theme's
header builder UI.

**One trap worth recording:** the first attempt appeared to do nothing. The theme
caches rendered header markup in transients, and Phase 7 had just given those a
one-week TTL, so the old markup kept being served. `tools/flush-theme-caches.php`
was added to clear them.

### Result

| | Before | After |
|---|---|---|
| Uncached PHP requests per anonymous page view | **8** | **2** |
| Document, gzipped | ~48 KB | ~100 KB |
| Total wire bytes for the same content | ~112 KB | ~103 KB |

Fewer bytes, six fewer round trips, and nothing left that blocks on PHP except
the two below. TTFB stayed at 29–60 ms HIT throughout.

**The two that remain, and why.** `update_mini_cart_contents` (321 B) fires
whenever the body carries `woocommerce-js`; suppressing it means either removing
that class — which disables AJAX add-to-cart — or patching theme JavaScript. Not
worth the risk for 321 bytes. `megamenu_load` (now 10.7 KB, down from 62 KB)
survives because the theme's JavaScript collects **every** `.menu-item.mm-true`
regardless of the per-item ajax flag; only header *buttons* honour the setting.
Removing it would require patching `controller.js`, which working rule 4 puts
off-limits. Both are documented rather than silently left.

So the "0 uncached PHP requests" target is **not** met — it is 2, down from 8.
Reaching 0 needs vendor JavaScript changes that the brief prohibits.

### Lighthouse — no improvement, and an earlier claim withdrawn

| Metric | Baseline | Phase 2 | Phase 4 |
|---|---|---|---|
| Home — Performance | 29 | 27 | 30 |
| Home — LCP | 7.7 s | 8.2 s | 8.7 s |
| Home — TBT | 7,860 ms | 13,320 ms | 8,690 ms |
| Product — Performance | 38 | 9 | 7 |
| Product — CLS | 0.148 | 0.955 | 0.955 |
| Document KB | 44 / 48 | 47 / 49 | 97 / 100 |
| JS KB | 418 / 445 | 419 / 446 | 418 / 445 |

Mid-phase I recorded product CLS improving from 0.955 to 0.076 and called it a
Phase 4 win. **That was wrong.** Three identical runs of the finished
configuration returned:

| Run | Score | LCP | CLS | TBT |
|---|---|---|---|---|
| 1 | 17 | 7.9 s | 0.955 | 1,180 ms |
| 2 | 30 | 8.3 s | **0.076** | 4,190 ms |
| 3 | 6 | 8.7 s | 0.955 | 2,890 ms |

CLS on this page is **bimodal** — it lands on 0.955 or 0.076 depending on a race,
and the score swings between 6 and 30 with no configuration change at all. The
0.076 I saw was a coin flip, not a result. Any single Lighthouse number from this
page should be treated as unreliable until the underlying race is fixed.

The shift itself is one event of 0.955 — an entire viewport — and Lighthouse's
`unsized-images` audit *passes*, so it is not the usual missing-dimensions cause.
A live `PerformanceObserver` on an unthrottled load recorded zero shifts, which
fits an intermittent that only reproduces under throttling. Diagnosing it belongs
in Phase 6 alongside the rest of the client-side work.

Document size roughly doubled, which is the honest cost of inlining. It is
accepted because the bytes it replaced were larger (a 62 KB XHR plus a 2.5 KB
one), they are now compressed and cached rather than fetched live, and each
removed request cost ~1.4 s of PHP. Phase 6 should trim the megamenu markup
itself, which is the real problem — 316 KB of raw HTML for a navigation menu.

**Phase 4 did not move Lighthouse, and was not going to.** Both pages still ship
~420–445 KB of JavaScript and ~2.2 MB total, and RevSlider accounts for 9 of the
10 heaviest requests. That is Phase 6's scope, and it is now the only thing left
standing between this site and the target scores.

---

## Phase 5 — instant navigation (Speculation Rules)

### Discovery: it was already on, at its weakest setting

WordPress 7.0.2 ships speculative loading in core (6.8+), and it was already
emitting rules — but `prefetch` with `conservative` eagerness, which fires on
*pointerdown*. By then the visitor has already committed to the click, so it
saves a little latency and does not feel instant.

Raised to **`prerender` / `moderate`**: Chrome renders the next page in full in
the background once a link has been hovered for roughly 200 ms, so the
navigation is close to zero. This is only affordable because of Phase 2 — the
speculative request is a cached HIT at ~30 ms rather than a 2 s PHP render.

Core disables speculation entirely for logged-in users and for sites without
pretty permalinks. `speculation-rules.php` returns early when core has already
decided to disable, so that behaviour is preserved rather than overridden.

### The exclusions are the important half

Core excludes `wp-admin`, `wp-*.php`, `wp-content` and — because pretty
permalinks are on — **any URL with a query string**, which already covers
`?add-to-cart=` and nonce-bearing links.

What core does not know about is WooCommerce, and **WooCommerce registers no
exclusions of its own** (checked in its source). Under `prerender` the page
genuinely executes: scripts run, the Store API is called, a session starts. On a
store, prerendering `/cart/`, `/checkout/` or `/my-account/` means creating
sessions for visitors who never went there, and activating a page that may be
stale.

Those three are now excluded, resolved through `wc_get_page_id()` rather than
hardcoded so renaming the cart page in WooCommerce settings cannot silently
reopen the gap — with the literal defaults kept as a fallback.

A small inline script additionally marks state-changing links with core's
`.no-prefetch` opt-out class — add-to-cart, remove-from-cart and logout — on the
principle that a link which mutates state should never be speculatively
followed, whatever its URL shape. Verified: **39 of 39** add-to-cart links on
`/shop/` are marked.

### Verified

| Check | Result |
|---|---|
| Mode / eagerness emitted | `prerender` / `moderate` |
| `/cart`, `/checkout`, `/my-account` (+ `/*`) excluded | yes |
| Query strings, `wp-admin`, `wp-*.php` excluded | yes (core) |
| Add-to-cart links marked `.no-prefetch` | 39 / 39 |
| Rules suppressed for logged-in users | yes (core, preserved) |
| Browser reports prerender support | yes |
| TTFB unchanged | 30 ms `/shop/`, still HIT |

### Not verified in this environment

**Live prerender activation could not be exercised here.** Speculation rules
respond only to genuine user hover, and pointer input cannot be dispatched in
this environment: the browser pane is not displayed, so the page never
composites frames and no screenshot — and therefore no coordinate-based hover —
is possible. DOM inspection and scripted measurement work fine; only real
pointer input does not.

The emitted rules, the exclusion list and the `.no-prefetch` marking are all
verified directly. What remains unverified is the browser actually activating a
prerendered page, which should be confirmed by hand in a real browser via the
Application panel's Speculative Loads view, or by checking that
`performance.getEntriesByType('navigation')[0].activationStart > 0` after a
hovered navigation.

Lighthouse is unaffected by this phase by design: it measures a single cold page
load, which is exactly the case speculation cannot help.

### Trade-off worth knowing

`prerender` executes the whole page, and this site still ships ~420–445 KB of
JavaScript (Phase 6's problem). On low-end mobile, prerendering two pages in the
background is real work. Chrome caps concurrent moderate-eagerness prerenders,
so it is bounded, but if the client reports battery or jank complaints on cheap
Android hardware, changing `'prerender'` to `'prefetch'` in
`speculation-rules.php` is a one-line dial-back that keeps every exclusion
intact.

### Noted, not fixed (out of scope)

Two leftover theme demo menu items link to `/nothing`, which returns 404, and
render in the navigation labelled "404 Error Page" (`menu-item-1804`,
`menu-item-389`). They are demo content rather than a Phase 5 issue, and are
flagged separately for cleanup.

---

## Phase 6 — payload *(in progress)*

### 6.1 — Slider Revolution, the single biggest item in the payload

`tptools.js` (73 KB) and `sr7.js` (94 KB), plus `migration.js` and `sr7.css`,
loaded on **every page of the site including `/cart/`**. Together they were
larger than jQuery, the theme's combined plugin bundle and WooCommerce put
together — 9 of the 10 heaviest requests on the product page.

Exactly one page uses a slider: the front page (#373, "Home 5"), which renders
Slider 5.

**The obvious check gives the wrong answer here, and it is worth recording why.**
Slider Revolution 6.7 builds its markup *client-side*: the server sends no
`<rs-module>` element, and the `SR7-MODULE` node only exists after `sr7.js` has
run. Parsing the served HTML for slider elements reports **zero sliders on every
page including the homepage** — which would have made dequeuing everywhere look
perfectly safe, and would have silently broken the homepage. The homepage was
confirmed to genuinely use a slider by inspecting the live DOM
(`SR7-MODULE#SR7_5_1`, `window.revapi5`, 4 slides, 424 px tall), not the source.

`slider-assets.php` therefore detects sliders by the shortcode in the content —
which is what actually drives the render — and checks the header and footer
builder post types as well, since either can embed one.

Verified after the change: slider assets load on `/` **only**, and the homepage
slider still builds with all 4 slides intact.

| Product page | Before | After |
|---|---|---|
| **JavaScript** | **445 KB** | **254 KB** |
| Total transfer | 1,136 KB | 866 KB |
| Requests | 100 | 83 |
| TBT (3 runs) | 1,184 / 4,188 / 2,892 ms | 628 / 1,160 / 1,441 ms |
| LCP (3 runs) | 7.9 / 8.3 / 8.7 s | 7.2 / 7.7 / 7.9 s |

**Target: < 300 KB of JavaScript on the product page. Achieved: 254 KB.**

Payload, request count and TBT are deterministic and all improved clearly. The
Lighthouse *score* is not: it read 40 / 13 / 11 across the three runs, against
17 / 30 / 6 before. That spread is the same bimodal CLS problem documented in
Phase 4 — the score is dominated by which side of the coin flip each run lands
on, so it should not be read as a result in either direction until that is
fixed. The homepage is unchanged, as expected, since it keeps the slider.

### A Phase 4 bug found and fixed here

Checking the console on a dequeued page surfaced a `TypeError: Cannot read
properties of null (reading 'addEventListener')` coming from my own Phase 4
cart-count script. The script attaches to `jquery-core`, which this theme
enqueues in the `<head>`, so `document.body` was still null when it ran. The
throw aborted the rest of the script and took the jQuery `added_to_cart` binding
with it — meaning the cart badge would not have updated after an AJAX
add-to-cart.

Moved into a `bind()` function that runs on `DOMContentLoaded` and guards
`document.body` regardless. Verified in a **fresh browser tab** — necessary
because console messages accumulate across navigations in this tool, which has
produced a false conclusion earlier in this engagement. Only the pre-existing
`plugins-combined.js` `HierarchyRequestError` remains.

### 6.2 / 6.3 — the intermittent layout shift, found and fixed

The bimodal CLS documented in Phase 4 turned out to be two separate causes, and
neither was the one every audit pointed at. Lighthouse's `unsized-images` audit
**passes** on this page and every image has its box reserved by CSS, so the usual
explanation was ruled out early. Lighthouse also could not attribute the shift —
its `layout-shifts` audit reported one 0.955 event with no node — so this needed
the raw trace.

The trace was unambiguous:

```
node 124  [0, 64, 412, 759]  ->  [0, 128, 412, 695]   the entire page content
node 121  [324, 0,  32,  64] ->  [343, 64,  32,  64]  a header icon wraps
```

The header was not growing because something loaded into it. It was growing
because its contents **re-wrapped onto a second row**, doubling it from 64 px to
128 px and pushing the whole page down by 64 px. Whether that happened before or
after first paint decided which side of the coin flip a run landed on.

**6.2 — the font.** The theme loads PT Sans from Google Fonts with
`display=swap`, and only the *stylesheet* is preloaded, not the font files. So
the header rendered in a fallback face, the real font arrived ~150 ms later,
every string in the header changed width, and the row re-wrapped. `swap` is
right for body copy, where a reflow is invisible; it is wrong for a fixed-height
header bar, where it moves the entire page.

Changed to `display=optional`, which gives the font a short window and then
keeps the fallback **for that page view without swapping** — one layout, so no
shift. The font is still cached for every later view, and with the page cache and
Phase 5 prerendering those are the common case. Preloading the woff2 files
directly was rejected: Google serves them from hashed URLs that would rot into
dead preloads. A `crossorigin` preconnect to `fonts.gstatic.com` was added, since
font fetches are CORS requests and a preconnect without it opens a connection the
fetch cannot reuse.

That fixed three runs in four. The fourth still hit 0.955, so the header was
measured directly at Lighthouse's emulated 412 px width:

| Element | x | y | width |
|---|---|---|---|
| `.header-slogan` — "Free delivery within Nairobi" | 21 | 0 | 166 |
| `.header-logo` | 186 | 0 | 138 |
| `.mobile-container-toggle` | 372 | 0 | 19 |
| `.header-product-search-toggle` | 343 | **64** | 32 |

**6.3 — the real culprit.** There was never room for four items on that row. The
slogan is the one that does not fit — it was added to the header when the COVID
notice was replaced, and at mobile widths it leaves the search toggle nowhere to
go, so the toggle wraps and the header doubles. The font merely decided *when*
that happened.

Rather than hide the slogan — the client asked for that message — it is given its
own full-width row in `brand.css`, so the header is deterministically two rows at
every viewport width and cannot change height after paint. Verified at 412 px:
slogan alone on row 1, logo and both toggles on row 2 with room to spare.

### Result

| Stage | CLS across runs | Max | Scores |
|---|---|---|---|
| 6.1 — RevSlider only | 0.145 / 0.955 / 0.955 | 0.955 | 40, 13, 11 |
| 6.2 — + `font-display` | 0.071 / 0.071 / 0.002 / 0.955 | 0.955 | 53, 34, 32, 9 |
| 6.3 — + header row fix | 0.069 / 0.046 / 0 / 0.069 / 0 | **0.069** | 57, 32, 35, 31, 36 |

The 0.955 outlier is gone across five consecutive runs, and the score stopped
swinging between 9 and 53 — which finally makes this page's numbers worth
comparing at all.

**The CLS target of < 0.05 is not yet met**: the worst of five runs is 0.069 and
the median is 0.046. What remains is small and no longer bimodal — the logo box
resolving from 65 px to 66 px is visible in the trace and is the likely
remainder. Honest position: substantially fixed, not finished.

### Still to do in this phase

`fetchpriority` on the LCP image (set on no page currently), WebP conversion,
unused CSS (~156 KB, mostly `propharm/style.css` at 78 KB and
`js_composer.min.css` at 46 KB), and the last ~0.02 of CLS.

---

## Phases not yet started

Phase 8 (final verification).

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
