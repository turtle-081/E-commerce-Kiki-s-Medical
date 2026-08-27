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

### 6.4 — static asset caching, and unblocking the render

Two changes, both aimed at what the audits actually ranked highest rather than at
the usual image advice.

**Static asset lifetimes.** Local's nginx defaults were
`Cache-Control: no-cache, must-revalidate` for CSS and JS, and `max-age=300` —
five minutes — for images and fonts. Lighthouse flagged 345 KB being re-fetched
or revalidated on every visit. Every one of these URLs is versioned (WordPress
appends `?ver=`, and this project's own stylesheets use `filemtime()`), so the
bytes behind a given URL never change; they are now
`public, max-age=31536000, immutable`. The `expires` directive was dropped in
favour of a single `add_header`, because together they emitted two
`Cache-Control` headers.

**Render-blocking JavaScript — the largest single item on the page at ~1,950 ms.**
The biggest entry was jQuery at 918 ms, loaded synchronously in the `<head>`.

Deferring jQuery on a WordPress site is usually reckless, so it was checked
rather than assumed: of 28 inline script blocks on the product page, 15 are
WordPress-managed and of the remaining 13 exactly **one** touches jQuery — the
Phase 5 `.no-prefetch` marker, which already guards with `if (window.jQuery)`.
The theme has no raw inline jQuery at all.

`script-loading.php` therefore asks for `defer` broadly and lets core decide.
Since 6.3, `WP_Scripts::filter_eligible_strategies()` only defers a script when
every script depending on it can also be deferred, so anything that would break
ordering is silently left blocking. That safety net is why this is a broad opt-in
rather than a hand-maintained allowlist.

Two things had to be fixed before core would actually defer jQuery:

1. *A late-enqueued dependent.* The search widget enqueues its script while the
   body renders, after `wp_enqueue_scripts` has run — so it was never marked, and
   one un-marked dependent was enough to keep jQuery blocking. A second pass on
   `wp_print_footer_scripts` catches late arrivals.

2. *My own Phase 4 code.* Core refuses to defer any handle with an inline script
   in the `'after'` position, and that propagates to everything depending on it.
   The cart-count script was attached to `jquery-core` — so 1 KB of cart code was
   pinning jQuery as render-blocking in the head. It now has its own `src`-less
   handle, which costs no request.

Result: **zero render-blocking scripts in the `<head>`**, and render-blocking
savings down from 1,950 ms to 550 ms.

### Verified working after deferring

This is the riskiest change in the engagement, so it was tested rather than
assumed, in a fresh tab for a clean console:

| Check | Result |
|---|---|
| jQuery available | 3.7.1 |
| `wc_add_to_cart_params`, `controller_opt` | both present |
| AJAX add-to-cart on `/shop/` | `added_to_cart` fired, cookie set, Store API `items_count: 1`, `1540 KES` |
| Cart badge after add | shows **1** |
| Product gallery / tabs / related | 1 / 51 / 5 |
| Homepage slider | 4 slides, 424 px, `revapi5` |
| Megamenus, carousels | 4 filled, 23 carousels |
| New console errors | none — only the pre-existing `plugins-combined.js` one |

### Result

| Product page | 6.3 | 6.4 |
|---|---|---|
| Score (3 runs) | 57 / 32 / 35 | 50 / 42 / 39 |
| **CLS** | 0.069 / 0.046 / 0 | **0 / 0.002 / 0** |
| FCP median | 5.4 s | **3.6 s** |
| TBT | 441 / 1,974 / 1,511 ms | 488 / 1,212 / 1,168 ms |
| LCP median | 7.5 s | 7.8 s |

**Target: CLS < 0.05. Achieved: 0–0.002.** The homepage also went from 0.076 to
**0**. Deferring is what finished the job the Phase 6.2/6.3 work started — with
scripts running after parse rather than during it, there is no mid-parse layout
change left to measure.

**One regression, reported not buried:** homepage TBT rose from 6,321 ms to
9,926 ms. Deferring means all the JavaScript executes in one burst after parsing
instead of being spread through it, and the homepage carries RevSlider plus 44
products. FCP and CLS improved and the score was flat (28 → 29), so this is kept
— but it is a real trade and the homepage's script weight is the thing that needs
addressing, not the defer.

LCP did not move. It is now the binding constraint.

### 6.5 — responsive images: tried, measured, and reverted

This one did not work, and the attempt is recorded because the reason is the
useful part.

**The problem is real.** The theme renders images by hand —
`wp_get_attachment_image_src( $image, 'full' )` into a raw `<img>` — so core's
`wp_filter_content_tags()` never sees them. On the homepage: **73 images from
uploads, 0 with `srcset`, 0 with `fetchpriority`**, only 14 lazy. Twenty render
at ~225 px and nine at ~100 px while every one downloads the 1000×1000 original.
The resized files (100×100 through 768×768) already exist on disk, unused.

**And there is a second cause underneath it.** `wp_get_attachment_image_srcset()`
returned false for every image even though the metadata was healthy (1000×1000,
12 sizes). `enovathemes-addons` disables responsive images site-wide and
unconditionally — there is no setting for it (enovathemes-addons.php:1862):

```php
add_filter( 'wp_calculate_image_srcset', '__return_empty_array', PHP_INT_MAX );
```

So a retrofit has to build the attribute itself.

**Attempt 1 — core's heuristic.** First large image gets `fetchpriority="high"`
and loads eagerly; everything after it gets `loading="lazy"` and `sizes="auto"`.
Image bytes on the product page fell from 349 KB to 225 KB. Everything else got
worse:

| Product (medians of 3) | Before | Aggressive |
|---|---|---|
| Score | 42 | **35** |
| LCP | 7.8 s | 7.3 s |
| TBT | 1,168 ms | **2,173 ms** |
| Image KB | 349 | 225 |

| Homepage | Before | Aggressive |
|---|---|---|
| LCP | 9.0 s | **12.4 s** |
| FCP | 3.9 s | 4.5 s |

Core's heuristic assumes the first large `<img>` in source order is the element
that paints largest. On this theme it is not: the hero is a Slider Revolution
module built by JavaScript, so the first `<img>` in the markup is somewhere else
entirely. Guessing wrong means lazy-loading the element that actually paints —
which is what a 3.4 s LCP regression on the homepage looks like.

**Attempt 2 — no guessing.** Restricted to images the theme had *already* marked
`loading="lazy"`, adding only `srcset` and `sizes="auto"`. Safe by construction:
it cannot change which element is the LCP. It also did nothing — **0 images
matched**, because the theme's own lazy-loaded images use a `data-src`
placeholder, which this correctly skips. Product medians were within noise of
baseline (358 KB vs 349 KB image, score 38 vs 42).

**So it was reverted.** A change that either measures worse or does nothing does
not belong in the tree. `image-delivery.php` is deleted; `git log` has the full
implementation if it is ever wanted.

**What would actually fix it** is changing how the theme requests images —
`wp_get_attachment_image_src( $image, 'propharm_425X425' )` instead of `'full'`
for grid contexts — and dropping the plugin's blanket srcset filter. Both are
vendor-code changes, which working rule 4 puts off-limits, and both belong
upstream or in a child-theme override of the specific shortcodes. This is the
single largest remaining payload win and should be scoped as its own piece of
work.

### Still to do in this phase

- **Images** (~182 KB) — blocked on the above; needs vendor-side changes.
- **Unused CSS** (156 KB / 930 ms) — `propharm/style.css` 78 KB and
  `js_composer.min.css` 46 KB.
- **HTTP/2** — `modern-http-insight` estimates 680 ms. Local serves this site
  over plain HTTP/1.1; HTTP/2 requires TLS. Environment-specific, and production
  would differ, so not pursued here.

Noted while reading the image audit and out of scope: every image on the page
has `alt="One"` — the site name, not a description. That is an accessibility
problem, not a performance one, but it should be fixed before handover.

### 6.6 — a regression the final smoke test caught

The last check before writing this up found the **homepage footer rendering as an
empty placeholder**. Worth recording both because it was user-visible and because
of how it was introduced.

The theme can load a footer over admin-ajax, leaving a fixed-height placeholder
until the response arrives. It is controlled per footer by
`enovathemes_addons_footer_async`, with per-context exemptions
(enovathemes-addons.php:624-658). On this site the **shop and product exemptions
were already on** — which is exactly why `/shop/` and `/product/` had real
footers throughout this engagement while the homepage did not. The split was
latent and invisible: the homepage footer was being hydrated by JavaScript.

Deferring scripts in Phase 6.4 turned that latent split into a visible bug. The
hydration call never fired — one `admin-ajax` request on the homepage instead of
five — so the footer stayed blank.

Diagnosis was by bisection rather than inspection: disabling `script-loading.php`
and reloading restored the footer, which pinned the cause precisely. A manual
`footer_load` POST returned a perfectly good 15 KB of footer HTML, confirming the
server side was fine and the trigger was the problem.

The fix is not to stop deferring. It is to render the footer inline, which is the
same decision already taken for the header megamenu and the mobile header:

- it removes another uncached PHP request per page view,
- the markup lands in the page cache, so it costs nothing per visit,
- and it cannot break, because there is no client-side step left to fail.

Applied with `tools/inline-footer.php` (backs up the original meta, supports
`--revert`) across all five footers. Verified: zero placeholders on `/`, `/shop/`
and `/product/`, and the homepage footer now carries the full address, phone,
email and copyright inline with defer still active.

**The lesson worth keeping:** deferring scripts does not just change timing, it
changes whether timing-dependent hydration runs at all. Anything on this site
that paints via JavaScript needs checking against it — which is why the smoke
test covered the slider, megamenus, cart and gallery, and why it was worth doing
one more pass over a page I thought was finished.

### 6.7 — responsive images, attempted again and reverted again

Phase 6.5 tried this with an output-buffer retrofit and failed because it moved
the LCP. This attempt avoided that failure completely and still had to be
reverted, for a different and more interesting reason. Recording it because the
conclusion changes what should be recommended next.

**The two blockers were identified precisely this time.** First,
`enovathemes-addons` disables responsive images site-wide, but it does so from a
*named* function on `init` (`enovathemes_addons_disable_responsive_images`,
enovathemes-addons.php:1850), so it can be lifted from a mu-plugin with a single
`remove_action` on `plugins_loaded` — no vendor edit. Second, the theme builds
thumbnails as raw HTML and echoes them straight into WooCommerce loop hooks, so
they never pass `the_content` and core's `wp_filter_content_tags()` never sees
them. That was not assumed: all four content filters were instrumented and the
product grid images appeared in none of them.

**The implementation deliberately could not repeat 6.5.** It added `srcset` and
`sizes` only to images that already carried `loading="lazy"`, and wrote no
`loading`, `fetchpriority` or `decoding` attribute at all. `sizes="auto"` was
used so the browser would match the real layout width rather than the viewport.
`tools/check-image-attrs.py` diffed every image attribute on three pages before
and after and confirmed it: 24 images gained `srcset`/`sizes`, and not one
loading decision moved.

**It did not work, and the reason is structural.** Checking `currentSrc` in the
live DOM: of the 13 images that gained a `srcset`, **12 had a layout width of
zero at selection time** — they sit inside carousels that have not been laid out
when the browser picks a candidate. `sizes="auto"` has nothing to measure, so it
falls back and the browser takes the largest candidate. Six of the thirteen
fetched the full-size original anyway.

**And lifting the vendor filter made the page heavier.** With responsive images
restored, the main WooCommerce product image gained a proper `srcset`, and on
Lighthouse's mobile profile — 412 px wide at DPR 2.625 — core's `sizes` asks for
more device pixels than the theme's fixed choice, so the browser upgraded it:

| | Baseline | With responsive images |
|---|---|---|
| Main product image | `product13-600x600.jpg`, 16 KB | `product13-768x768.jpg`, 22 KB |

Identical in all three runs, so this is not noise. Everything else was within
run-to-run spread.

**The finding worth keeping** is that the premise recorded after 6.5 — that
image delivery was the single largest remaining payload win, blocked only by
vendor code — is wrong as stated. Lighthouse's "larger than it needs to be"
savings are computed against CSS pixels; at DPR 2.625 a *correct* responsive
implementation asks for roughly 2.6x more pixels than the theme's hardcoded
sizes do. The theme's crude fixed sizes are, on this profile, already smaller
than what proper responsive images would request. Serving fewer bytes here means
serving a *worse* image than the browser asks for, which is a client decision
about quality, not a technical one.

Reverted. `image-delivery.php` is deleted; `tools/check-image-attrs.py` is kept,
because it is the guard that made this attempt cheap to evaluate and would make
the next one cheap too.

### 6.8 — WebP, and the payload win that was actually there

Re-reading the same Lighthouse insight for what it says rather than what 6.5
assumed it said: of the homepage's 608 KB, **290 KB is attributed to "using a
modern image format (WebP, AVIF) or increasing this image's compression"**, not
to sizing at all. Four Slider Revolution PNGs account for 262 KB of it, and they
are small in dimensions and enormous in bytes — `slider-1-as6.png` is 200x379
and 84 KB.

That is a much better target than sizing, because it is independent of layout,
independent of DPR, and cannot move the LCP: the URL does not change and neither
does the markup. Only the bytes behind the URL change, and only for browsers
that asked for them.

**Implementation.** `tools/make-webp.php` writes `foo.png.webp` beside
`foo.png`, keeping the conversion only where it saves at least 15%. nginx picks
it up with a `map` on the request's `Accept` header and
`try_files $uri$webp_suffix $uri`, so the suffix is appended rather than
rewritten and the fallback is automatic for anything unconverted. `Vary: Accept`
is set, without which a shared cache could hand a WebP to a client that cannot
render it.

```
1041 files converted, 23 rejected as not worth it
26,234 KB -> 12,263 KB across those files, a 54% reduction
```

Verified in both directions on the worst offender:

```
Accept: image/webp  ->  Content-Type: image/webp,  10,114 bytes
Accept: image/*     ->  Content-Type: image/png,   84,127 bytes
```

Neither the file nor the markup that references it was modified, so this is
reversible by deleting the generated files and reverting two config blocks.

**Measured.** The payload reduction is large and the timing effect is not:

| Homepage | Before | After |
|---|---|---|
| **Images** | **1,508 KB** | **802 KB** |
| **Total transfer** | **2,378 KB** | **1,671 KB** |
| LCP (median of 3-4) | 9.57 s | 9.14 s — within spread |
| Score | 28 | 29 — within spread |

| Product page | Before | After |
|---|---|---|
| Images | 343 KB | 278 KB |
| Total transfer | 936 KB | 871 KB |
| LCP | 7.46 s | 7.13 s |

**Read that honestly: 707 KB — 30% of the homepage — stopped being transferred,
and the page did not get measurably faster on this machine.** That is not a
contradiction. The homepage's bottleneck is JavaScript execution, not image
bytes: TBT sits between 3.5 s and 8.7 s across runs. Removing bytes that were
never on the critical path does not move a metric gated on main-thread work.

It is still worth keeping, and the reason is not the Lighthouse score. This site
sells to customers in Kenya, many on metered mobile data. A third less data per
page view is a real improvement to them whether or not a lab metric on a
Windows workstation notices.

### A measurement that was wrong, and how it was caught

The first attempt to measure this produced a homepage that looked dramatically
better: FCP down 1.11 s, images down to 722 KB. It was wrong. All four runs had
measured a **276 KB homepage instead of the real 622 KB one** — an incomplete
render, missing every megamenu and the mobile header, that nginx had stored and
was serving as a `HIT`.

The cause is worth recording because it is a live production hazard rather than
a measurement quirk. The theme rebuilds its layout transients lazily. A render
that triggers that rebuild while the site is busy — six php-cgi workers, all of
them saturated by Lighthouse — can emit the page without its megamenus, and the
page cache will then keep that version for its full 24-hour TTL. **Flush the
theme cache in production and the first visitor's broken page becomes
everyone's page for a day.**

Two changes came out of it:

- `tools/flush-theme-caches.php` now empties the nginx cache itself, so the two
  can no longer be flushed out of step.
- `scripts/lh-summary.py` compares the HTML document size across runs and
  refuses to present the metrics as an improvement when it differs. It flags the
  original contaminated pair correctly.

The tell was that the document had halved. A page that gets dramatically faster
*and* dramatically smaller has usually lost content.

### 6.9 — the last of the CSS, and a layout shift that was still there

**Block editor stylesheets.** The site runs Classic Editor with WPBakery and
Elementor; a DOM count returns **0 elements** carrying a `wp-block-*` class,
while `wp-block-library` loads at 17 KB and render-blocking on every page.
`block-assets.php` drops it, gated on the rendered content actually containing
no block delimiter so the saving cannot outlive the assumption behind it.

Two things the first version got wrong, both caught by checking rather than
assuming:

- It kept the stylesheet on `/shop/` because `widget_block` contains blocks —
  but all five are WordPress's default widgets sitting in `wp_inactive_widgets`,
  where nothing renders them. The gate now only counts block widgets actually
  placed in a sidebar.
- It also tried to drop `wc-blocks-style`, which appeared to work and did
  nothing: WooCommerce enqueues that one from a `wc_get_template` filter during
  template rendering, long after `wp_enqueue_scripts`. Just as well — it is
  genuinely used, because WooCommerce renders its "added to cart" and validation
  notices with block-based templates on classic pages. Dropping it would have
  left the transactional flow's messaging unstyled to save 3 KB. It stays.

**The remaining layout shift, identified.** One product run in four still
recorded CLS 0.069 against a 0.002 median — above the brief's 0.05 target on its
own. The trace attributed it to
`body.wp-singular > div.qlwapp > div.qlwapp__container`: the **WhatsApp chat
widget**, not the header wrap that Phase 6.3 fixed.

`wp-whatsapp-chat` injects its container with JavaScript and only then adds the
corner modifier that carries `position: fixed`. In between, the container is a
static 430x88 flex box in normal flow near the end of `<body>`, so everything
above it moves. The base class simply has no `position` of its own.

One rule in `brand.css` gives the base class `position: fixed`. It cannot change
where the widget ends up — the modifier still supplies the corner offsets — it
only means the container is never in flow to begin with. Verified in the live
DOM afterwards: still fixed, still bottom-right, still 430x88.

**What is left in this phase, and why it is not what the brief expected.** The
"156 KB of unused CSS" recorded after 6.5 does not survive scrutiny. Lighthouse
measures coverage on a 412 px mobile run, so every desktop `@media
(min-width: ...)` rule counts as unused: 39% of `dynamic-styles-cached.css` and
14% of the theme stylesheet by raw bytes. `js_composer.min.css` reads **100%
unused** on mobile while containing the entire `.vc_col-sm-*` grid that desktop
layout depends on — dequeuing it on that evidence would break the site at
desktop widths. What was genuinely removable was the 17 KB of block CSS, and it
has been removed. Trimming the rest means generating a per-page CSS subset and
regenerating it whenever content changes, which is a different kind of project.

---

## Phase 8 — where this ended up

### Targets

| Target | Baseline | Final | |
|---|---|---|---|
| TTFB < 100 ms cached | ~1.9 s uncached | **20-24 ms, all HIT** | met |
| JS < 300 KB on product page | 445 KB | **253 KB** | met |
| CLS < 0.05 | 0.148, then 0.955 intermittent | **median 0.000, worst 0.069** | **not reliably met** |
| 0 uncached PHP per anonymous view | 8 | **2** (home was 4 until Phase 8) | not met |
| LCP < 2.5 s | 9.57 s home / 7.46 s product | **8.86 s / 6.59 s** | not met |
| Lighthouse mobile >= 90 | 28 home / 40 product | **36-59 / 58-64** | not met |

### What moved, and by how much

| Homepage | Before | After |
|---|---|---|
| Score | 28 | 36-59 (see the caveat below) |
| LCP | 9.57 s | 8.86 s |
| FCP | 3.93 s | 3.28 s |
| Speed Index | 9.89 s | 6.65 s |
| **TBT** | **9.60 s** | **2.21 s** |
| Images | 1,508 KB | **532 KB** |
| **Total transfer** | **2,378 KB** | **1,382 KB** |

| Product page | Before | After |
|---|---|---|
| Score | 40 | 58-64 |
| **LCP** | **7.46 s** | **6.59 s** |
| FCP | 3.98 s | 3.12 s |
| Speed Index | 6.07 s | 4.45 s |
| **TBT** | **1.10 s** | **420 ms** |
| Images | 343 KB | 272 KB |
| Total transfer | 936 KB | 848 KB |

The homepage HTML grew from 622 KB to 944 KB, and that is the change working
rather than a regression: five grids that used to be fetched over
`admin-ajax.php` after load are now baked into the cached response.

### The measurement caveat, stated plainly

**Run-to-run spread within one session badly understates the real variance on
this machine.** Two full measurement rounds, taken an hour apart with no change
affecting the homepage between them:

| Homepage score | Runs |
|---|---|
| Round A | 58, 59, 59, 59 |
| Round B | 33, 45, 32, 38 |

Each round is internally tight, so either one looks like a solid result on its
own. Between them the median moves 23 points. Any single session's numbers from
this environment should be read as a range, not a value, and that is why the
score row above gives one.

The deterministic figures -- bytes, request counts, TTFB, uncached PHP requests
-- do not behave this way and are the ones worth quoting.

### Uncached PHP per view

Phase 4 reported this as 2 and it was 2 on the product page. On the **homepage
it was 4**, and nobody had measured it there. Instrumenting `admin-ajax.php`
named them:

| Request | Status |
|---|---|
| `et_posts_ajax` | removed -- grid now inline |
| `woo_products_ajax` | removed -- grids now inline |
| `megamenu_load` | remains |
| `update_mini_cart_contents` | remains |

Each was taking ~2.5 s. The two that remain are the theme's sidebar category
flyouts and the cart badge; both need a different approach than a builder
setting, and neither blocks first paint.

### CLS: honest position

The median is 0.000 and the worst of four runs is 0.069. It is a single
intermittent shift of the **WhatsApp chat widget**, which `wp-whatsapp-chat`
injects with JavaScript. It fired in two runs of four before the product image
was promoted to `eager` and in one of four after, so that helped and did not
finish the job.

An earlier attempt to fix it with CSS was wrong and has been reverted: the
plugin already sets `position: fixed` with full offsets on the corner modifier,
and builds that modifier into the className at creation, so the added rule was
redundant. Four clean runs after it were the intermittent case not firing, not
evidence it worked. That is recorded in `brand.css` so nobody re-derives it.

Finishing this means either configuring the widget out, reserving its space, or
patching its JavaScript. It is a third-party widget shifting a page it does not
own, and it is the only thing standing between this site and the CLS target.

### The honest summary

**The server-side work is finished and its targets are met.** An anonymous page
view is a 20-24 ms cached HIT against a ~1.9 s PHP render, and it stays correct:
every session cookie and transactional path bypasses the cache.

**The payload work is finished too, and went further than the brief scoped.**
Total transfer is down 42% on the homepage and 9% on the product page, images
down 65% and 21%, with 1,041 files served as WebP under content negotiation
without a single URL or markup change.

**The biggest single win was not on the brief's list at all.** The homepage was
fetching its five main grids over `admin-ajax.php` after load -- roughly 10 s of
uncached PHP per view, sitting behind a page cache that was serving the HTML in
24 ms. Inlining them cut TBT from 9.60 s to 2.21 s. It was found only because
Phase 8 re-measured a number Phase 4 had recorded rather than trusting it.

**The client-side targets remain unmet and will not be met by tuning.** LCP is
6.59 s against 2.5 s and the score sits in the 40s to 50s. What is left is
structural: ~417 KB of JavaScript on a page built from WPBakery, Elementor and
Slider Revolution simultaneously. Reaching 2.5 s means changing what the page is
made of. That is a rebuild decision, and it is the recommendation to take
forward.

## Phase status at a glance

| Phase | State | Note |
|---|---|---|
| 1 — discovery and baseline | done | `DISCOVERY.md` |
| 2 — caching | done | 2A.1 and 2A.4 skipped, see below |
| 3 — Cloudflare | skipped | not possible here, see below |
| 4 — WooCommerce | done | target missed: 2 uncached requests remain, not 0 |
| 5 — instant navigation | done | rules verified; live activation could not be exercised here |
| 6 — payload | done | images and CSS addressed; what remains is structural, see below |
| 7 — database and background | done | |
| 8 — final verification | done | two of the brief's checks are impossible here, named below |

**Phase 6 is finished, but not the way the brief anticipated.** Responsive
images were tried twice (6.5, 6.7) and reverted twice, the second time with
measurements showing that a *correct* srcset implementation makes this site
heavier on a DPR 2.625 mobile profile than the theme's hardcoded sizes do. The
real image win was format, not sizing: 6.8 serves 1,041 files as WebP under
content negotiation, taking 65% off homepage image bytes without touching a
single URL or markup. The "156 KB of unused CSS" turned out to be largely a
mobile-viewport artifact; the 17 KB that was genuinely removable has been
removed.

**Three targets are met, three are not.** What blocks the remaining three is
structural — ~417 KB of JavaScript on a page built from WPBakery, Elementor and
Slider Revolution at once — and is a rebuild decision rather than a tuning one.

**Two caveats a reader should carry forward.** The Lighthouse *score* on this
machine varies by more than 20 points between measurement sessions even when the
site does not change, so scores are quoted as ranges and the deterministic
figures are the ones to trust. And CLS has an intermittent 0.069 shift from the
WhatsApp chat widget that is not fully fixed.

### Found during Phase 8, worth knowing

Re-measuring rather than trusting recorded numbers turned up three things:

- The homepage was making **4 uncached PHP requests per view, not 2** — five
  builder grids were being fetched over `admin-ajax.php` at ~2.5 s each, behind
  a page cache serving the HTML in 24 ms. Inlining them cut homepage TBT from
  9.60 s to 2.21 s, the largest single win of the whole engagement.
- **None of the tool-applied database changes were present**, because a
  database restore had silently undone them while every file-based change
  survived in git. See `PATCHES.md` §3.
- An **incomplete render can be promoted into the page cache** and served for
  24 hours. It happened here and four Lighthouse runs measured the wrong page.
  `tools/flush-theme-caches.php` and `scripts/lh-summary.py` were both changed
  so it cannot pass unnoticed again.

## Skipped permanently in this environment

| Phase | Why |
|---|---|
| **3 — Cloudflare, all of it** | `client1.local` is not publicly resolvable |
| **2A.1 purge endpoint** | `ngx_cache_purge` is not compiled into this nginx |
| **2A.4 Redis object cache** | no Redis server and no `php_redis` extension |
| **8 — edge/Nairobi TTFB** | no public hostname, no CDN |
| **8 — M-Pesa STK test** | no payment gateway plugin is installed |
| **6 — HTTP/2** (`modern-http-insight`, ~460 ms) | verified rather than assumed this time: Local *does* have a certificate for `client1.local` and `https://` responds 200, but its router negotiates **HTTP/1.1** in both cases. `curl -sk -o /dev/null -w '%{http_version}' https://client1.local/` returns `1.1`. Production behind any modern host or CDN would differ |

`wp db optimize` also could not complete: one table fails with
`Invalid default value for 'scheduled_date_gmt'`, a known MySQL 8 strict-mode
issue with a zero-date default. Reported rather than forced.
