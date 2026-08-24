<?php
/**
 * Stop LiteSpeed lazy-loading the site logo.
 *
 *   php tools/fix-logo-lazyload.php          # dry run
 *   php tools/fix-logo-lazyload.php apply    # write
 *
 * LiteSpeed's lazy loader replaces every <img> src with a 1x1 base64 placeholder
 * and only fetches the real file once its JavaScript has run. That is right for
 * images below the fold and wrong for the logo, which is in the header on every
 * page: the request cannot even start until the lazy-load script executes, so the
 * logo visibly pops in a moment after the rest of the page.
 *
 * Excluded by class rather than by filename, so it keeps working if the logo file
 * is ever swapped. The theme renders the header logo as <img class="logo"> and
 * the sticky variant as <img class="sticky-logo">.
 */

$apply = ( ( $argv[1] ?? '' ) === 'apply' );
$port  = isset( $argv[2] ) ? (int) $argv[2] : 10004;

$OPTION  = 'litespeed.conf.media-lazy_cls_exc';
$CLASSES = array( 'logo', 'sticky-logo' );

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

$row = $db->query( "SELECT option_value FROM wp_options WHERE option_name='$OPTION' LIMIT 1" );
if ( ! $row || ! $row->num_rows ) {
	fwrite( STDERR, "$OPTION not found - is LiteSpeed Cache installed?\n" );
	exit( 1 );
}
$raw     = $row->fetch_row()[0];
$current = json_decode( $raw, true );
if ( ! is_array( $current ) ) {
	$current = array();
}

printf( "%s\n  before: %s\n", $OPTION, $raw );

$merged = array_values( array_unique( array_merge( $current, $CLASSES ) ) );
if ( $merged === $current ) {
	echo "  already excluded - nothing to do\n";
	exit( 0 );
}

$new = json_encode( $merged, JSON_UNESCAPED_SLASHES );
printf( "  after:  %s\n", $new );

if ( ! $apply ) {
	echo "\nDRY RUN - nothing written. Pass 'apply' to write.\n";
	exit( 0 );
}

$s = $db->prepare( "UPDATE wp_options SET option_value=? WHERE option_name='$OPTION'" );
$s->bind_param( 's', $new );
$s->execute();
printf( "\nupdated %d row(s)\n", $s->affected_rows );

$check = $db->query( "SELECT option_value FROM wp_options WHERE option_name='$OPTION'" )->fetch_row()[0];
printf( "verified: %s\n", $check );

// LiteSpeed caches rendered HTML, and the placeholder markup is baked into it.
$db->query( "DELETE FROM wp_options WHERE option_name LIKE '_transient_litespeed%' OR option_name LIKE '_transient_timeout_litespeed%'" );
$db->query( "DELETE FROM wp_options WHERE option_name LIKE '_transient_enovathemes-%' OR option_name LIKE '_transient_timeout_enovathemes-%'" );
printf( "cleared %d cache row(s)\n", $db->affected_rows );
echo "also purge LiteSpeed's page cache (LiteSpeed Cache > Toolbox > Purge All) if pages still show the placeholder\n";
