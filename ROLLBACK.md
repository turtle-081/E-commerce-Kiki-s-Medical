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

## Phases not yet applied

Phase 2 (page cache), 3 (skipped — no Cloudflare), 4 (WooCommerce), 5 (instant
navigation), 6 (payload). Rollback notes will be added here as each is applied.

Planned rollback shape for the ones that touch config:

- **Phase 2A** — the nginx cache directives go in `conf/nginx/*.hbs`, not the
  generated output. Comment out the `fastcgi_cache*` lines and restart the site
  in Local; the generated config is rebuilt from the templates.
- **Phase 4/5/6** — one file each under `mu-plugins/safi-performance/`. Delete
  the file to disable that change; nothing else references it.
