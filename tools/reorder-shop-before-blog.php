<?php
/**
 * Put Shop before Blog in the header menus.
 *
 *   php tools/reorder-shop-before-blog.php          # dry run
 *   php tools/reorder-shop-before-blog.php apply    # write
 *
 * Applied to the desktop, boxy-megamenu and mobile header menus so the running
 * order matches whichever header layout is active.
 *
 * The two items' menu_order values are swapped rather than renumbered, so
 * everything around them keeps its position. Only top-level items are touched.
 */

$apply = ( ( $argv[1] ?? '' ) === 'apply' );
$port  = isset( $argv[2] ) ? (int) $argv[2] : 10004;

$MENUS      = array( 'Header menu', 'Header menu boxy megamenu', 'Mobile header' );
$SHOP_PAGE  = 2518; // the Shop page
$BLOG_PAGE  = 341;  // the Blog page

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

$find = function ( $menu, $objectId ) use ( $db ) {
	$menu = $db->real_escape_string( $menu );
	$sql  = "SELECT p.ID, p.menu_order
	         FROM wp_posts p
	         JOIN wp_term_relationships tr ON tr.object_id = p.ID
	         JOIN wp_term_taxonomy tt ON tt.term_taxonomy_id = tr.term_taxonomy_id AND tt.taxonomy='nav_menu'
	         JOIN wp_terms t ON t.term_id = tt.term_id
	         JOIN wp_postmeta pm_obj ON pm_obj.post_id = p.ID AND pm_obj.meta_key='_menu_item_object_id' AND pm_obj.meta_value='$objectId'
	         JOIN wp_postmeta pm_par ON pm_par.post_id = p.ID AND pm_par.meta_key='_menu_item_menu_item_parent' AND pm_par.meta_value='0'
	         WHERE p.post_type='nav_menu_item' AND t.name='$menu'
	         LIMIT 1";
	$r = $db->query( $sql );
	return ( $r && $r->num_rows ) ? $r->fetch_assoc() : null;
};

$stmt    = $db->prepare( 'UPDATE wp_posts SET menu_order=? WHERE ID=?' );
$changed = 0;

foreach ( $MENUS as $menu ) {
	$shop = $find( $menu, $SHOP_PAGE );
	$blog = $find( $menu, $BLOG_PAGE );

	if ( ! $shop || ! $blog ) {
		printf( "%-28s missing %s item, skipped\n", $menu, ! $shop ? 'Shop' : 'Blog' );
		continue;
	}

	if ( (int) $shop['menu_order'] < (int) $blog['menu_order'] ) {
		printf( "%-28s Shop (%s) already before Blog (%s)\n", $menu, $shop['menu_order'], $blog['menu_order'] );
		continue;
	}

	printf(
		"%-28s Blog %s -> %s,  Shop %s -> %s\n",
		$menu,
		$blog['menu_order'], $shop['menu_order'],
		$shop['menu_order'], $blog['menu_order']
	);
	$changed++;

	if ( $apply ) {
		$stmt->bind_param( 'ii', $shop['menu_order'], $blog['ID'] );
		$stmt->execute();
		$stmt->bind_param( 'ii', $blog['menu_order'], $shop['ID'] );
		$stmt->execute();
	}
}

if ( ! $apply ) {
	printf( "\nDRY RUN - %d menu(s) would change. Pass 'apply' to write.\n", $changed );
	exit( 0 );
}

printf( "\nswapped in %d menu(s)\n", $changed );

echo "\nverified order:\n";
foreach ( $MENUS as $menu ) {
	$shop = $find( $menu, $SHOP_PAGE );
	$blog = $find( $menu, $BLOG_PAGE );
	if ( $shop && $blog ) {
		printf(
			"  %-28s Shop=%s Blog=%s  %s\n",
			$menu,
			$shop['menu_order'],
			$blog['menu_order'],
			( (int) $shop['menu_order'] < (int) $blog['menu_order'] ) ? 'OK' : 'STILL WRONG'
		);
	}
}

$db->query( "DELETE FROM wp_options WHERE option_name LIKE '_transient_enovathemes-%' OR option_name LIKE '_transient_timeout_enovathemes-%'" );
printf( "cleared %d cache row(s)\n", $db->affected_rows );
