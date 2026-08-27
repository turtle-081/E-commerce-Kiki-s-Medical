# PLAYBOOK — WordPress performance engagement

Hand this to Claude at the start of a new WordPress performance project, together
with the original brief. The brief says what to aim for. **This says what
actually happens when you try it**, and it exists because roughly a third of the
brief's assumptions did not survive contact with a real site.

Read `## The eleven things that cost us time` before starting. Everything else is
reference.

---

## How to use this document

1. Work the brief's phases in order. This document does not replace them.
2. At each phase, check the **Amendments** section here for that phase first.
3. Build the measurement harness in Phase 1 **before** changing anything. Every
   later decision depends on it, and it is the single highest-leverage thing in
   the engagement.
4. When a change measures worse or does nothing, **revert it and write down
   why.** A reverted change with a recorded reason is a deliverable. A change
   kept because it "should" help is a liability.

### The rule that matters most

> **Re-measure recorded numbers. Do not trust them, including your own.**

The largest single win in the reference engagement — a 77% cut in homepage
blocking time — was found because Phase 8 re-measured a figure Phase 4 had
recorded as "2 uncached PHP requests" and got 4. Nobody had measured it on the
homepage. Two of those requests were taking 2.5 s each, behind a page cache that
was serving the HTML in 24 ms.

---

## The eleven things that cost us time

Read these before writing any code. Each one was learned the expensive way.

### 1. Lighthouse scores vary more between sessions than within them

Two measurement rounds an hour apart, no change affecting the page between them:

```
Round A homepage scores:  58, 59, 59, 59
Round B homepage scores:  33, 45, 32, 38
```

Each round is internally tight. The medians are 23 points apart. **Within-session
spread badly understates real variance.** Quote scores as ranges, and base every
decision on deterministic figures instead: transferred bytes, request counts,
TTFB, uncached PHP requests. Those do not behave this way.

### 2. An incomplete render can get promoted into the page cache

The theme rebuilds layout fragments (headers, menus) lazily into transients. A
render that triggers that rebuild while the server is busy can emit the page
*without* them — and nginx will store that and serve it as a `HIT` for the full
24-hour TTL.

It happened, and four Lighthouse runs measured a homepage that was **276 KB
instead of 622 KB**, missing every megamenu. Every metric looked like a dramatic
improvement.

**Defence:** compare the HTML document size across runs before reading any
metric. A page that got dramatically faster *and* dramatically smaller lost
content. Build this check into the summary tool (see Phase 1). Also make the
theme-cache flush script empty the page cache itself, so the two can never be
flushed out of step.

### 3. Responsive images can make a site heavier, not lighter

Lighthouse's "image is larger than it needs to be (1000×1000) for its displayed
dimensions (285×285)" is computed in **CSS pixels**. Its mobile profile is 412 px
wide at **DPR 2.625**. A *correct* `srcset` implementation therefore asks for
~2.6× more pixels than a theme's hardcoded size does.

Measured: restoring core's responsive images upgraded the main product image
from `600×600` (16 KB) to `768×768` (22 KB) in every run. The theme's crude fixed
sizes were already below what proper responsive images would request.

**Before implementing responsive images, check what the theme currently
requests.** If it hardcodes a size at or below the display size × 1, srcset will
cost you bytes. The win is **format** (WebP/AVIF), not sizing.

### 4. `sizes="auto"` needs a laid-out element, and carousels have none

`sizes="auto"` resolves against the element's rendered width. In the reference
site, **12 of 13** images that gained a srcset had a layout width of **zero** at
the moment the browser picks a candidate, because they sit in carousels that
have not been laid out yet.

**Defence:** check `img.currentSrc` and `getBoundingClientRect().width` in the
live DOM before assuming any srcset is being honoured.

### 5. "Unused CSS" is measured at one viewport and overstates the win

Lighthouse coverage runs at 412 px, so every desktop `@media (min-width: …)`
rule counts as unused. In the reference site that was 39% of one stylesheet and
14% of another by raw bytes. One file read **100% unused** on mobile while
containing the entire `.vc_col-sm-*` grid that desktop layout depends on.

**Dequeuing on that evidence breaks the site at desktop widths.** What is
genuinely removable is usually much smaller — here, 17 KB of block-editor CSS on
a site that renders zero blocks.

### 6. Content fetched over AJAX behind a page cache is backwards

This was the biggest win and it was not on the brief.

Page builders often set grids and widgets to load their contents over
`admin-ajax.php` after render. That is a sensible default with no page cache: it
keeps the initial response small. **With a full-page cache in front it is exactly
inverted** — the HTML serves in 24 ms and the browser then goes back to the
origin four more times for the parts that actually matter.

Measured on the reference homepage: five such elements, ~2.5 s each.

| | Before | After inlining |
|---|---|---|
| HTML | 622 KB | 944 KB |
| **TBT** | **9.60 s** | **2.21 s** |
| Uncached PHP per view | 4 | 2 |

The larger HTML is the change working — it is baked into the cached response and
costs nothing per view.

**How to find it:** instrument `admin-ajax.php` and load the page.

```php
// mu-plugins/zz-ajax-diag.php — TEMPORARY, delete after use
<?php
defined( 'ABSPATH' ) || exit;
if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
    error_log( 'AJAX action=' . ( $_REQUEST['action'] ?? '(none)' )
        . ' ref=' . ( $_SERVER['HTTP_REFERER'] ?? '?' ) );
}
```

Then flip the offending builder elements to render inline. In the reference
theme those carried `ajax="true"` in the shortcode; look for the equivalent
attribute or element setting and change it with a reversible tool script.

### 7. Database changes live only in the database, and a restore undoes them

Everything that edits post content or post meta — inlining a footer, changing a
builder setting — exists **only in the database**. Git cannot reproduce it. A
database restore silently undoes it while every file-based change survives, so
the tree looks correct and the site behaves differently.

This happened. Phase 8 found the front page still loading grids over AJAX and no
backup meta on any post, meaning **none** of the tool-applied changes were
present, though the report described them as done.

**Defence:** every content tool must be idempotent so re-running is free, and the
handover docs must carry a re-apply loop plus a one-line verification:

```bash
curl -s "$SITE_URL/" | wc -c     # expect the post-change size, not the pre-change one
```

### 8. Deferring scripts changes whether hydration runs at all

Not just when. Deferring scripts broke a footer that was being painted by an
AJAX call — the trigger never fired and the footer stayed blank.

**Anything on the site that paints via JavaScript needs re-checking after any
change to script loading**: sliders, megamenus, mini-carts, product grids,
tabs. Bisect by temporarily renaming the script-loading module.

The right fix is usually to render the thing inline rather than to stop
deferring — see trap 6.

### 9. JavaScript-built markup is invisible in the served HTML

Slider Revolution 6.7 builds its markup client-side. Parsing the served HTML for
slider elements reports **zero sliders on every page, including the one that has
a slider**. Dequeuing its assets on that evidence would have looked perfectly
safe and silently broken the homepage.

**Detect by the thing that drives the render** — the shortcode in post content —
and verify against the live DOM, never the source.

### 10. Removing a loading attribute is not the same as setting one

Core runs a pass that fills in missing loading attributes. `remove_attribute(
'loading' )` therefore achieves nothing: core puts `lazy` straight back. Setting
`loading="eager"` explicitly is what core treats as already decided.

Related: setting `fetchpriority` yourself *and* letting core add it produces the
attribute **twice** in the markup. Set `loading="eager"` and let core supply
`fetchpriority`.

Also: `wp_get_attachment_image_attributes` is not the last word — a later pass
can override it. `wp_get_attachment_image` receives the assembled tag and is.

### 11. Vendor kill-switches can often be lifted without touching vendor code

The reference theme disabled responsive images site-wide:

```php
add_action( 'init', 'enovathemes_addons_disable_responsive_images' );
```

Because that is a **named function on a hook**, it lifts with one line from a
mu-plugin — no vendor edit, no patch to lose on update:

```php
add_action( 'plugins_loaded', function () {
    remove_action( 'init', 'enovathemes_addons_disable_responsive_images' );
}, 0 );
```

`plugins_loaded` is the right moment: the plugin file has run, `init` has not
fired. **Always check whether a vendor behaviour is registered as a named
callback before concluding it needs a patch.**

---

## Phase 0 — intake

Fill in the brief's block, and add these:

```
DPR_PROFILE       = what DPR the target audience actually uses (affects trap 3)
THEME_TYPE        = classic | block | page-builder (which builders, how many)
BUILDER_COUNT     = how many page builders are active simultaneously
JS_LAZY_LOADER    = does the theme ship its own JS lazy loader?
DB_RESTORE_RISK   = is this environment likely to be restored from a dump?
```

**If two or more page builders are active on the same page, say so in Discovery
immediately.** A 400–600 KB JavaScript baseline from the builders is not fixable
by tuning, and it will be what stops you reaching the Lighthouse and LCP targets.
Flag it in Phase 1, not Phase 8.

---

## Phase 1 — discovery and the measurement harness

Do the brief's discovery. Then build these three tools before touching anything.
They pay for themselves within the first phase.

### `scripts/measure.sh` — TTFB and cache state

Two amendments to the brief's version:

- **Use `GET` with `-D -`, not `curl -I`.** A `HEAD` is not a `GET`, so
  cache-bypass rules skip it by design and the cache always looks broken.
- **Discard a warm-up request per path**, so run 1 is not measuring a cold
  opcache rebuild while runs 2–3 measure a warm one.

### `scripts/lh.sh` — Lighthouse runner

```bash
#!/usr/bin/env bash
# Usage: ./scripts/lh.sh <label> [runs] [page ...]
set -u
LABEL="${1:?usage: lh.sh <label> [runs] [page ...]}"
RUNS="${2:-3}"
shift 2 2>/dev/null || shift 1
BASE="$SITE_URL"

page_url() {
  case "$1" in
    home)    echo "$BASE/" ;;
    shop)    echo "$BASE/shop/" ;;
    product) echo "$BASE/product/<slug>/" ;;
    *)       echo "" ;;
  esac
}

PAGES=("$@"); [ "${#PAGES[@]}" -eq 0 ] && PAGES=(home product)
mkdir -p reports
for page in "${PAGES[@]}"; do
  url="$(page_url "$page")"
  [ -z "$url" ] && { echo "unknown page $page" >&2; exit 2; }
  for i in $(seq 1 "$RUNS"); do
    out="reports/${LABEL}-${page}-${i}.json"
    printf '%s run %s/%s -> %s\n' "$page" "$i" "$RUNS" "$out"
    npx --yes lighthouse "$url" --preset=perf --form-factor=mobile \
      --throttling-method=simulate --screenEmulation.mobile \
      --chrome-flags="--headless=new --no-sandbox --disable-gpu" \
      --quiet --output=json --output-path="./$out" 2>/dev/null
  done
done
```

**Never quote a single Lighthouse run.** Minimum three; four is better.

### `scripts/lh-summary.py` — medians, spread, and the contamination guard

The summary tool must do three things the raw reports do not:

1. **Report medians, not means.** One pathological run (TBT came back at 20 s on
   a page whose median is 1 s) drags a mean somewhere meaningless.
2. **Print the spread beside every median**, and mark a delta smaller than the
   spread as `noise` rather than an improvement.
3. **Compare the HTML document size across labels and warn when it moved.** This
   is the trap-2 guard and it is not optional.

```python
def document_size(report):
    for item in report["audits"]["network-requests"]["details"]["items"]:
        if item.get("resourceType") == "Document":
            return item.get("resourceSize") or 0
    return 0

# then, across labels:
#   if max(sizes) > min(sizes) * 1.2:
#       warn("document size differs between labels — the page changed, "
#            "do not read the metrics as an improvement")
```

Pull byte totals from `network-requests` rather than `resource-summary`; newer
Lighthouse versions rename and remove audits, and the network log is stable.

### `tools/check-image-attrs.py` — the loading-attribute guard

Snapshots every `<img>`'s `loading`, `fetchpriority` and `decoding` across three
pages and diffs two snapshots. **Direction is what matters**: making an image
load *later* is the failure mode; making a known image load *sooner* cannot
delay anything and should be reported as a promotion, not an error.

Run it before and after anything that touches image markup. It is what makes an
image experiment cheap to evaluate and safe to keep.

### Also capture in Discovery

- The **top ten heaviest requests** — this is what Phase 6 targets.
- Whether Lighthouse's newer **`*-insight` audits** are present. Recent versions
  replaced `uses-responsive-images` / `render-blocking-resources` with
  `image-delivery-insight`, `render-blocking-insight`, `lcp-discovery-insight`
  and others. Old audit keys silently return nothing.
- **Read the insight's stated reason, not just its number.** Ours attributed
  290 KB of 608 KB to *"using a modern image format"* and the rest to sizing.
  Those need completely different fixes, and only one of them worked.

---

## Phase 2 — full-page cache

Follow the brief. Amendments:

- **On Windows / Local by Flywheel there is no PHP-FPM.** Local runs a TCP pool
  of `php-cgi.exe` processes, one per port, and that count *is* the site's
  concurrency limit. It lives in the host's own config, not the repo. Ours was
  2 on a 12-core machine while the theme fired 6–8 requests per page view;
  raising it to 6 changed `loadEventEnd` from 62.6 s to 8.9 s. **Check this
  before diagnosing anything else as slow.**
- **If the host regenerates its config from templates** (Local uses `.hbs`),
  editing the runtime file gets a change that vanishes on restart, and editing
  only the template gets a change that does nothing until restart. Edit the
  template, mirror into the runtime file, reload. Document which is which.
- `ngx_cache_purge` is often not compiled in. Fall back to deleting cache files
  from a mu-plugin; say so in the report rather than substituting silently.
- **Verify the bypass matrix explicitly.** Ours caught a real bug where
  logged-in users would have been served anonymous HTML. Test every cookie and
  every transactional path, and re-test after every later change.

---

## Phase 3 — Cloudflare

No amendments. If the site is not publicly resolvable, skip the whole phase and
say so — do not substitute a local equivalent.

---

## Phase 4 — WooCommerce

Follow the brief. Amendments:

- **Measure uncached PHP requests on every page type, not one.** The brief's
  target is per-view; the homepage and the product page can differ by 2×. See
  trap 6.
- **A cart-count script attached to `jquery-core` will pin jQuery as
  render-blocking.** Core refuses to defer any handle carrying an `after`
  inline script, and that propagates to everything depending on it. Give the
  script its own `src`-less handle — it costs no request.
- Scripts enqueued *during* body render (late widgets) are never marked by a
  `wp_enqueue_scripts` pass. Add a second pass on `wp_print_footer_scripts` to
  catch them, or one unmarked dependent keeps jQuery blocking.

---

## Phase 5 — instant navigation

No amendments beyond the brief's own warning that prerender is worthless without
Phase 2. Note in the report that live activation cannot be exercised in a local
environment — verify the rules are emitted and correct, and say that is what you
verified.

---

## Phase 6 — payload

This is the phase the brief gets most wrong, because it assumes the wins are
where the audits point.

**Work in this order:**

1. **Scope oversized libraries to the pages that use them.** Ours loaded a
   slider bundle (167 KB) on every page including `/cart/`; exactly one page
   used it. This was the largest deterministic win of the phase. Detect by the
   shortcode in content, not by parsing HTML (trap 9).
2. **Fix layout shift before reading any score.** A bimodal CLS makes the score
   swing 40 points between identical runs and makes every other comparison
   meaningless. Get the raw trace — Lighthouse's own `layout-shifts` audit
   reported one 0.955 event with no node attached.
3. **Static asset lifetimes.** Versioned URLs (`?ver=`, `filemtime()`) can take
   `public, max-age=31536000, immutable`. Drop `expires` if you add
   `add_header` — together they emit two `Cache-Control` headers.
4. **Render-blocking JavaScript**, with trap 8 in mind.
5. **Images — format first, sizing probably never.** See traps 3 and 4.
6. **CSS — measure what is genuinely removable.** See trap 5.

### The image work that actually pays

Generate WebP siblings and serve them by content negotiation. **No URL changes,
no markup changes**, so it cannot move the LCP element and cannot interact with
any lazy loader:

```nginx
# http {} block
map $http_accept $webp_suffix {
    default        "";
    "~*image/webp" ".webp";
}

# server {} — must precede the general image location
location ~* \.(?:jpe?g|png)$ {
    add_header  Cache-Control "public, max-age=31536000, immutable";
    add_header  Vary Accept;
    try_files   $uri$webp_suffix $uri =404;
}
```

Write `foo.png.webp` beside `foo.png` — appending the suffix rather than
replacing the extension is what lets `try_files` find it with no rewriting.

A converter script should: keep the conversion **only if it saves ≥15%** (many
JPEGs do not), skip files below ~8 KB, be idempotent, and support `--revert` and
`--dry-run`. Ours converted 1,041 files, 26.2 MB → 12.3 MB, with PNGs saving
79–88% and photographic JPEGs correctly rejected.

`Vary: Accept` is required. Without it a shared cache can hand a WebP to a
client that cannot render it.

**New uploads are not converted automatically.** nginx falls through to the
original so nothing breaks — re-run the tool after a bulk import.

---

## Phase 7 — database and background work

Follow the brief. One addition worth checking early:

**Transients created with an expiry of `0` are stored as autoloaded options.**
Some plugins create every cache that way, which loads megabytes on every
request, admin and AJAX included. Ours carried **2,241 KB** of autoloaded
options, 93% of it transients.

If the plugin exposes a filter for the expiry, returning a real TTL fixes it
with no vendor edit and no behavioural change — those caches are already purged
explicitly when their content changes, so the TTL is only a backstop. Result:
2,241 KB → 153 KB.

Verify it is **durable, not a one-time sweep**: delete the transients, reload
the pages that rebuild them, and confirm the regenerated ones come back
`autoload=off` with a real timeout row.

---

## Phase 8 — verification

The brief treats this as reporting. **Treat it as an audit**, and budget real
time for it. In the reference engagement it produced the largest single win and
three process corrections.

Do all of this:

1. **Re-measure every number the earlier phases recorded**, on every page type.
   This is where trap 6 was found.
2. **Verify the database-side changes are actually present** (trap 7). Check for
   the backup meta your tools write.
3. **Run the full functional checklist from the brief.** Add: fresh incognito
   window, and a check that no page lost content (trap 2).
4. **State the measurement caveat explicitly** with the two-round comparison
   from trap 1. A reader who takes one session's score as fact will make bad
   decisions later.
5. **Name what is not met and why**, separating "not done" from "not reachable
   in this environment" from "not reachable without a rebuild".

---

## Conventions worth copying

### Tool scripts

Every script that changes database content:

- **backs up what it is about to change into post meta**, so it is reversible
  without a database restore;
- supports `--revert` and `--dry-run`;
- is **idempotent** — re-running must not clobber a real original with an
  already-modified copy;
- is **scoped to specific shortcode tags or handles**, never a blind
  `str_replace` across post content.

### The mu-plugin layout

```
wp-content/mu-plugins/
  safi-performance.php          loader only — WP auto-loads top-level files
                                only, so this globs the subdirectory
  safi-performance/
    cache-purge.php             one concern per file
    woo-fragments.php           delete one file to disable exactly that change
    script-loading.php          and nothing else breaks
    ...
```

Each module opens with a comment block stating **what it does, why, what it
deliberately does not do, and what to delete to revert.** When a change is
reverted, keep the explanation — the reasoning is the deliverable.

### Documentation set

| File | Answers |
|---|---|
| `DISCOVERY.md` | what the environment is, plugin audit, baseline |
| `REPORT.md` | what changed and why, per phase, including what was reverted |
| `ROLLBACK.md` | how to undo each phase independently |
| `PATCHES.md` | vendor edits that an update will silently revert, plus settings that live outside git |
| `PLATFORM.md` | the map: stack, layout, gotchas — for whoever picks this up next |

`PATCHES.md` needs a section for **state that git cannot reproduce**: host
config outside the repo, and every database change made by a tool (trap 7).

---

## Honest ceilings — put these in the report

- **Cart, checkout and account always run PHP.** With OPcache and an object
  cache that is 200–400 ms, not 30 ms. That is the ceiling for WooCommerce
  without going headless.
- **The first visitor after a purge pays full PHP cost.**
  `fastcgi_cache_background_update` makes that one slow request per page per
  day, not one per visitor.
- **Prerender is Chromium-only.** Safari and Firefox get hover-prefetch.
- **A multi-builder theme has a floor you cannot tune past.** Ours shipped
  ~417 KB of JavaScript from WPBakery + Elementor + Slider Revolution on one
  page. Server-side work took an anonymous view from ~1.9 s to 24 ms; the
  client-side targets stayed out of reach because the remaining cost is
  execution, not transfer. **Say this in Discovery, not in the final report.**

### What the reference engagement actually achieved

| | Homepage | Product page |
|---|---|---|
| Lighthouse score | 28 → 36–59 | 40 → 58–64 |
| LCP | 9.57 s → 8.86 s | 7.46 s → 6.59 s |
| **TBT** | **9.60 s → 2.21 s** | **1.10 s → 420 ms** |
| **Total transfer** | **2,378 → 1,382 KB** | **936 → 848 KB** |
| TTFB cached | ~1.9 s → **24 ms** | ~1.8 s → **22 ms** |

Three of six targets met: TTFB, JavaScript budget, and CLS by median. LCP,
Lighthouse ≥ 90 and zero-uncached-PHP were not met, for reasons that are
structural and are named above.

**The server-side work met its targets completely. The client-side targets were
not reachable on that stack, and the honest recommendation was a rebuild, not
more tuning.** Expect the same shape of result on any heavy multi-builder
WooCommerce site.
