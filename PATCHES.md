# Local patches

Changes made to code that this repo does not own. **A plugin or theme update
will silently revert everything in section 1** — nothing warns you, the symptom
just comes back. Check this file after any update.

Section 2 lists workarounds that live in our own child theme. Updates will not
touch them, but they should be *removed* once the upstream bug they work around
is fixed, or they will start fighting the corrected code.

Section 3 covers configuration that is real but lives in gitignored or
out-of-repo files, so a fresh clone will not have it.

Section 4 documents the client's brand palette — what it is, why the interactive
green differs from the brand green, and what remains to be migrated.

Versions below are the ones the patches were written against. If an update
brings a newer version, re-read the upstream code before reapplying — the bug
may be gone, or the surrounding code may have moved.

---

## 1. Patches to third-party code (lost on update)

### 1.1 `disco` 1.3.53 — PHP 8.2 mbstring deprecation

**File:** `app/public/wp-content/plugins/disco/vendor/inpsyde/assets/src/OutputFilter/AttributesOutputFilter.php` (~line 56)

`mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8')` is deprecated in PHP 8.2
("Handling HTML entities via mbstring is deprecated"). Replaced with the
documented equivalent:

```php
mb_encode_numericentity($html, [0x80, 0x10FFFF, 0, ~0], 'UTF-8')
```

Verified equivalent on PHP 8.2: both produce byte-identical parsed output,
including multibyte characters; only the old form emits the deprecation.

Note this is inside `vendor/`, so it is also lost if the plugin's Composer
dependencies are reinstalled, not only on a plugin update.

**Introduced in:** `a31641c3`

### 1.2 `enovathemes-addons` 3.1 — unguarded `WPBMap` calls

**File:** `app/public/wp-content/plugins/enovathemes-addons/enovathemes-addons.php`
**Lines:** 511, 689, 3723, 6799, 6831, 6858 (6 sites)

Each call site changed from:

```php
WPBMap::addAllMappedShortcodes();
```

to:

```php
if (class_exists('WPBMap')) { WPBMap::addAllMappedShortcodes(); }
```

`WPBMap` is defined by `js_composer`, which requires it unconditionally in
`Vc_Manager`'s constructor — so it exists whenever that plugin is loaded and is
simply absent when it is not. All six sites are on AJAX / `template_redirect`
paths that run on ordinary page views (the site runs in `cache-queries` mode, so
`/ajax-api/footer-query`, `megamenu-query` and `mobile-query` are hit on every
view). Unguarded, those endpoints returned a 500 whenever `js_composer` was
inactive — the "Class WPBMap not found" fatals logged 10–14 Aug 2026.

Verified by filtering `js_composer` out of `active_plugins` for a single
request: unguarded → HTTP 500; guarded → HTTP 200.

**Introduced in:** `3503b04b`

### 1.3 `enovathemes-addons` 3.1 — dynamic CSS rewritten on every request

**File:** `app/public/wp-content/plugins/enovathemes-addons/includes/dynamic-styles.php` (~line 1264)

`enovathemes_addons_include_dynamic_styles_cached()` caches the generated CSS in a
transient, but the `file_put_contents()` that mirrors it to
`themes/propharm/css/dynamic-styles-cached.css` sat *outside* that cache branch. So
a ~270 KB file was rewritten on every single page view even on a cache hit, and
concurrent requests raced on the same handle — which Windows reports as:

```
file_put_contents(...): Failed to open stream: Permission denied
```

Now guarded by a content comparison, plus an exclusive lock on any real write:

```php
if (md5_file($file) !== md5($dynamic_css)) {
    file_put_contents($file, $dynamic_css, LOCK_EX);
}
```

The `wp_enqueue_style()` call stays unconditional — the stylesheet is needed on
every request regardless. Hashing 270 KB costs well under a millisecond against
writing 270 KB.

Verified both directions: no write across 6 varied page loads, no write across 8
concurrent requests, zero new log entries — and the file still self-heals, with a
deliberately corrupted copy restored to the correct content by a single request.

**Introduced in:** `8987df14`

---

## 2. Workarounds in our own child theme (safe from updates)

These live in `app/public/wp-content/themes/propharm-child/` and each carries a
full explanation of the upstream bug in a comment above it. Remove the
workaround when the corresponding upstream fix lands.

| Workaround | Works around | Remove when |
|---|---|---|
| `propharm_child_fix_revslider_sr7()` | revslider 6.7.54 never enqueues `public/js/migration.js` (its `$v6_slider` flag is set after `wp_enqueue_scripts` has fired); `tp-tools`/`sr7` are both `async` with no declared dependency despite `sr7.js` needing `_tpt` | RevSlider fixes the enqueue gate or ships the sliders in v7 tables |
| `propharm_child_fix_image_sizes_filter()` | enovathemes-addons 3.1 hooks `__return_empty_array` onto `wp_calculate_image_sizes`, which filters a *string* → "Array to string conversion" | enovathemes-addons uses `__return_empty_string` |
| `propharm_child_fix_add_to_cart_url()` | `add_query_arg()`'s `REQUEST_URI` fallback produces protocol-relative `//ajax-api/...?add-to-cart=N` hrefs during the theme's own AJAX endpoint requests | the theme passes an explicit base URL |
| `.summary .yay-currency-single-page-switcher` rule in `style.css` | propharm 3.1 declares `height:100%` on the switcher unscoped, so it fills the whole product summary column | the parent theme scopes that rule |

### A note on the `cache-queries` theme option

Theme options → "Cache custom queries?" is **on**, though the theme's own default
is off. It makes each page render as a shell and fetch megamenus, footer, mobile
header, products and posts through separate `/ajax-api/` requests, each paying a
full WordPress bootstrap (~2 s here, of which ~700 ms is
`propharm_enovathemes_integrateVC` re-registering all 102 WPBakery shortcodes).

Turning it off was measured and is **worse** on this setup: median went from
~4.4 s to ~14 s with intermittent multi-minute hangs. With it on, those requests
run concurrently and the page shell paints immediately; with it off everything
serialises into one request and nothing renders until all of it finishes. It is
left **on**.

That measurement was taken while PHP concurrency was still 2. Now that it is 6
(see above), the trade-off has shifted in favour of leaving it on even more
clearly — the parallel requests it fires can finally run in parallel. Re-test
before changing it.

---

## 3. Settings that live outside git

These are real configuration, but `.gitignore` excludes the files they live in, so
a fresh clone or a restore on another machine will not have them. Reapply by hand.

### The database changes are in this category too, and that bit us

Everything in `tools/` that edits post content or post meta lives **only in the
database**. The repository cannot reproduce it, and a database restore silently
undoes it while every file-based change survives in git — so the tree looks
correct and the site behaves differently.

That is not hypothetical. Phase 8 verification found the front page still
loading four product grids and a post grid over `admin-ajax.php`, and
`_safi_*` backup meta absent from every post, meaning **none of the
tool-applied changes were present in the current database** even though
`REPORT.md` described them as done. The branch is called
`restore/local-environment`; a restore is exactly what happened.

**After any database restore, re-run the content tools and verify.** They are
all idempotent, so running one that is already applied is free:

```bash
PHP="/c/Users/Turtle/AppData/Roaming/Local/lightning-services/php-8.2.29+0/bin/win64/php.exe"
INI="/c/Users/Turtle/AppData/Roaming/Local/run/cPNju-zlO/conf/php"
for t in inline-header-megamenu inline-mobile-header inline-footer inline-grid-ajax; do
    "$PHP" -c "$INI" "tools/$t.php"
done
"$PHP" -c "$INI" tools/flush-theme-caches.php
```

The cheap check that they are actually in effect — the front page should be
~944 KB of HTML, not ~622 KB, because the grids are baked in:

```bash
curl -s http://client1.local/ | wc -c
```

### `app/public/wp-config.php` (gitignored)

```php
define( 'DISABLE_WP_CRON', true );
```

Without it, every page view spawns `wp-cron.php` — a second full WordPress
bootstrap costing roughly 2.4 s on this machine, on top of the page's own
render. Nothing runs scheduled tasks while it is set, so run them manually when
needed (WooCommerce Action Scheduler, scheduled posts):

```bash
wp cron event run --due-now
```

### PHP concurrency — `%APPDATA%\Local\sites.json` (outside the repo entirely)

PHP-FPM does **not** run on Windows — `conf/php/php-fpm.d/www.conf.hbs` says so in
its first line, so its `pm.max_children = 2` is a red herring and changing it does
nothing. Local instead starts one `php-cgi.exe` per port listed here:

```
sites.json -> cPNju-zlO.services.php.ports.cgi
```

Each `php-cgi` handles exactly one request at a time (no forking on Windows), and
nginx round-robins across them via the `upstream php` block in its generated
`site.conf`. So that array *is* the concurrency limit.

It was `[10002, 10003]` — two concurrent PHP requests on a 12-core machine, while
the theme fires 6–8 per page view. Combined with `max_execution_time = 1200`, a
request the browser had already abandoned kept a worker busy for up to 20 minutes,
so every other request queued until the client gave up. Raised to:

```
[10002, 10003, 10006, 10007, 10008, 10009]
```

Measured effect on the shop page: `loadEventEnd` 62.6 s → 8.9 s, worst-case image
queueing 20,150 ms → 545 ms, and sequential page loads went from 2 of 4 timing out
to 6 of 6 succeeding.

Takes effect when Local restarts the site. If the edit does not stick, Local has
overwritten it from memory — quit Local completely, re-apply, then relaunch.

### Windows Defender exclusion (machine-level, needs an elevated shell)

Real-time scanning inspects every PHP file WordPress opens, and a single request
opens thousands. Excluding the sites directory is usually the largest single win
for Local on Windows:

```powershell
Add-MpPreference -ExclusionPath "C:\Users\Turtle\Local Sites"
```

---

## Checking whether a patch survived an update

```bash
bash tools/check-patches.sh
```

It reports each patch as `ok` or `REVERTED` and exits non-zero if any is
missing, so it also works as a post-update gate. It ignores comment lines —
both patches are described in comments that name the very functions being
checked, which a naive `grep -c` miscounts.

Or inline, from the repo root:

```bash
grep -n "WPBMap::" app/public/wp-content/plugins/enovathemes-addons/enovathemes-addons.php | grep -v class_exists
```

Expected: no output. Any line printed is an unguarded call.

```bash
grep -c 'mb_encode_numericentity($html' app/public/wp-content/plugins/disco/vendor/inpsyde/assets/src/OutputFilter/AttributesOutputFilter.php
```

Expected: `1`. `0` means an update reverted the patch.

`git diff` against the commits listed above will show exactly what to restore.

---

## 4. Client brand palette

Client colours: green `#80AF40`, red `#DC2222`, white `#FFFFFF`.

`#80AF40` is **2.58:1 on white** — it fails WCAG AA for text (4.5:1) and even the
3:1 minimum for UI components. White text on it fails identically. It is an
identity colour, not an interface colour, so the palette splits those roles:

| Token | Value | Role | Contrast |
|---|---|---|---|
| `--brand-green` | `#80AF40` | Identity: fills, borders, icons | 2.58:1 — large/decorative only |
| `--brand-green-dark` | `#5A7B2D` | Interactive: links, text, buttons | white on it 4.88:1, AA |
| `--brand-green-tint` | `#F5F9F0` | Section backgrounds | body text on it 4.44:1 |
| `--brand-red` | `#DC2222` | Sale badges, discounts | 4.89:1, AA |
| `--brand-red-dark` | `#C61F1F` | Red hover | 5.81:1, AA |

Both greens are mixes of the client's own green, so they read as one family.
Body copy stays `#56778f` (4.74:1) and headings `#184363` (10.39:1) — recolouring
text to brand green is the usual way this goes wrong.

**Applied in two places:**

1. **Theme options** (a serialised `wp_options` row, so git cannot track it) —
   `main-color`, `area-color`, `sale-color`, `discount-color`, `form-button-back`.
   Reproduce with `php tools/apply-brand-palette.php apply`; roll back with
   `restore`, which uses `tools/brand-palette-backup.txt`.

2. **`propharm-child/assets/css/brand.css`** — tokens, plus overrides for the three
   generated rules that paint text in the failing green (97 selectors in total).
   It must load *after* `propharm/css/dynamic-styles-cached.css`, which is enqueued
   at priority 20 and so lands after the child `style.css`; one of those rules is
   `!important` upstream, so specificity alone cannot win. The child theme declares
   it as a dependency and enqueues at priority 30 to force document order.

**Still outstanding — colours baked into page content.** The demo content sets
colours per page-builder module, stored in `wp_posts.post_content`, not in settings:

| Colour | Posts | Occurrences |
|---|---|---|
| `#15a9e3` | 51 | 521 |
| `#39cb74` | 20 | 194 |
| `#edf4f6` | 23 | 105 |
| `#f2971f` | 20 | 84 |

Plus 172 `wp_postmeta` rows. Spread across pages, megamenus, posts, banners,
headers and footers. Until those are remapped the site keeps rendering the old
blue in module-level styling (`#et-image-518409 .curtain{background-color:#15a9e3}`
and similar). That is a content migration, not a settings change.

### 4.1 Content pass — colours baked into page content

The demo set colours per page-builder module, so the theme options could not
reach them. `tools/remap-content-colours.php` rewrites those:
**1,525 occurrences across 182 rows** in `wp_posts.post_content` and
`wp_postmeta.meta_value`. Run it with no argument for a dry run; `apply` to write.

The mapping is context-aware, not a find/replace, because the same old colour
meant different things in different places:

| Old | Context | New |
|---|---|---|
| `#15a9e3`, `#39cb74` | background / fill area | `#80AF40` |
| `#15a9e3`, `#39cb74` | text, icons, borders, strokes | `#5A7B2D` |
| `#f2971f` | any (CTA orange) | `#5A7B2D`, hover `#4D6926` |
| `#edf4f6` | any (pale tint) | `#F5F9F0` |

Two decisions worth knowing:

**`#39cb74` did not become red.** It is the theme's `sale-color` *option*, but in
page content it paints `et_icon_list` ticks (38), `et_icon_box` (37), `et_button`
(30) and `et_progress` bars (9) on the About page, product descriptions and posts.
Recolouring those red would have rendered ~138 feature ticks as error icons.
Red-for-sale is carried by the `sale-color` option instead.

**Hover states map one step darker** than their base. A flat replace would have
collapsed some base/hover pairs into a single colour and made the hover invisible.

**Deliberately left alone — product colour swatches.** Three `wp_termmeta` rows
hold `color` meta for `pa_color` terms named "Blue", "Green" and "Orange". Those
are variation swatches describing the products themselves, not branding. A swatch
labelled "Blue" must not render brand green.

### 4.2 Caches that hide the change

Both the generated CSS and the theme's content fragments are cached in transients
stored with **no expiry**, so edits appear to do nothing until they are cleared:

- `_transient_dynamic-styles-cached` — the generated stylesheet
- `_transient_enovathemes-megamenu` / `-headers` / `-footers` / `-banners` /
  `-icons` / `-s-icons` and friends — page-builder HTML, stored gzcompressed, so
  a plain SQL search will not even find colours inside them
- `_transient_et_icon_*` — one per inlined SVG, including the logo

Clearing the enovathemes and et_icon transients (41 rows here) makes them rebuild
from the corrected content.

### 4.3 Parent-theme hardcoded colours

Seven rules in `propharm/style.css` hardcode the demo palette and are not driven
by any option: desktop and mobile menu hover, the mobile toggle icon, Mailchimp
success text, post-navigation arrows, the audio playlist progress bar, and star
ratings. Overridden at the end of `brand.css` rather than by editing the parent,
which an update would overwrite.

Star ratings are now brand green (they were `#f2971f`). Amber stars are a strong
e-commerce convention and the brief supplied no amber, so that one declaration is
worth confirming with the client — it is a single line to revert.

### 4.4 The logo

`uploads/logo.svg` is a two-colour mark: navy `#184363` plus one accent that was
the demo blue. The accent is now `#80AF40`; the original is kept at
`tools/logo-original-demo.svg`. `uploads/` is gitignored, so that backup is the
only copy under version control. This is still the demo placeholder — replace it
with the client's own artwork when they supply it.

---

## 5. Second client round — black text, no COVID notice, Kenyan shillings

### 5.1 Black text, dark panels on-palette

`#184363` (navy) and `#56778f` (grey) were the demo's text colours. The client
asked for black text, and both are off-palette, so:

| Context | New |
|---|---|
| Foreground — copy, headings, icons, borders | `#000000` (21:1 on white) |
| Filled panels — footer, CTA blocks | `#4D6926` (`--panel-dark`, white text 6.24:1) |

The split matters: those two colours also painted the dark panels, which carry
white text. Brand green there would be 2.58:1, so they take the darkest brand
green instead.

Applied in four places, because these colours live in four separate stores:

1. **Theme options** — `main-typo`, `headings-typo`, `form-text-color`
   (`tools/apply-brand-palette.php`).
2. **Page content** — 2,661 occurrences across 107 rows
   (`tools/remap-content-colours.php`).
3. **Slider Revolution** — 135 occurrences across 15 rows in
   `wp_revslider_slides.layers`, stored as JSON in the plugin's own table, so
   neither of the above could see them. The remap tool now covers that table.
4. **Parent theme `style.css`** — 106 rules hardcode navy/grey with no theme
   option behind them. Generated into
   `propharm-child/assets/css/parent-overrides.css` by
   `tools/gen-parent-overrides.py`, rather than editing the parent, which an
   update would overwrite. Regenerate after a theme update.

The logo wordmark went navy → black; the untouched demo original is still at
`tools/logo-original-demo.svg`.

### 5.2 COVID-19 notice removed

`tools/remove-covid-notice.php` strips the `[et_header_slogan]` element carrying
"Due to the COVID 19 epidemic…" from all six header layouts. Only the slogan is
removed, not its row — that 48px top bar also holds the login toggle, currency
switcher and language switcher.

**Deliberately left in place:** a blog post about COVID vaccines, the "COVID 19"
product filter term, and a `covid-19` product tag. Those are real catalogue and
editorial content on a pharmacy site, not the banner.

### 5.3 Kenyan shillings

`tools/convert-currency-kes.php`. Changing `woocommerce_currency` alone only
swaps the symbol, so a product stored as 145.55 would have displayed as
"KSh 145.55" — about a hundredth of its intended value. Prices were therefore
converted as well: **196 values across 79 products/variations at 1 USD = 129 KES**,
rounded to the nearest 10.

`_price` is recomputed from the regular and sale prices rather than converted on
its own, so a rounded sale price can never land at or above its regular price.

Store settings: `woocommerce_currency = KES`, `price_num_decimals = 0`,
`currency_pos = left_space`.

**The YayCurrency plugin formats prices itself**, so its own entry
(`yay-currency-manage` post) had to be switched to KES with matching decimals and
position — otherwise it silently overrides the WooCommerce settings and prices
render as "KSh1,540" with no space, still to 2 decimals.

**The rate is an approximation, not client-approved pricing.** Re-run with a
different rate via `php tools/convert-currency-kes.php apply <rate>` — but only
against a database restored from before the first conversion, or the multiplier
compounds.

---

## 6. Client content round — branding, header, footer

All applied via `tools/apply-client-content.php` and `-2.php` (dry run by default).

| Change | Detail |
|---|---|
| Top-bar notice | "Free delivery within Nairobi", re-created as an `[et_header_slogan]` in the 6 headers the COVID notice was removed from. Header "Desktop 4" already had an unrelated slogan and was left alone |
| Language + currency switchers | `[et_language_switcher]` / `[et_currency_switcher]` removed from all 7 headers |
| Elements / Features menus | 27 nav items deleted from Header menu, Header menu boxy megamenu and Mobile header — 6 parents plus 21 descendants. Children must go too: WordPress promotes orphaned items to top level rather than deleting them |
| Logo | `uploads/kiki-logo.png` (749×353) as new attachment 2602; 10 posts repointed from attachment 335. The demo `logo.svg` attachment is untouched, so reverting is a one-line change |
| Footer | Address, two phone numbers, email, opening hours and copyright across all 5 footer layouts |

### Things worth knowing

**Footer 1603 is the one that renders**, not 564 (the `footer-id` option). It had no
phone or hours blocks at all, so those were *created* rather than replaced,
mirroring the styling of the existing icon boxes. Icon ids: 493 phone, 567 email,
1482 address.

**The address block was a `mailto:` link** in the demo. Removed — an address
should not open a mail client.

**YayCurrency was deactivated** rather than reconfigured. Its only remaining job
was the switcher the client asked to remove, and it formats prices itself, which
silently overrode the WooCommerce currency settings. Those are already correct, so
this is a clean removal — prices still render "KSh 2,840" afterwards. Reactivate
from the Plugins screen if a switcher is ever wanted again.

**Hyphens render as en dashes.** WordPress `wptexturize` turns "Monday - Friday"
into "Monday – Friday". Correct typography, but it means a literal string search
for the hyphenated form will not match the rendered page.

**Product description bullets are red** (`#DC2222`) — they already were, inherited
from the theme reusing `sale-color` for `li:before`. Confirmed as intended.
