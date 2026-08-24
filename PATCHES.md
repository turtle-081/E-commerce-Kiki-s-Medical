# Local patches

Changes made to code that this repo does not own. **A plugin or theme update
will silently revert everything in section 1** — nothing warns you, the symptom
just comes back. Check this file after any update.

Section 2 lists workarounds that live in our own child theme. Updates will not
touch them, but they should be *removed* once the upstream bug they work around
is fixed, or they will start fighting the corrected code.

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
serialises into one request and nothing renders until all of it finishes. Leave
it on unless `pm.max_children` is raised first.

---

## 3. Settings that live outside git

These are real configuration, but `.gitignore` excludes the files they live in, so
a fresh clone or a restore on another machine will not have them. Reapply by hand.

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

### `conf/php/php-fpm.d/www.conf.hbs` — PHP-FPM worker count (gitignored, Local-managed)

```
pm = static
pm.max_children = 2
```

Two worker processes on a 12-core machine. The theme fires 6–8 concurrent PHP
requests per page view (see `cache-queries` below), so they queue two at a time.
Combined with `max_execution_time = 1200` in `conf/php/php.ini.hbs`, a request a
browser has already given up on keeps occupying a worker for up to 20 minutes,
which starves everything behind it. Raising `pm.max_children` (8–12 here) needs a
site restart in Local to take effect.

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
