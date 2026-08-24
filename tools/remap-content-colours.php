<?php
/**
 * Remap the demo palette to the client's brand palette inside page content.
 *
 * The theme options only cover site-wide colours. The imported demo also sets
 * colours per page-builder module, stored in wp_posts.post_content and
 * wp_postmeta.meta_value, so those have to be rewritten separately.
 *
 *   php tools/remap-content-colours.php            # dry run, reports what would change
 *   php tools/remap-content-colours.php apply      # write
 *
 * Take a database backup first. This rewrites hundreds of rows:
 *   mysqldump --host=127.0.0.1 --port=10004 --user=root --password=root \
 *     local wp_posts wp_postmeta --result-file=backup.sql
 *
 * The mapping is context-aware rather than a blind find/replace, because the same
 * old colour means different things in different places. #80AF40 is 2.58:1 on
 * white, so it is only safe on background fills; every foreground use (text,
 * icons, borders, strokes) maps to the accessible #5A7B2D instead. Hover states
 * map one step darker so they stay visually distinct from their base colour --
 * a blind replace would collapse some pairs into a single colour and make the
 * hover disappear.
 */

$apply = ( ( $argv[1] ?? '' ) === 'apply' );
$port  = isset( $argv[2] ) ? (int) $argv[2] : 10004;

$root   = dirname( __DIR__ );
$config = @file_get_contents( $root . '/app/public/wp-config.php' );
if ( ! $config ) {
	fwrite( STDERR, "cannot read wp-config.php\n" );
	exit( 1 );
}
$conf = array();
foreach ( array( 'DB_NAME', 'DB_USER', 'DB_PASSWORD', 'DB_HOST' ) as $k ) {
	preg_match( "/define\(\s*'$k'\s*,\s*'([^']*)'/", $config, $m );
	$conf[ $k ] = $m[1] ?? '';
}
$host = preg_replace( '/:\d+$/', '', $conf['DB_HOST'] );
if ( 'localhost' === $host ) {
	$host = '127.0.0.1';
}

$db = mysqli_init();
$db->options( MYSQLI_OPT_CONNECT_TIMEOUT, 5 );
if ( ! @$db->real_connect( $host, $conf['DB_USER'], $conf['DB_PASSWORD'], $conf['DB_NAME'], $port ) ) {
	fwrite( STDERR, 'connect failed: ' . mysqli_connect_error() . "\n" );
	exit( 1 );
}
$db->set_charset( 'utf8mb4' );

/**
 * Old demo colours we are migrating away from.
 *
 * The first four are the demo's accent palette. The last two are the demo's
 * navy and grey: the client asked for black text, and navy/grey are off-palette,
 * so foreground uses become black and the dark panels they painted become the
 * darkest brand green.
 */
$OLD = array( '15a9e3', 'f2971f', '39cb74', 'edf4f6', '184363', '56778f' );

/** Names that mean "this is a filled area", where the bright brand green works. */
function is_background( $name ) {
	return (bool) preg_match( '/(^|[_\-])(back|background|bg)([_\-]|$)|^background/i', $name );
}

/**
 * Decide the replacement for one occurrence.
 *
 * @param string $old  lowercase hex without '#'
 * @param string $name the css property or shortcode attribute carrying it
 */
function target( $old, $name ) {
	$name  = strtolower( $name );
	$hover = ( false !== strpos( $name, 'hover' ) );

	switch ( $old ) {
		case '39cb74':
			// The demo's secondary green. It is the theme's sale-color *option*,
			// but in page content it is not used for sales at all -- it paints
			// et_icon_list ticks (38), et_icon_box (37), et_button (30) and
			// et_progress bars (9) on the About page, product descriptions and
			// posts. Recolouring those red would render ~138 feature ticks as
			// error icons. Red-for-sale is handled by the sale-color option
			// instead, so here this behaves as a positive accent: brand green.
			if ( is_background( $name ) ) {
				return $hover ? '#5A7B2D' : '#80AF40';
			}
			return $hover ? '#4D6926' : '#5A7B2D';

		case 'edf4f6': // demo's pale area tint -> tint of the brand green
			return $hover ? '#ECF3E2' : '#F5F9F0';

		case 'f2971f': // demo's CTA orange -> interactive green
			return $hover ? '#4D6926' : '#5A7B2D';

		case '15a9e3': // demo's primary blue -> brand green, split by context
			if ( is_background( $name ) ) {
				return $hover ? '#5A7B2D' : '#80AF40';
			}
			// Foreground: text, icons, borders, strokes. #80AF40 fails contrast
			// for all of these, so use the accessible green.
			return $hover ? '#4D6926' : '#5A7B2D';

		case '184363': // demo's navy
		case '56778f': // demo's body grey
			// Client asked for black text. Every foreground use -- copy, icons,
			// borders -- becomes black (21:1 on white).
			//
			// These same two colours also painted the dark panels (footer, CTA
			// blocks): 331 background uses for the navy alone. Those carry white
			// text, so they cannot become the brand green (2.58:1). They take the
			// darkest brand green instead, which holds white text at 6.24:1 and
			// keeps the panels on-palette.
			if ( is_background( $name ) ) {
				return '#4D6926';
			}
			return '#000000';
	}
	return null;
}

/** Rewrite one blob, returning [newValue, changeCount, perRuleTally]. */
function remap( $text, $OLD, &$tally ) {
	$count = 0;
	// The optional quote after the name lets this match JSON ("backgroundColor":"#...")
	// as well as CSS declarations and shortcode attributes.
	$re    = '/(?:([a-z0-9_\-]+)"?\s*[:=]\s*"?[^"{};]{0,60}?)?#?(' . implode( '|', $OLD ) . ')\b/i';

	$out = preg_replace_callback(
		$re,
		function ( $m ) use ( &$count, &$tally ) {
			$name = $m[1] ?? '';
			$old  = strtolower( $m[2] );
			$new  = target( $old, $name );
			if ( null === $new ) {
				return $m[0];
			}
			$key           = "#$old  ($name)" . ' -> ' . $new;
			$tally[ $key ] = ( $tally[ $key ] ?? 0 ) + 1;
			$count++;
			// Preserve everything before the hex exactly; swap only the colour.
			$prefix = substr( $m[0], 0, strlen( $m[0] ) - strlen( $m[2] ) );
			$prefix = rtrim( $prefix, '#' );
			return $prefix . $new;
		},
		$text
	);

	return array( $out, $count );
}

$tally    = array();
$totalRow = 0;
$totalOcc = 0;
$like     = implode( ' OR ', array_map( fn( $c ) => "LOWER(%s) LIKE '%$c%'", $OLD ) );

/*
 * Slider Revolution keeps its layer styling as JSON in its own table, so the
 * slider headlines are a third place colours hide -- not reachable through
 * wp_posts or wp_postmeta.
 */
$targets = array(
	array( 'wp_posts', 'ID', 'post_content' ),
	array( 'wp_postmeta', 'meta_id', 'meta_value' ),
	array( 'wp_revslider_slides', 'id', 'layers' ),
);

foreach ( $targets as list( $table, $pk, $col ) ) {
	// Skip tables that are not installed (RevSlider may be absent).
	if ( ! $db->query( "SHOW TABLES LIKE '$table'" )->num_rows ) {
		printf( "%-22s (table not present, skipped)\n", $table );
		continue;
	}
	$where = str_replace( '%s', $col, $like );
	$res   = $db->query( "SELECT $pk, $col FROM $table WHERE $where" );
	$rows  = 0;
	$occ   = 0;

	$stmt = $db->prepare( "UPDATE $table SET $col=? WHERE $pk=?" );

	while ( $row = $res->fetch_row() ) {
		list( $new, $n ) = remap( $row[1], $OLD, $tally );
		if ( ! $n || $new === $row[1] ) {
			continue;
		}
		$rows++;
		$occ += $n;
		if ( $apply ) {
			$stmt->bind_param( 'si', $new, $row[0] );
			$stmt->execute();
		}
	}
	printf( "%-12s  %4d row(s), %5d occurrence(s)\n", $table, $rows, $occ );
	$totalRow += $rows;
	$totalOcc += $occ;
}

echo "\nmapping applied:\n";
ksort( $tally );
foreach ( $tally as $k => $n ) {
	printf( "  %-52s %5d\n", $k, $n );
}
printf( "\n%s: %d row(s), %d occurrence(s)\n", $apply ? 'WRITTEN' : 'DRY RUN (nothing written)', $totalRow, $totalOcc );

if ( $apply ) {
	$db->query( "DELETE FROM wp_options WHERE option_name IN ('_transient_dynamic-styles-cached','_transient_timeout_dynamic-styles-cached')" );
	echo "cleared the generated-CSS transient; load a front-end page once to rebuild it\n";
} else {
	echo "pass 'apply' to write\n";
}
