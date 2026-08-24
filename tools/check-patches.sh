#!/usr/bin/env bash
#
# Verifies that the local patches described in PATCHES.md are still present.
# A plugin update silently reverts them, so run this after updating anything.
#
# Exit status: 0 = all patches present, 1 = at least one is missing.

set -u

cd "$(dirname "$0")/.." || exit 1

status=0

# Emits a file's contents with comment-only lines removed, so that patterns
# named in our own explanatory comments are not mistaken for live code.
code_lines() {
	grep -v '^[[:space:]]*\(//\|\*\|/\*\|#\)' "$1"
}

# check <expected-count> <label> <file> <pattern>
check() {
	local expected="$1" label="$2" file="$3" pattern="$4" found

	if [ ! -f "$file" ]; then
		printf 'MISSING FILE  %s\n              %s\n' "$label" "$file"
		status=1
		return
	fi

	found=$(code_lines "$file" | grep -cF -- "$pattern")

	if [ "$found" -eq "$expected" ]; then
		printf 'ok            %s (%s/%s)\n' "$label" "$found" "$expected"
	else
		printf 'REVERTED      %s (%s/%s occurrences)\n              %s\n' \
			"$label" "$found" "$expected" "$file"
		status=1
	fi
}

# check_absent <label> <file> <pattern> - fails if the pattern is still present.
check_absent() {
	local label="$1" file="$2" pattern="$3" found

	if [ ! -f "$file" ]; then
		printf 'MISSING FILE  %s\n              %s\n' "$label" "$file"
		status=1
		return
	fi

	found=$(code_lines "$file" | grep -cF -- "$pattern")

	if [ "$found" -eq 0 ]; then
		printf 'ok            %s\n' "$label"
	else
		printf 'REVERTED      %s (deprecated call is back, %s occurrence(s))\n              %s\n' \
			"$label" "$found" "$file"
		status=1
	fi
}

echo "Checking local patches (see PATCHES.md)"
echo

ENOVA="app/public/wp-content/plugins/enovathemes-addons/enovathemes-addons.php"
DISCO="app/public/wp-content/plugins/disco/vendor/inpsyde/assets/src/OutputFilter/AttributesOutputFilter.php"

check 6 "enovathemes-addons: WPBMap guards present" "$ENOVA" "class_exists('WPBMap')"

# Belt and braces: catches new unguarded call sites an update might introduce,
# which a fixed expected-count check on its own would miss.
unguarded=$(grep -n "WPBMap::" "$ENOVA" | grep -v "class_exists" | grep -v '^[0-9]*:[[:space:]]*\(//\|\*\)')
if [ -z "$unguarded" ]; then
	printf 'ok            enovathemes-addons: no unguarded WPBMap calls\n'
else
	printf 'REVERTED      enovathemes-addons: unguarded WPBMap call(s) found\n'
	printf '%s\n' "$unguarded" | sed 's/^/              /'
	status=1
fi

DYNSTYLES="app/public/wp-content/plugins/enovathemes-addons/includes/dynamic-styles.php"
check 1 "enovathemes-addons: CSS write guarded" "$DYNSTYLES" 'md5_file($file) !== md5($dynamic_css)'
check_absent "enovathemes-addons: per-request CSS write gone" "$DYNSTYLES" 'file_put_contents($file, $dynamic_css);'

# Match the call itself, not the explanatory comment that names the function.
check 1 "disco: mbstring fix present" "$DISCO" 'mb_encode_numericentity($html'
check_absent "disco: deprecated call removed" "$DISCO" "mb_convert_encoding("

echo
if [ "$status" -eq 0 ]; then
	echo "All patches present."
else
	echo "One or more patches were reverted - see PATCHES.md to restore them."
fi

exit "$status"
