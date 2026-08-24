<?php
/**
 * Finish the footer menus: four items in every column, no dead links.
 *
 *   php tools/finish-footer-menus.php          # dry run
 *   php tools/finish-footer-menus.php apply    # write
 *
 * Three things are left after the page work:
 *
 *   - "Terms & Conditions" still points at '#'. The earlier pass matched menu
 *     titles literally and this one is stored HTML-encoded, so it was missed.
 *   - "Popular" has five products, three of them demo placeholders literally
 *     named product38, product20 and product27.
 *   - "Useful" is down to two after the Career page was deleted.
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

/** Repoint a custom-link menu item at a real page. */
$point = function ( $menuItemId, $pageId ) use ( $db, $apply ) {
	if ( ! $apply ) {
		return;
	}
	$menuItemId = (int) $menuItemId;
	$pageId     = (int) $pageId;
	$db->query( "UPDATE wp_postmeta SET meta_value='post_type' WHERE post_id=$menuItemId AND meta_key='_menu_item_type'" );
	$db->query( "UPDATE wp_postmeta SET meta_value='page' WHERE post_id=$menuItemId AND meta_key='_menu_item_object'" );
	$db->query( "UPDATE wp_postmeta SET meta_value='$pageId' WHERE post_id=$menuItemId AND meta_key='_menu_item_object_id'" );
	$db->query( "UPDATE wp_postmeta SET meta_value='' WHERE post_id=$menuItemId AND meta_key='_menu_item_url'" );
};

$title = function ( $id ) use ( $db ) {
	$r = $db->query( 'SELECT post_title FROM wp_posts WHERE ID=' . (int) $id );
	return $r->num_rows ? $r->fetch_row()[0] : '(missing)';
};

/* ------------------------------------------------------- Terms & Conditions */
echo "1. Terms & Conditions -> the new page\n";
$terms = $db->query( "SELECT ID FROM wp_posts WHERE post_type='page' AND post_name='terms-conditions' LIMIT 1" );
if ( ! $terms->num_rows ) {
	echo "  terms-conditions page missing, skipped\n";
} else {
	$termsId = (int) $terms->fetch_row()[0];
	$res     = $db->query(
		"SELECT p.ID FROM wp_posts p
		 JOIN wp_postmeta pm ON pm.post_id=p.ID AND pm.meta_key='_menu_item_url' AND pm.meta_value='#'
		 WHERE p.post_type='nav_menu_item' AND p.post_title LIKE 'Terms%Conditions'"
	);
	$n = 0;
	while ( $row = $res->fetch_row() ) {
		echo "  menu item {$row[0]} -> page $termsId\n";
		$point( $row[0], $termsId );
		$n++;
	}
	echo "  $n item(s)\n";
}

/* ---------------------------------------------------------------- Popular */
echo "\n2. Popular -> four real products\n";

// menu item => product to point it at; null means delete the item.
$POPULAR = array(
	1124 => 25,   // Vitamin C 500mg Sugarless Tab X 300   (was product38)
	1125 => 102,  // Chemists' Own Antihistamine           (already real)
	1126 => 77,   // Cationorm Eye Drop Emulsion           (was product20)
	1127 => null, // product27 - dropped, brings column to four
	1128 => 132,  // Healthy Care Apple Cider Vinegar      (already real)
);

$toDelete = array();
foreach ( $POPULAR as $menuItemId => $productId ) {
	$exists = $db->query( "SELECT ID FROM wp_posts WHERE ID=$menuItemId AND post_type='nav_menu_item'" );
	if ( ! $exists->num_rows ) {
		echo "  menu item $menuItemId no longer exists, skipped\n";
		continue;
	}
	if ( null === $productId ) {
		echo "  menu item $menuItemId: delete (fifth item)\n";
		$toDelete[] = $menuItemId;
		continue;
	}
	printf( "  menu item %s -> product %s  %s\n", $menuItemId, $productId, $title( $productId ) );
	if ( $apply ) {
		$db->query( "UPDATE wp_postmeta SET meta_value='product' WHERE post_id=$menuItemId AND meta_key='_menu_item_object'" );
		$db->query( "UPDATE wp_postmeta SET meta_value='$productId' WHERE post_id=$menuItemId AND meta_key='_menu_item_object_id'" );
	}
}
if ( $apply && $toDelete ) {
	$list = implode( ',', array_map( 'intval', $toDelete ) );
	$db->query( "DELETE FROM wp_postmeta WHERE post_id IN ($list)" );
	$db->query( "DELETE FROM wp_term_relationships WHERE object_id IN ($list)" );
	$db->query( "DELETE FROM wp_posts WHERE ID IN ($list)" );
	echo '  deleted ' . count( $toDelete ) . " item(s)\n";
}

/* ----------------------------------------------------------------- Useful */
echo "\n3. Useful -> four items\n";

$USEFUL_PARENT = 1123 + 6; // 1129
$ADD           = array(
	array( 'Shop', 2518 ),
	array( 'Blog', 341 ),
);

$res     = $db->query(
	"SELECT COUNT(*) FROM wp_posts p
	 JOIN wp_postmeta pm ON pm.post_id=p.ID AND pm.meta_key='_menu_item_menu_item_parent' AND pm.meta_value='$USEFUL_PARENT'
	 WHERE p.post_type='nav_menu_item'"
);
$current = (int) $res->fetch_row()[0];
echo "  currently $current item(s)\n";

// Which nav_menu term does the Useful column belong to?
$termRow = $db->query(
	"SELECT tt.term_taxonomy_id FROM wp_term_relationships tr
	 JOIN wp_term_taxonomy tt ON tt.term_taxonomy_id = tr.term_taxonomy_id AND tt.taxonomy='nav_menu'
	 WHERE tr.object_id = $USEFUL_PARENT LIMIT 1"
);
$ttid = $termRow->num_rows ? (int) $termRow->fetch_row()[0] : 0;

$maxRow = $db->query( "SELECT COALESCE(MAX(menu_order),0) FROM wp_posts p JOIN wp_term_relationships tr ON tr.object_id=p.ID AND tr.term_taxonomy_id=$ttid WHERE p.post_type='nav_menu_item'" );
$order  = (int) $maxRow->fetch_row()[0];

foreach ( $ADD as $spec ) {
	list( $label, $pageId ) = $spec;
	$dupe = $db->query(
		"SELECT p.ID FROM wp_posts p
		 JOIN wp_postmeta pp ON pp.post_id=p.ID AND pp.meta_key='_menu_item_menu_item_parent' AND pp.meta_value='$USEFUL_PARENT'
		 JOIN wp_postmeta po ON po.post_id=p.ID AND po.meta_key='_menu_item_object_id' AND po.meta_value='$pageId'
		 WHERE p.post_type='nav_menu_item' LIMIT 1"
	);
	if ( $dupe->num_rows ) {
		echo "  $label already present, skipped\n";
		continue;
	}
	$order++;
	printf( "  add \"%s\" -> page %s (%s)\n", $label, $pageId, $title( $pageId ) );
	if ( ! $apply ) {
		continue;
	}
	$db->query(
		"INSERT INTO wp_posts (post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt,
		 post_status, comment_status, ping_status, post_password, post_name, to_ping, pinged,
		 post_modified, post_modified_gmt, post_content_filtered, post_parent, guid, menu_order, post_type, post_mime_type)
		 VALUES (1, NOW(), UTC_TIMESTAMP(), '', '', '', 'publish', 'closed', 'closed', '', '', '', '',
		 NOW(), UTC_TIMESTAMP(), '', 0, '', $order, 'nav_menu_item', '')"
	);
	$mid = $db->insert_id;
	foreach ( array(
		'_menu_item_type'             => 'post_type',
		'_menu_item_menu_item_parent' => (string) $USEFUL_PARENT,
		'_menu_item_object_id'        => (string) $pageId,
		'_menu_item_object'           => 'page',
		'_menu_item_target'           => '',
		'_menu_item_classes'          => 'a:1:{i:0;s:0:"";}',
		'_menu_item_xfn'              => '',
		'_menu_item_url'              => '',
	) as $k => $v ) {
		$k = $db->real_escape_string( $k );
		$v = $db->real_escape_string( $v );
		$db->query( "INSERT INTO wp_postmeta (post_id, meta_key, meta_value) VALUES ($mid, '$k', '$v')" );
	}
	if ( $ttid ) {
		$db->query( "INSERT INTO wp_term_relationships (object_id, term_taxonomy_id, term_order) VALUES ($mid, $ttid, 0)" );
		$db->query( "UPDATE wp_term_taxonomy SET count = count + 1 WHERE term_taxonomy_id = $ttid" );
	}
	echo "    created menu item $mid\n";
}

if ( ! $apply ) {
	echo "\nDRY RUN - nothing written. Pass 'apply' to write.\n";
	exit( 0 );
}

$db->query( "DELETE FROM wp_options WHERE option_name LIKE '_transient_enovathemes-%' OR option_name LIKE '_transient_timeout_enovathemes-%'" );
printf( "\ncleared %d cache row(s)\n", $db->affected_rows );
echo "done\n";
