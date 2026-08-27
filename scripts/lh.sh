#!/usr/bin/env bash
# Lighthouse runner for the performance engagement.
#
# Usage: ./scripts/lh.sh <label> [runs] [page ...]
#
# Writes reports/<label>-<page>-<n>.json. Pages default to "home" and
# "product"; pass names from the PAGES map below to narrow it.
#
# Why a runner rather than a one-off npx line: scores on this machine swing by
# 30 points between identical runs (see PLATFORM.md), so every result in this
# engagement is a median of at least three. Doing that by hand invites
# comparing a single lucky run against a single unlucky one.

set -u

LABEL="${1:?usage: lh.sh <label> [runs] [page ...]}"
RUNS="${2:-3}"
shift 2 2>/dev/null || shift 1

BASE="http://client1.local"
PRODUCT_PATH="/product/advil-minis-liquid-cap-x-90/"

page_url() {
	case "$1" in
		home)    echo "$BASE/" ;;
		shop)    echo "$BASE/shop/" ;;
		product) echo "$BASE$PRODUCT_PATH" ;;
		*)       echo "" ;;
	esac
}

PAGES=("$@")
[ "${#PAGES[@]}" -eq 0 ] && PAGES=(home product)

mkdir -p reports

for page in "${PAGES[@]}"; do
	url="$(page_url "$page")"
	if [ -z "$url" ]; then
		printf 'unknown page "%s" -- expected home|shop|product\n' "$page" >&2
		exit 2
	fi

	for i in $(seq 1 "$RUNS"); do
		out="reports/${LABEL}-${page}-${i}.json"
		printf '%s run %s/%s -> %s\n' "$page" "$i" "$RUNS" "$out"
		npx --yes lighthouse "$url" \
			--preset=perf --form-factor=mobile \
			--throttling-method=simulate --screenEmulation.mobile \
			--chrome-flags="--headless=new --no-sandbox --disable-gpu" \
			--quiet --output=json --output-path="./$out" 2>/dev/null
	done
done

printf '\ndone: %s\n' "$LABEL"
