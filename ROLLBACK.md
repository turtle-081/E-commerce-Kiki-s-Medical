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

---

## Phases not yet applied

Phase 3 (skipped — no Cloudflare), 8 (final verification).
