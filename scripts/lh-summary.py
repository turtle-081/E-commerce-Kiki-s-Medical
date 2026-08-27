#!/usr/bin/env python3
"""Summarise Lighthouse runs written by scripts/lh.sh.

Usage: python scripts/lh-summary.py <label> [<label> ...]

Prints the median of each metric across the runs for a label, and -- when more
than one label is given -- the delta against the first. Medians, not means: a
single pathological run on this machine (TBT has come back at 20 s on a page
whose median is 1 s) would drag a mean somewhere meaningless.

Spread is printed alongside every median for the same reason. A delta smaller
than the spread of either side is noise, and is marked as such rather than
reported as an improvement.
"""

import glob
import json
import os
import statistics
import sys

# key -> (label, unit, lower_is_better)
METRICS = [
    ("score", "score", "", False),
    ("largest-contentful-paint", "LCP", "ms", True),
    ("first-contentful-paint", "FCP", "ms", True),
    ("speed-index", "SI", "ms", True),
    ("total-blocking-time", "TBT", "ms", True),
    ("cumulative-layout-shift", "CLS", "", True),
]

# Byte totals pulled from the network records rather than an audit, so they
# stay comparable across Lighthouse versions that rename their audits.
RESOURCE_KINDS = ("image", "script", "stylesheet", "document", "font", "total")


def load(label, page):
    out = []
    for path in sorted(glob.glob(f"reports/{label}-{page}-*.json")):
        try:
            with open(path, encoding="utf-8") as fh:
                out.append((os.path.basename(path), json.load(fh)))
        except (OSError, ValueError) as exc:
            print(f"  ! skipping {path}: {exc}", file=sys.stderr)
    return out


def metric(report, key):
    if key == "score":
        return report["categories"]["performance"]["score"] * 100
    audit = report["audits"].get(key)
    if not audit:
        return None
    return audit.get("numericValue")


def resources(report):
    """Transfer size by resource type, in KB."""
    summary = report["audits"].get("resource-summary", {}).get("details", {})
    got = {}
    for item in summary.get("items", []):
        if item.get("resourceType") in RESOURCE_KINDS:
            got[item["resourceType"]] = item.get("transferSize", 0) / 1024
    if got:
        return got
    # resource-summary is gone in newer Lighthouse; fall back to the network log.
    items = report["audits"].get("network-requests", {}).get("details", {}).get("items", [])
    for it in items:
        kind = (it.get("resourceType") or "other").lower()
        size = (it.get("transferSize") or 0) / 1024
        got[kind] = got.get(kind, 0) + size
        got["total"] = got.get("total", 0) + size
    return got


def document_sizes(report):
    """Uncompressed size of the HTML document itself.

    This is the contamination check. The theme rebuilds its layout transients
    lazily, and a render that happens during a rebuild can emit the page
    without its megamenus -- which nginx then caches and serves as a HIT for
    24 hours. Four runs were once measured against a homepage that was 276 KB
    instead of 622 KB, and every metric looked like an improvement.

    A page that got dramatically faster *and* dramatically smaller lost
    content. Comparing document size across runs is what catches it.
    """
    for item in report["audits"].get("network-requests", {}).get("details", {}).get("items", []):
        if item.get("resourceType") == "Document":
            return item.get("resourceSize") or 0
    return 0


def summarise(label, pages):
    out = {}
    for page in pages:
        runs = load(label, page)
        if not runs:
            continue
        rows = {}
        for key, name, unit, lower in METRICS:
            vals = [v for v in (metric(r, key) for _, r in runs) if v is not None]
            if vals:
                rows[name] = (statistics.median(vals), min(vals), max(vals), unit, lower)
        res = {}
        for kind in RESOURCE_KINDS:
            vals = [resources(r).get(kind, 0) for _, r in runs]
            vals = [v for v in vals if v]
            if vals:
                res[kind] = statistics.median(vals)
        docs = [document_sizes(r) for _, r in runs]
        docs = [d for d in docs if d]
        out[page] = {
            "n": len(runs),
            "metrics": rows,
            "resources": res,
            "doc": statistics.median(docs) if docs else 0,
            "doc_range": (min(docs), max(docs)) if docs else (0, 0),
        }
    return out


def check_documents(data, labels, pages):
    """Warn when the HTML being measured is not the same HTML across labels."""
    warnings = []
    for page in pages:
        sizes = {}
        for lab in labels:
            entry = data[lab].get(page)
            if entry and entry["doc"]:
                sizes[lab] = entry
                lo, hi = entry["doc_range"]
                if lo and hi > lo * 1.2:
                    warnings.append(
                        f"{page}: document size varies within {lab} "
                        f"({lo:,} - {hi:,} bytes) -- runs are not comparable"
                    )
        if len(sizes) > 1:
            vals = [e["doc"] for e in sizes.values()]
            if min(vals) and max(vals) > min(vals) * 1.2:
                detail = ", ".join(f"{lab} {int(e['doc']):,}" for lab, e in sizes.items())
                warnings.append(
                    f"{page}: document size differs between labels ({detail}). "
                    "A smaller document means the page lost content -- do not "
                    "read the metrics as an improvement."
                )
    return warnings


def fmt(value, unit):
    if unit == "ms":
        return f"{value/1000:.2f} s" if value >= 1000 else f"{value:.0f} ms"
    if unit == "":
        return f"{value:.3f}" if value < 10 else f"{value:.0f}"
    return f"{value:.0f}{unit}"


def main():
    labels = sys.argv[1:]
    if not labels:
        print(__doc__)
        return 2

    pages = ["home", "shop", "product"]
    data = {lab: summarise(lab, pages) for lab in labels}
    base = labels[0]

    warnings = check_documents(data, labels, pages)
    if warnings:
        print("\n!! DOCUMENT SIZE WARNING")
        for w in warnings:
            print(f"   {w}")

    for page in pages:
        if not any(page in data[lab] for lab in labels):
            continue
        print(f"\n=== {page} ===")
        header = f"{'':<6}" + "".join(
            f"{lab + ' (n=' + str(data[lab].get(page, {}).get('n', 0)) + ')':>22}"
            for lab in labels
        )
        print(header)

        names = [n for _, n, _, _ in METRICS]
        for name in names:
            row = f"{name:<6}"
            base_val = data[base].get(page, {}).get("metrics", {}).get(name)
            for lab in labels:
                cell = data[lab].get(page, {}).get("metrics", {}).get(name)
                if not cell:
                    row += f"{'-':>22}"
                    continue
                med, lo, hi, unit, lower = cell
                txt = f"{fmt(med, unit)} [{fmt(lo, unit)}-{fmt(hi, unit)}]"
                row += f"{txt:>22}"
            print(row)
            # Delta line, only when it clears the noise floor.
            if len(labels) > 1 and base_val:
                bmed, blo, bhi, unit, lower = base_val
                deltas = []
                for lab in labels[1:]:
                    cell = data[lab].get(page, {}).get("metrics", {}).get(name)
                    if not cell:
                        deltas.append("-")
                        continue
                    med, lo, hi, _, _ = cell
                    diff = med - bmed
                    spread = max(bhi - blo, hi - lo)
                    if abs(diff) <= spread / 2:
                        deltas.append("noise")
                    else:
                        better = (diff < 0) == lower
                        deltas.append(("BETTER " if better else "WORSE ") + fmt(abs(diff), unit))
                print(f"{'':<6}{'':>22}" + "".join(f"{d:>22}" for d in deltas))

        print(f"{'-'*6}")
        row = f"{'html':<6}"
        for lab in labels:
            v = data[lab].get(page, {}).get("doc")
            row += f"{'-':>22}" if not v else f"{v/1024:>19.0f} KB"
        print(row)
        for kind in RESOURCE_KINDS:
            row = f"{kind[:6]:<6}"
            any_val = False
            for lab in labels:
                v = data[lab].get(page, {}).get("resources", {}).get(kind)
                if v is None:
                    row += f"{'-':>22}"
                else:
                    any_val = True
                    row += f"{v:>19.0f} KB"
            if any_val:
                print(row)
    print()
    return 0


if __name__ == "__main__":
    sys.exit(main())
