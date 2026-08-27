#!/usr/bin/env python3
"""Snapshot the loading-related attributes of every <img> on a page.

Usage:
    python tools/check-image-attrs.py snapshot <file>     # write a snapshot
    python tools/check-image-attrs.py diff <a> <b>        # compare two

Why this exists
---------------
Phase 6.5 regressed homepage LCP by 3.4 s by changing which images were
lazy-loaded: it applied core's "the first large image is the LCP" heuristic,
which is wrong on this theme because the hero is a Slider Revolution module
built in JavaScript, so the first <img> in source order is not what paints.

"This change does not touch how images load" is a claim, not evidence. This
turns it into evidence: snapshot before, snapshot after, diff.

What matters is *direction*. Making an image load later is the failure mode and
fails the check; making a known image load sooner cannot delay anything and is
reported separately as a promotion. Phase 6.7 used this to show a srcset change
moved nothing at all; Phase 8 used it to show that promoting the product gallery
image to `eager` moved exactly one image and nothing else.

The comparison is keyed on the image's source file, not its position, so a
page whose product carousel rotates its order between requests still diffs
cleanly.
"""

import json
import re
import sys
import urllib.request

PAGES = {
    "home": "http://client1.local/",
    "shop": "http://client1.local/shop/",
    "product": "http://client1.local/product/advil-minis-liquid-cap-x-90/",
}

IMG_RE = re.compile(r"<img\s[^>]*>", re.I)
ATTR_RE = re.compile(r"""([a-zA-Z_:-]+)\s*=\s*["']([^"']*)["']""")

# The attributes that decide *when and whether* an image loads. These must not
# move. `srcset`/`sizes` decide *which file* loads and are what we are adding.
LOADING_ATTRS = ("loading", "fetchpriority", "decoding")
DELIVERY_ATTRS = ("srcset", "sizes")


def fetch(url):
    req = urllib.request.Request(url, headers={"Accept": "text/html"})
    with urllib.request.urlopen(req, timeout=120) as resp:
        return resp.read().decode("utf-8", "replace")


def key_for(attrs):
    """Identify an image by the file it will actually load."""
    src = attrs.get("data-src") or attrs.get("src") or ""
    if src.startswith("data:"):
        src = attrs.get("data-src") or src[:32]
    return src.rsplit("/", 1)[-1] or src


def parse(html):
    out = {}
    for i, tag in enumerate(IMG_RE.findall(html)):
        attrs = {k.lower(): v for k, v in ATTR_RE.findall(tag)}
        k = key_for(attrs)
        # Same file can appear more than once (logo in two headers); number them.
        n, base = 0, k
        while k in out:
            n += 1
            k = f"{base}#{n}"
        out[k] = {
            "order": i,
            **{a: attrs.get(a, "") for a in LOADING_ATTRS},
            **{a: ("yes" if attrs.get(a) else "") for a in DELIVERY_ATTRS},
        }
    return out


def is_promotion(attr, before, after):
    """Is this change in the safe direction?

    Phase 6.5 broke LCP by making the element that paints *lazier* -- it
    deferred an image the browser needed immediately. The reverse, making a
    known image load sooner, cannot delay anything: an eager image is fetched
    at least as early as a lazy one.

    So direction is what matters, not the fact of a change. Demotions
    (eager -> lazy, high -> nothing) are the failure mode and fail the check.
    Promotions are reported separately so a deliberate one -- like the single
    product-gallery image in `product-image-priority.php` -- is visible without
    being an error.
    """
    if attr == "loading":
        return before in ("lazy", "") and after == "eager"
    if attr == "fetchpriority":
        return after == "high" and before != "high"
    if attr == "decoding":
        # Neither direction affects when the image is fetched.
        return True
    return False


def snapshot(path):
    data = {}
    for name, url in PAGES.items():
        try:
            data[name] = parse(fetch(url))
            print(f"  {name}: {len(data[name])} images")
        except Exception as exc:  # noqa: BLE001 - report and continue
            print(f"  {name}: FAILED {exc}", file=sys.stderr)
    with open(path, "w", encoding="utf-8") as fh:
        json.dump(data, fh, indent=1, sort_keys=True)
    print(f"wrote {path}")


def diff(path_a, path_b):
    with open(path_a, encoding="utf-8") as fh:
        a = json.load(fh)
    with open(path_b, encoding="utf-8") as fh:
        b = json.load(fh)

    bad = 0
    for page in sorted(set(a) | set(b)):
        pa, pb = a.get(page, {}), b.get(page, {})
        added = sorted(set(pb) - set(pa))
        removed = sorted(set(pa) - set(pb))
        loading_changed, delivery_added = [], []

        promotions = []
        for k in sorted(set(pa) & set(pb)):
            for at in LOADING_ATTRS:
                before, after = pa[k].get(at, ""), pb[k].get(at, "")
                if before == after:
                    continue
                line = f"{k}: {at} {before!r} -> {after!r}"
                if is_promotion(at, before, after):
                    promotions.append(line)
                else:
                    loading_changed.append(line)
            for at in DELIVERY_ATTRS:
                if not pa[k].get(at) and pb[k].get(at):
                    delivery_added.append(f"{k}: +{at}")

        print(f"\n=== {page} ===")
        print(f"  images: {len(pa)} -> {len(pb)}")
        print(f"  gained srcset/sizes: {len(delivery_added)}")
        if added:
            print(f"  NEW images ({len(added)}): {', '.join(added[:6])}")
        if removed:
            print(f"  GONE images ({len(removed)}): {', '.join(removed[:6])}")
        if promotions:
            print(f"  promoted (safe direction) ({len(promotions)}):")
            for line in promotions[:20]:
                print(f"       {line}")
        if loading_changed:
            bad += len(loading_changed)
            print(f"  !! DEMOTED -- loads later than before ({len(loading_changed)}):")
            for line in loading_changed[:20]:
                print(f"       {line}")
        elif not promotions:
            print("  loading/fetchpriority/decoding: unchanged")

    print()
    if bad:
        print(f"FAIL: {bad} image(s) now load later than before. This is the Phase 6.5 failure mode.")
        return 1
    print("PASS: nothing loads later than it did before.")
    return 0


def main():
    if len(sys.argv) >= 3 and sys.argv[1] == "snapshot":
        snapshot(sys.argv[2])
        return 0
    if len(sys.argv) >= 4 and sys.argv[1] == "diff":
        return diff(sys.argv[2], sys.argv[3])
    print(__doc__)
    return 2


if __name__ == "__main__":
    sys.exit(main())
