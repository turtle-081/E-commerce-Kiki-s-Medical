<?php
/**
 * Two fixes:
 *
 *   1. Footer: replace the two remaining off-brand blues.
 *   2. Content pages: wrap the plain HTML in the theme's container so it stops
 *      running edge to edge.
 *
 *   php tools/fix-footer-and-pages.php          # dry run
 *   php tools/fix-footer-and-pages.php apply    # write
 *
 * Note on what is NOT changed: a scan for off-palette hex values in the footers
 * also flags "#039" five times. That is not a colour -- it is the apostrophe
 * entity in "Kiki&#039;s Medical Equipment...". Replacing it would corrupt the
 * copyright line on every footer. The neutral greys (#4d4d4d, #eaeaea, #bdbdbd
 * and friends) are left alone too: they are legitimate UI neutrals for borders
 * and muted text, not brand colours, and flattening them would damage the
 * design.
 */

$apply = ( ( $argv[1] ?? '' ) === 'apply' );
$port  = isset( $argv[2] ) ? (int) $argv[2] : 10004;

$root   = dirname( __DIR__ );
$config = @file_get_contents( $root . '/app/public/wp-config.php' );
$conf   = array();
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

$stmt = $db->prepare( 'UPDATE wp_posts SET post_content=? WHERE ID=?' );

/* ------------------------------------------------------------------ footer */

$COLOURS = array(
	// The newsletter band. A pale blue left over from the demo, and the most
	// visible off-brand element on the page.
	'#b5e4f6' => '#F5F9F0',
	// Footer megamenu link hover.
	'#127ed1' => '#5A7B2D',
);

echo "1. footer colours\n";
$res = $db->query( "SELECT ID, post_title, post_content FROM wp_posts WHERE post_type='footer' AND post_status='publish'" );
$n   = 0;
while ( $row = $res->fetch_assoc() ) {
	$c    = $row['post_content'];
	$hits = 0;
	foreach ( $COLOURS as $from => $to ) {
		// Case-insensitive but literal: these strings never appear inside an
		// HTML entity, unlike the "#039" the scan false-positived on.
		$count = preg_match_all( '/' . preg_quote( $from, '/' ) . '/i', $c );
		if ( $count ) {
			$c     = preg_replace( '/' . preg_quote( $from, '/' ) . '/i', $to, $c );
			$hits += $count;
		}
	}
	if ( ! $hits ) {
		continue;
	}
	$n++;
	printf( "  %-5s %-10s %d replacement(s)\n", $row['ID'], $row['post_title'], $hits );
	if ( $apply ) {
		$stmt->bind_param( 'si', $c, $row['ID'] );
		$stmt->execute();
	}
}
printf( "  %d footer(s) updated\n", $n );

// Also in postmeta, where the row's generated CSS is cached.
$res = $db->query( "SELECT meta_id, meta_value FROM wp_postmeta WHERE meta_value LIKE '%b5e4f6%' OR meta_value LIKE '%127ed1%'" );
$mstmt = $db->prepare( 'UPDATE wp_postmeta SET meta_value=? WHERE meta_id=?' );
$mn = 0;
while ( $row = $res->fetch_assoc() ) {
	$v = $row['meta_value'];
	foreach ( $COLOURS as $from => $to ) {
		$v = preg_replace( '/' . preg_quote( $from, '/' ) . '/i', $to, $v );
	}
	if ( $v === $row['meta_value'] ) {
		continue;
	}
	$mn++;
	if ( $apply ) {
		$mstmt->bind_param( 'si', $v, $row['meta_id'] );
		$mstmt->execute();
	}
}
printf( "  %d postmeta row(s) updated\n", $mn );

/* ------------------------------------------------------------------- pages */

/*
 * The rewritten pages are plain HTML with no wrapper, so the theme renders them
 * at the full 1265px viewport width with zero padding -- text runs edge to edge.
 * Wrapping in the theme's own .container gives the same 1240px measure every
 * other page uses; .kiki-page (styled in brand.css) adds the vertical rhythm and
 * a comfortable reading width for prose.
 */
$SLUGS = array( 'about-us', 'contact-us', 'faq', 'delivery-information', 'returns-refunds', 'terms-conditions' );

echo "\n2. wrap page content in the theme container\n";
$in  = "'" . implode( "','", $SLUGS ) . "'";
$res = $db->query( "SELECT ID, post_title, post_name, post_content FROM wp_posts WHERE post_type='page' AND post_name IN ($in)" );
$n   = 0;
while ( $row = $res->fetch_assoc() ) {
	$c = trim( $row['post_content'] );
	if ( 0 === strpos( $c, '<div class="container kiki-page">' ) ) {
		printf( "  %-22s already wrapped\n", $row['post_name'] );
		continue;
	}
	if ( '' === $c || '<' !== $c[0] ) {
		printf( "  %-22s does not look like the HTML we wrote, skipped\n", $row['post_name'] );
		continue;
	}
	$new = "<div class=\"container kiki-page\">\n" . $c . "\n</div>";
	$n++;
	printf( "  %-22s wrapped\n", $row['post_name'] );
	if ( $apply ) {
		$stmt->bind_param( 'si', $new, $row['ID'] );
		$stmt->execute();
	}
}
printf( "  %d page(s) wrapped\n", $n );

if ( $apply ) {
	$db->query(
		"DELETE FROM wp_options WHERE option_name LIKE '_transient_enovathemes-%'
		 OR option_name LIKE '_transient_timeout_enovathemes-%'
		 OR option_name IN ('_transient_dynamic-styles-cached','_transient_timeout_dynamic-styles-cached')"
	);
	printf( "\ncleared %d cache row(s)\n", $db->affected_rows );
	echo "done\n";
} else {
	echo "\nDRY RUN - nothing written. Pass 'apply' to write.\n";
}
