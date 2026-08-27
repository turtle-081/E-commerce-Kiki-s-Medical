# ROLLBACK

Each phase can be undone independently, without touching the others.

Paths are relative to the project root
(`C:\Users\Turtle\Local Sites\client1`). PHP commands use Local's bundled
binaries — see `tools/` for the full invocation used elsewhere in this project.

---

## Full restore (everything, all at once)

A verified dump was taken before Phase 7 and restored into a scratch database to
prove it works.

```bash
# dump
mysql --host=127.0.0.1 --port=10004 --user=root --password=root local \
  < scratchpad/phase7-pre.sql

# files (wp-config.php, child theme, conf/, tools/)
# unzip scratchpad/phase7-pre-files.zip over the project root
```

The verified restore also still exists as a separate database, `restore_check`,
which can be swapped in by pointing `DB_NAME` at it in `wp-config.php`.

---

## Phase 7 — database and background work

### Transient autoload fix

```bash
rm app/public/wp-content/mu-plugins/safi-performance/transient-autoload.php
```

Removing the file restores the previous behaviour immediately: the theme goes
back to creating its caches with no expiry, and WordPress goes back to
autoloading them. Existing transients keep their expiry until they lapse.

To also remove the loader and every other module:

```bash
rm app/public/wp-content/mu-plugins/safi-performance.php
rm -r app/public/wp-content/mu-plugins/safi-performance/
```

Nothing else depends on these files, and no vendor code was modified.

### Revision cap

```bash
wp config delete WP_POST_REVISIONS
```

Restores unlimited revisions.

### Deleted transients

No rollback needed or possible — transients are caches by definition and the
theme rebuilds them on the next page load.

---

## LiteSpeed Cache removal

The plugin **files were deliberately left on disk**, so this is reversible from
the WordPress Plugins screen: activate LiteSpeed Cache and it will recreate its
options with defaults.

What was removed, if you need to restore it by hand:

| Item | How to restore |
|---|---|
| Plugin activation | Activate from the Plugins screen |
| 192 `litespeed.*` options | Recreated with defaults on activation, or restore the dump |
| `WP_CACHE` in `wp-config.php` | Original kept at `app/public/wp-config.php.bak-litespeed` |
| `.htaccess` LSCACHE blocks | Rewritten by the plugin on activation (inert under nginx regardless) |
| `wp-content/litespeed/` | Recreated by the plugin |

**Before restoring it, read the reasoning in `REPORT.md`.** Its page cache and
object cache never functioned on this server — it installed no drop-ins. The only
thing it actually did was JavaScript lazy-loading, and that had to be
hand-excluded to stop it lazy-loading the site logo.

If the goal is only to get its lazy-loading back, prefer finishing Phase 6.4
instead: WebP output, correct `sizes`, and `fetchpriority` on the LCP image.

---

## Phase 2A — nginx FastCGI page cache

The cache directives live in `conf/nginx/nginx.conf.hbs` and
`conf/nginx/site.conf.hbs`, **not** in the generated runtime config — Local
rebuilds the runtime config from those templates on every site restart, so
editing the generated output alone would be silently reverted.

To disable the page cache:

1. Comment out the `fastcgi_cache`, `fastcgi_cache_valid`,
   `fastcgi_cache_bypass`, `fastcgi_no_cache` and `add_header X-FastCGI-Cache`
   lines in `conf/nginx/site.conf.hbs`.
2. Comment out `fastcgi_cache_path` and `fastcgi_cache_key` in
   `conf/nginx/nginx.conf.hbs`.
3. Restart the site in Local, then empty the store:

```bash
rm -rf "app/nginx-cache"/*
```

Both files are tracked in git, so `git checkout -- conf/nginx` also works.

### Purge module

```bash
rm app/public/wp-content/mu-plugins/safi-performance/cache-purge.php
```

Only removes automatic purging; it does not disable the cache itself. If the
cache is still on after removing this, purge by hand with the `rm -rf` above.

### OPcache settings

`conf/php/php.ini.hbs` — revert the `opcache.*` values and restart the site.

---

## Phase 4 — WooCommerce and theme AJAX

### Cart fragments and asset scoping

```bash
rm app/public/wp-content/mu-plugins/safi-performance/woo-fragments.php
rm app/public/wp-content/mu-plugins/safi-performance/woo-assets.php
```

`woo-fragments.php` also carries the replacement cart-count script, so removing
it restores WooCommerce's own `wc-cart-fragments` behaviour in one step — there
is no half-state where the badge stops updating.

### Inlined header megamenu and mobile header

These are header-builder settings, not code, so they are reverted with the same
scripts that applied them. Each stored the original `post_content` in post meta
before changing it.

```bash
php tools/inline-header-megamenu.php --revert
php tools/inline-mobile-header.php --revert
php tools/flush-theme-caches.php
rm -rf "app/nginx-cache"/*
```

The flush is required: the theme caches rendered header markup in transients, so
without it the old markup is served until the transient lapses (a week, since
Phase 7 gave these a real TTL).

The same toggles exist in the theme's header builder UI — *Megamenu ajax* on the
header button element, and *Async* on the mobile container — so the client can
also flip them back without the CLI.

### `cache-queries` theme option

Turned **off** in Theme Options. Turn it back on there, or:

```php
// value lives in the enovathemes option array, key 'cache-queries'
// '1' = on (per-page /ajax-api/ endpoints), '0' = off (render inline)
```

Re-run `php tools/flush-theme-caches.php` and empty the nginx cache afterwards.

---

## Phase 5 — instant navigation (Speculation Rules)

```bash
rm app/public/wp-content/mu-plugins/safi-performance/speculation-rules.php
rm -rf "app/nginx-cache"/*
```

This does **not** turn speculative loading off — it returns it to the WordPress
core default of `prefetch` / `conservative`, which was already active before this
engagement. Removing the file also removes the WooCommerce exclusions, but core's
own rules still exclude every URL with a query string, so add-to-cart links stay
safe under the weaker default.

To keep the exclusions but reduce the aggressiveness — the right move if the
client reports jank or battery drain on low-end Android — edit the file and
change `'prerender'` to `'prefetch'` instead of deleting it. The page cache must
be emptied either way, since the rules are baked into cached HTML.

---

## Phase 6 — payload

### Slider Revolution scoping

```bash
rm app/public/wp-content/mu-plugins/safi-performance/slider-assets.php
rm -rf "app/nginx-cache"/*
```

Restores Slider Revolution's assets on every page. Only do this if a slider is
added to a page the detection cannot see — in which case the better fix is the
escape hatch, which keeps the saving everywhere else:

```php
add_filter( 'safi_page_needs_revslider', '__return_true' ); // that template only
```

The homepage is unaffected either way: it contains a slider shortcode, so its
assets are never dequeued.

### Font loading

```bash
rm app/public/wp-content/mu-plugins/safi-performance/font-loading.php
rm -rf "app/nginx-cache"/*
```

Returns PT Sans to `display=swap`. Only do this if the client would rather see
the brand font on every first view than avoid the reflow — it reintroduces the
layout shift described in `REPORT.md`.

### Mobile header slogan row

In `themes/propharm-child/assets/css/brand.css`, delete the block commented
"Mobile header: give the slogan its own row". The file is tracked, so
`git checkout -- app/public/wp-content/themes/propharm-child/assets/css/brand.css`
also works. Empty the page cache afterwards.

Removing it restores the crowded single row, where the search toggle wraps and
the header changes height after paint. If the slogan itself is unwanted on
mobile, hiding it is the better alternative — that also fixes the shift, and
leaves the header at a single 64px row:

```css
@media (max-width: 1024px) { .et-mobile .header-slogan { display: none; } }
```

### Deferred JavaScript

```bash
rm app/public/wp-content/mu-plugins/safi-performance/script-loading.php
rm -rf "app/nginx-cache"/*
```

Returns every script to its original loading strategy. This is the change most
likely to be implicated if a front-end behaviour breaks, so it is the first thing
to remove when diagnosing.

To keep the benefit but exempt one script, prefer the denylist over removal:

```php
add_filter( 'safi_blocking_script_handles', function ( $handles ) {
    $handles[] = 'some-handle';   // stays render-blocking
    return $handles;
} );
```

Note that `woo-fragments.php` was also changed as part of this: its cart-count
script moved off `jquery-core` onto its own handle, because core will not defer
any handle carrying an `'after'` inline script. Reverting only `script-loading.php`
leaves that change in place, which is correct and harmless.

### Static asset cache headers

`conf/nginx/site.conf.hbs` — the three static `location` blocks. Restore with
`git checkout -- conf/nginx/site.conf.hbs`, then restart the site in Local so the
runtime config is regenerated from the template.

The previous values were `no-cache, must-revalidate` for CSS/JS and `expires 5m`
for images and fonts. There is no good reason to go back: the URLs are versioned,
so a long lifetime cannot serve stale content.

### Inlined footer

```bash
php tools/inline-footer.php --revert
php tools/flush-theme-caches.php
rm -rf "app/nginx-cache"/*
```

Returns the footers to AJAX loading. **Do not do this while
`script-loading.php` is active** — deferred scripts stop the theme's footer
hydration from running, which leaves the homepage footer blank. That combination
is the bug this change fixed.

The same toggle is in the theme's footer editor, so the client can flip it
without the CLI.

### WebP image delivery

Two independent halves. Removing either one alone is safe — the config without
the files serves the originals, and the files without the config are never
requested — so they can be reverted in whichever order suits.

**The generated files:**

```bash
PHP="/c/Users/Turtle/AppData/Roaming/Local/lightning-services/php-8.2.29+0/bin/win64/php.exe"
INI="/c/Users/Turtle/AppData/Roaming/Local/run/cPNju-zlO/conf/php"
"$PHP" -c "$INI" tools/make-webp.php --revert
```

Deletes only the double-extension files the script created (`*.png.webp`,
`*.jpg.webp`), so a genuine `.webp` upload is never touched. Add `--dry-run`
first to see the count.

**The nginx config:**

```bash
git checkout -- conf/nginx/nginx.conf.hbs conf/nginx/site.conf.hbs
```

then restart the site in Local so the runtime config is regenerated. The two
blocks are the `map $http_accept $webp_suffix` in `nginx.conf.hbs` and the
`location ~* \.(?:jpe?g|png)$` in `site.conf.hbs`, both commented "Phase 6.8".

To reload without a restart, edit the runtime copies too and reload nginx — see
PLATFORM.md. Backups of both runtime files were left beside them as
`nginx.conf.bak-p68` and `site.conf.bak-p68`.

**Neither the images nor the markup that references them were modified**, so
there is nothing else to undo. New uploads are not converted automatically;
re-running `tools/make-webp.php` picks them up and is idempotent, so it is cheap
to run on a schedule or after a bulk import.

### Homepage grids rendered inline

```bash
PHP="/c/Users/Turtle/AppData/Roaming/Local/lightning-services/php-8.2.29+0/bin/win64/php.exe"
INI="/c/Users/Turtle/AppData/Roaming/Local/run/cPNju-zlO/conf/php"
"$PHP" -c "$INI" tools/inline-grid-ajax.php --revert
"$PHP" -c "$INI" tools/flush-theme-caches.php
```

Returns the front page's four `[et_woo_products]` grids and one `[et_posts]`
to loading over `admin-ajax.php`. That costs four uncached PHP requests of
roughly 2.5 s each per view, so there is no performance reason to go back.

The same toggle is in the WPBakery element editor, so the client can flip an
individual grid without the CLI.

**Do not do this while `script-loading.php` is active** unless you re-check the
homepage afterwards. Deferred scripts are what broke the AJAX-hydrated footer in
Phase 6.6, and these grids hydrate the same way.

### Main product image priority

```bash
rm app/public/wp-content/mu-plugins/safi-performance/product-image-priority.php
rm -rf "app/nginx-cache"/*
```

Returns the single product page's gallery image to `loading="lazy"`. There is no
good reason to: it is the largest element on the page and the one the customer
came to look at, so deferring it delays LCP for no saving.

If it ever needs to be scoped differently, note that the module deliberately
promotes **only the first** image matching the product's own featured image, so
a multi-image gallery still lazy-loads the ones below the fold.

### Block editor stylesheets

```bash
rm app/public/wp-content/mu-plugins/safi-performance/block-assets.php
rm -rf "app/nginx-cache"/*
```

Restores `wp-block-library` and `wc-blocks-style` on every page. Only needed if
a page starts using blocks *and* the gate fails to notice — which should not
happen, because the module checks the rendered content for block delimiters on
every request and keeps the stylesheets when it finds any. Cart, checkout and
my-account are exempt unconditionally and are unaffected either way.

---

## Phases not yet applied

Phase 3 (skipped — no Cloudflare), 8 (final verification).
