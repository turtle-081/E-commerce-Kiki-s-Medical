<?php
/**
 * Remove the Archives and Categories blog widgets from the shop page.
 *
 *   php tools/remove-shop-blog-widgets.php          # dry run
 *   php tools/remove-shop-blog-widgets.php apply    # write
 *
 * They are WordPress's default first-run block widgets (block-5 "Archives",
 * block-6 "Categories") that ended up in the shop-bottom widget area, so every
 * shop page listed the blog archive months and post categories underneath the
 * products.
 *
 * They are moved to wp_inactive_widgets rather than deleted. That is what
 * WordPress itself does when a widget is dragged out of a sidebar: the widget's
 * settings survive, so it can be dropped back in from Appearance > Widgets.
 * block-2/3/4 (Search, Recent Posts, Recent Comments) are already sitting there.
 *
 * The banner widget in the same area is left alone.
 */

$apply = ( ( $argv[1] ?? '' ) === 'apply' );
$port  = isset( $argv[2] ) ? (int) $argv[2] : 10004;

$SIDEBAR = 'shop-bottom-widgets';
$REMOVE  = array( 'block-5', 'block-6' );

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

$row = $db->query( "SELECT option_value FROM wp_options WHERE option_name='sidebars_widgets'" )->fetch_row();
if ( ! $row ) {
	fwrite( STDERR, "sidebars_widgets option not found\n" );
	exit( 1 );
}
$sidebars = unserialize( $row[0] );
if ( ! is_array( $sidebars ) ) {
	fwrite( STDERR, "sidebars_widgets did not unserialize to an array\n" );
	exit( 1 );
}

$current = $sidebars[ $SIDEBAR ] ?? array();
printf( "%s before: %s\n", $SIDEBAR, $current ? implode( ', ', $current ) : '(empty)' );

$moving = array_values( array_intersect( $current, $REMOVE ) );
if ( ! $moving ) {
	echo "nothing to remove - already done\n";
	exit( 0 );
}

$sidebars[ $SIDEBAR ]        = array_values( array_diff( $current, $REMOVE ) );
$sidebars['wp_inactive_widgets'] = array_values( array_unique( array_merge( $sidebars['wp_inactive_widgets'] ?? array(), $moving ) ) );

printf( "%s after:  %s\n", $SIDEBAR, $sidebars[ $SIDEBAR ] ? implode( ', ', $sidebars[ $SIDEBAR ] ) : '(empty)' );
printf( "moved to wp_inactive_widgets: %s\n", implode( ', ', $moving ) );

if ( ! $apply ) {
	echo "\nDRY RUN - nothing written. Pass 'apply' to write.\n";
	exit( 0 );
}

$new = serialize( $sidebars );
$s   = $db->prepare( "UPDATE wp_options SET option_value=? WHERE option_name='sidebars_widgets'" );
$s->bind_param( 's', $new );
$s->execute();
printf( "\nupdated %d row(s)\n", $s->affected_rows );

// Read back so a silent serialisation problem cannot pass unnoticed.
$check = unserialize( $db->query( "SELECT option_value FROM wp_options WHERE option_name='sidebars_widgets'" )->fetch_row()[0] );
printf(
	"verified: %s now holds [%s]; inactive holds [%s]\n",
	$SIDEBAR,
	implode( ', ', $check[ $SIDEBAR ] ),
	implode( ', ', $check['wp_inactive_widgets'] )
);

$db->query( "DELETE FROM wp_options WHERE option_name LIKE '_transient_enovathemes-%' OR option_name LIKE '_transient_timeout_enovathemes-%'" );
printf( "cleared %d cache row(s)\n", $db->affected_rows );
