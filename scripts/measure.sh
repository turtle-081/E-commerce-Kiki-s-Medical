#!/usr/bin/env bash
# Baseline / after measurement for the performance engagement.
#
# Usage: ./scripts/measure.sh http://client1.local [runs]
#
# Adapted from the brief for this environment:
#   - %{header_json} is not portable across curl builds, so cache headers are
#     fetched separately with a HEAD request rather than inlined into -w
#   - product path is discovered rather than hardcoded
#   - a warm-up request is made and discarded, so run 1 is not measuring a cold
#     opcache/transient rebuild while runs 2-3 measure a warm one

set -u

URL="${1:-http://client1.local}"
RUNS="${2:-3}"

PRODUCT_PATH="/product/advil-minis-liquid-cap-x-90/"
PATHS=("/" "/shop/" "$PRODUCT_PATH" "/cart/")

printf '%s\n' "measuring $URL   ($RUNS runs per path, plus one discarded warm-up)"
printf '%s\n' "$(date)"
echo

for path in "${PATHS[@]}"; do
	echo "== $path"

	# Warm-up, discarded.
	curl -so /dev/null --max-time 300 "$URL$path" 2>/dev/null

	total_ttfb=0
	ok=0
	for i in $(seq 1 "$RUNS"); do
		read -r ttfb total size code <<<"$(
			curl -so /dev/null --max-time 300 \
				-w '%{time_starttransfer} %{time_total} %{size_download} %{http_code}' \
				-H 'Accept: text/html' "$URL$path" 2>/dev/null
		)"
		if [ -z "${ttfb:-}" ]; then
			printf '   run %s: FAILED\n' "$i"
			continue
		fi
		printf '   run %s: TTFB %6.3fs  total %6.3fs  %8s B  HTTP %s\n' \
			"$i" "$ttfb" "$total" "$size" "$code"
		total_ttfb=$(awk -v a="$total_ttfb" -v b="$ttfb" 'BEGIN{print a+b}')
		ok=$((ok + 1))
	done

	if [ "$ok" -gt 0 ]; then
		printf '   mean TTFB: %.3fs over %s run(s)\n' \
			"$(awk -v t="$total_ttfb" -v n="$ok" 'BEGIN{print t/n}')" "$ok"
	fi

	# Cache-related response headers.
	#
	# Deliberately a GET with -D, not `curl -I`. A HEAD request is not a GET, so
	# the cache-bypass rules skip it by design and it always reports BYPASS --
	# which makes a perfectly working cache look broken.
	curl -s --max-time 300 -o /dev/null -D - "$URL$path" 2>/dev/null |
		grep -iE '^(x-fastcgi-cache|x-cache-skipped|x-litespeed-cache|cf-cache-status|x-cache|cache-control|age|server):' |
		sed 's/^/   /'
	echo
done
