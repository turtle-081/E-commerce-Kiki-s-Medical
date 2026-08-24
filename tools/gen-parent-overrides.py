#!/usr/bin/env python3
"""
Regenerate propharm-child/assets/css/parent-overrides.css.

The parent theme's style.css hardcodes the demo's navy (#184363) and grey
(#56778f) in ~106 rules. None of them is driven by a theme option, so neither the
palette change nor the content pass can reach them, and editing the parent theme
would be undone by an update.

This reads those rules and emits an override for each one, preserving the
original property (color / fill / stroke / background-color / ...) and any
!important, mapping:

    foreground declarations -> var(--text-black)   (#000000)
    background declarations -> var(--panel-dark)   (#4D6926)

Both custom properties are defined in brand.css, which parent-overrides.css
declares as a dependency.

Run from the repo root after a parent theme update:

    python tools/gen-parent-overrides.py
"""

import os
import re
import sys

SRC = "app/public/wp-content/themes/propharm/style.css"
DEST = "app/public/wp-content/themes/propharm-child/assets/css/parent-overrides.css"
OLD = r"184363|56778f"

HEADER = """
/* ---------------------------------------------------------------------------
 * GENERATED FILE - do not edit by hand.
 * Regenerate with:  python tools/gen-parent-overrides.py
 *
 * Navy (#184363) and grey (#56778f) hardcoded in the PARENT theme's style.css.
 * None of it is driven by a theme option, so neither the palette change nor the
 * content pass could reach it, and editing the parent would be lost on update.
 *
 * The client asked for black text, so every foreground declaration becomes
 * #000000. The few filled areas take the darkest brand green, which holds white
 * text at 6.24:1.
 * ------------------------------------------------------------------------ */
"""


def is_background(prop):
    return bool(re.search(r"(^|[-_])(background|bg)([-_]|$)|^background", prop, re.I))


def main():
    if not os.path.isfile(SRC):
        sys.exit(f"parent stylesheet not found: {SRC}\nrun this from the repo root")

    css = open(SRC, encoding="utf-8", errors="replace").read()
    rules = re.findall(r"([^{}]+)\{([^{}]*)\}", css)

    out = [HEADER]
    count = 0

    for sel, body in rules:
        hits = [d.strip() for d in body.split(";") if re.search(OLD, d, re.I)]
        if not hits:
            continue

        selector = " ".join(sel.split())
        if selector.startswith("@"):
            continue

        decls = []
        for decl in hits:
            if ":" not in decl:
                continue
            prop, val = decl.split(":", 1)
            prop = prop.strip()
            important = " !important" if "!important" in val.lower() else ""
            token = "var(--panel-dark)" if is_background(prop) else "var(--text-black)"
            decls.append(f"\t{prop}: {token}{important};")

        if not decls:
            continue

        count += 1
        out.append(f"{selector} {{")
        out.extend(decls)
        out.append("}\n")

    with open(DEST, "w", encoding="utf-8", newline="\n") as fh:
        fh.write("\n".join(out))

    print(f"generated {count} override rule(s) -> {DEST}")


if __name__ == "__main__":
    main()
