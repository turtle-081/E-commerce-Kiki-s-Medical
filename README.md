# Kiki's Medical Equipment — WordPress site and performance engagement

WooCommerce store for Kiki's Medical Equipment and Hospital Supplies (Nairobi,
prices in KES), together with the full record of a performance engagement
carried out against it.

This repository is **private**, and it needs to stay that way — see
[Before you do anything](#before-you-do-anything).

---

## What this is

Two things in one tree:

1. **The site.** A WordPress 7.0 / WooCommerce 10.8 install running under Local
   by Flywheel on Windows. WordPress core is gitignored (it is reinstallable);
   what is tracked is everything that was written or configured for this client.
2. **The engagement.** Eight phases of performance work — caching, payload,
   database — with the measurements behind every decision, including the two
   changes that measured worse and were reverted.

Headline result: an anonymous page view went from a ~1.9 s PHP render to a
**24 ms cached hit**, homepage blocking time from **9.60 s to 2.21 s**, and
total transfer from **2,378 KB to 1,382 KB**. Three of the brief's six targets
were met; the three that were not are named, with the reasons, in `REPORT.md`.

---

## Start here

| Read this | When you want |
|---|---|
| **[PLATFORM.md](PLATFORM.md)** | the map — stack, repo layout, and the things that will bite you |
| **[REPORT.md](REPORT.md)** | what changed and why, phase by phase, including what was reverted |
| **[ROLLBACK.md](ROLLBACK.md)** | how to undo any single phase without touching the others |
| **[PATCHES.md](PATCHES.md)** | vendor edits an update will silently revert, and state git cannot reproduce |
| **[DISCOVERY.md](DISCOVERY.md)** | the environment audit and the "before" numbers |
| **[PLAYBOOK.md](PLAYBOOK.md)** | the field guide for doing this on a **different** site |

If you are picking this up cold, read `PLATFORM.md` first. It is written for
exactly that.

---

## Before you do anything

### 1. This repository must stay private

`app/public/wp-config.php` is tracked deliberately — the engagement required
every changed config file to be in version control. It contains **database
credentials and five WordPress auth salts**.

They are local-only development values, but they are still secrets. **If this
repository is ever made public, `wp-config.php` must be removed from history
first**, not just deleted from the working tree.

### 2. `conf/` is a template directory, not live config

Every file in `conf/` ends in `.hbs`. Local regenerates its runtime nginx and
PHP config from these on every site restart.

- Editing a runtime file gets a change that silently vanishes on restart.
- Editing only the template gets a change that does nothing until restart.

Edit the template, mirror the edit into the runtime copy, reload nginx. Full
instructions and the reload command are in `PLATFORM.md`.

### 3. A database restore undoes part of this work invisibly

Anything under `tools/` that edits post content or post meta lives **only in the
database**. Git cannot reproduce it. A restore undoes it while every file-based
change survives, so the tree looks correct and the site behaves differently.

This has already happened once. After any restore, re-run the content tools —
they are all idempotent, so running one that is already applied is free:

```bash
PHP="/c/Users/Turtle/AppData/Roaming/Local/lightning-services/php-8.2.29+0/bin/win64/php.exe"
INI="/c/Users/Turtle/AppData/Roaming/Local/run/cPNju-zlO/conf/php"
for t in inline-header-megamenu inline-mobile-header inline-footer inline-grid-ajax; do
    "$PHP" -c "$INI" "tools/$t.php"
done
"$PHP" -c "$INI" tools/flush-theme-caches.php
```

The one-line check that they took effect — the front page should be ~944 KB of
HTML, not ~622 KB, because the product grids are baked in:

```bash
curl -s http://client1.local/ | wc -c
```

See `PATCHES.md` §3.

---

## Layout

```
app/public/                       WordPress root (core is gitignored)
  wp-config.php                   tracked deliberately — see above
  wp-content/
    mu-plugins/safi-performance/  all performance work, one concern per file
    themes/propharm-child/        all theme customisation
conf/                             Local's config TEMPLATES — edit these
scripts/                          measurement harness
tools/                            one-off, reversible change scripts
reports/                          raw Lighthouse JSON (evidence, prunable)
```

Everything in `mu-plugins/safi-performance/` is independent: delete one file to
disable exactly that change, and nothing else breaks. Each opens with a comment
block saying what it does, why, what it deliberately does not do, and what to
delete to revert.

---

## Measuring

Never quote a single Lighthouse run. Scores on this machine have moved **23
points between measurement sessions with nothing changed**, so results are
quoted as ranges and the deterministic figures — bytes, request counts, TTFB —
are the ones to trust.

```bash
# TTFB and cache state across the key paths
bash scripts/measure.sh http://client1.local 5

# Lighthouse, N runs per page
bash scripts/lh.sh <label> 4 home product

# medians, spread, and the contamination guard
python scripts/lh-summary.py <baseline-label> <new-label>
```

`lh-summary.py` compares the HTML document size between labels and **refuses to
present the metrics as an improvement when it moved**. That guard exists because
an incomplete render once got cached and four runs measured a homepage that was
276 KB instead of 622 KB, with every metric looking dramatically better.

---

## Housekeeping

After **any** header, footer, menu or theme-option change:

```bash
php tools/flush-theme-caches.php    # clears theme transients AND the page cache
```

The two caches must be flushed together — the script does both for a reason
explained in `PLATFORM.md`.

After a bulk image upload, regenerate the WebP siblings (idempotent, so this is
cheap):

```bash
php tools/make-webp.php
```

After updating any plugin or theme:

```bash
bash tools/check-patches.sh         # verifies the vendor patches still hold
```

---

## Branches

`restore/local-environment` carries all of the engagement work. `master` is an
ancestor of it and is many commits behind — **nothing has merged the work
forward yet**, so the repository's default branch does not show any of it.

---

## Not in this repository

Machine-level state a fresh clone will not reproduce, all documented in
`PATCHES.md` §3:

- **PHP worker count** — `%APPDATA%\Local\sites.json`. Six workers; the default
  of two is a hard concurrency ceiling for the whole site.
- **Windows Defender exclusion** for the sites directory.
- **Every database change made by a tool** — see the warning above.

`package.json` / `package-lock.json` are a Next.js + Supabase manifest that
nothing here builds, installs or reads. They are inert; see commit `9f8b351a`
for what is known about them.
