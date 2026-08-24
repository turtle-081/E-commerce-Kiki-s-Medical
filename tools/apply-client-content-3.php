<?php
/**
 * Third content round:
 *
 *   1. remove the "More to love" block and the banner under it from the single
 *      product sidebar
 *   2. trim the footer menus: delete "Sales" and "Dashboard", and drop the
 *      leftover demo pages
 *   3. point the "Show on map" links at the client's Google Maps location, and
 *      add one to the footer that actually renders
 *
 *   php tools/apply-client-content-3.php          # dry run
 *   php tools/apply-client-content-3.php apply    # write
 *
 * Take a database backup first.
 */

$apply = ( ( $argv[1] ?? '' ) === 'apply' );
$port  = isset( $argv[2] ) ? (int) $argv[2] : 10004;

$MAP_URL = 'https://maps.app.goo.gl/BfGpndo2C43aCZDe7';

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

$stmt = $db->prepare( 'UPDATE wp_posts SET post_content=? WHERE ID=?' );

/* ---------------------------------------------------------------------------
 * 1. "More to love" + the banner beneath it.
 *
 * In banner 2060 (the single-product sidebar) the run is:
 *   [et_heading 916655]More to love[/et_heading]
 *   [et_gap 68843] [et_woo_products 844715] [et_gap 667555]
 *   [et_banner 939467] ... [/et_banner]
 *
 * The whole run is removed: the heading on its own would leave an orphaned
 * product grid, and the banner is what sits directly under it.
 * ------------------------------------------------------------------------ */
echo "1. remove \"More to love\" + the banner below it\n";

$row = $db->query( "SELECT ID, post_title, post_content FROM wp_posts WHERE ID=2060" )->fetch_assoc();
if ( ! $row ) {
	echo "  banner 2060 not found, skipped\n";
} else {
	$c     = $row['post_content'];
	$start = strpos( $c, '[et_heading' );
	// Find the heading that actually carries element_id 916655.
	$start = false;
	if ( preg_match( '/\[et_heading[^\]]*element_id="916655"/', $c, $m, PREG_OFFSET_CAPTURE ) ) {
		$start = $m[0][1];
	}

	$end = false;
	if ( false !== $start && preg_match( '/\[et_banner[^\]]*element_id="939467"/', $c, $m2, PREG_OFFSET_CAPTURE, $start ) ) {
		$close = strpos( $c, '[/et_banner]', $m2[0][1] );
		if ( false !== $close ) {
			$end = $close + strlen( '[/et_banner]' );
		}
	}

	if ( false === $start || false === $end ) {
		echo "  block not found (already removed?), skipped\n";
	} else {
		$new = substr( $c, 0, $start ) . substr( $c, $end );
		printf( "  banner 2060: removing %d characters (heading, product grid, gaps, banner)\n", $end - $start );
		if ( $apply ) {
			$stmt->bind_param( 'si', $new, $row['ID'] );
			$stmt->execute();
		}
	}
}

/* ---------------------------------------------------------------------------
 * 2. Footer menu trimming.
 *
 * "Sales" and "Dashboard" were named explicitly; removing them brings the
 * Information and Account columns to four items each.
 *
 * "Features" and "Elements" go too. They are demo pages, already removed from
 * the header menus in an earlier round, and do not belong on a client site.
 * That leaves the "Useful" column at three -- see the note printed at the end.
 * ------------------------------------------------------------------------ */
echo "\n2. trim the footer menus\n";

$FOOTER_MENUS = array( 'Footer menu', 'Footer menu 2' );
$BY_TITLE     = array( 'Sales', 'Dashboard' );
$BY_PAGE      = array( '343', '345' ); // Elements, Features

$in  = "'" . implode( "','", array_map( array( $db, 'real_escape_string' ), $FOOTER_MENUS ) ) . "'";
$res = $db->query(
	"SELECT p.ID, p.post_title, t.name AS menu,
	        (SELECT meta_value FROM wp_postmeta WHERE post_id=p.ID AND meta_key='_menu_item_object_id') AS objid,
	        (SELECT meta_value FROM wp_postmeta WHERE post_id=p.ID AND meta_key='_menu_item_menu_item_parent') AS parent
	 FROM wp_posts p
	 JOIN wp_term_relationships tr ON tr.object_id = p.ID
	 JOIN wp_term_taxonomy tt ON tt.term_taxonomy_id = tr.term_taxonomy_id AND tt.taxonomy='nav_menu'
	 JOIN wp_terms t ON t.term_id = tt.term_id
	 WHERE p.post_type='nav_menu_item' AND t.name IN ($in)"
);

$doomed = array();
while ( $r = $res->fetch_assoc() ) {
	$label = $r['post_title'];
	if ( '' === $label && $r['objid'] ) {
		$t = $db->query( 'SELECT post_title FROM wp_posts WHERE ID=' . (int) $r['objid'] );
		if ( $t->num_rows ) {
			$label = $t->fetch_row()[0];
		}
	}
	if ( in_array( $label, $BY_TITLE, true ) || in_array( (string) $r['objid'], $BY_PAGE, true ) ) {
		$doomed[ (int) $r['ID'] ] = sprintf( '%s / %s', $r['menu'], $label );
	}
}

foreach ( $doomed as $id => $what ) {
	echo "  delete id=$id  $what\n";
}
printf( "  %d menu item(s)\n", count( $doomed ) );

if ( $apply && $doomed ) {
	$list = implode( ',', array_map( 'intval', array_keys( $doomed ) ) );
	$db->query( "DELETE FROM wp_postmeta WHERE post_id IN ($list)" );
	$db->query( "DELETE FROM wp_term_relationships WHERE object_id IN ($list)" );
	$db->query( "DELETE FROM wp_posts WHERE ID IN ($list)" );
	echo "  deleted\n";
}

/* ---------------------------------------------------------------------------
 * 3. Map links.
 *
 * The demo pointed "Show on map" at the local contact page. Footers 564 and 1100
 * carry one; footer 1603 -- the one the site actually renders -- has none, so a
 * link is added there under the address, or the update would be invisible.
 * ------------------------------------------------------------------------ */
echo "\n3. map links -> $MAP_URL\n";

$res = $db->query( "SELECT ID, post_title, post_content FROM wp_posts WHERE post_type='footer' AND post_status='publish'" );
$n   = 0;

while ( $r = $res->fetch_assoc() ) {
	$c   = $r['post_content'];
	$id  = (int) $r['ID'];
	$was = $c;

	if ( false !== strpos( $c, 'Show on map' ) ) {
		// Repoint the link on the heading that renders "Show on map".
		$c = preg_replace_callback(
			'/\[et_heading[^\]]*\]Show on map\[\/et_heading\]/',
			function ( $m ) use ( $MAP_URL ) {
				if ( preg_match( '/\slink="[^"]*"/', $m[0] ) ) {
					return preg_replace( '/\slink="[^"]*"/', ' link="' . $MAP_URL . '"', $m[0] );
				}
				return preg_replace( '/^\[et_heading/', '[et_heading link="' . $MAP_URL . '"', $m[0] );
			},
			$c
		);
		if ( $c !== $was ) {
			$n++;
			echo "  $id {$r['post_title']}: existing \"Show on map\" repointed\n";
		}
	} elseif ( false !== strpos( $c, 'London Beauty Building' ) ) {
		// No map link here: add one straight after the address icon box.
		$pos = strpos( $c, 'London Beauty Building' );
		$close = strpos( $c, '[/et_icon_box]', $pos );
		if ( false !== $close ) {
			$at  = $close + strlen( '[/et_icon_box]' );
			$eid = 730000 + $id;
			$add = '[et_heading type="p" link="' . $MAP_URL . '" extra_class="map-link" font_weight="400" font_size="14"'
				. ' line_height="22" margin="8,0,0,0" padding="0,0,0,0" element_id="' . $eid . '"'
				. ' element_font="Theme default:400:latin"'
				. ' element_css="#et-heading-' . $eid . ' .text-wrapper {background-color:transparent;padding:0;}'
				. '#et-heading-' . $eid . ' {color:#5A7B2D;font-size:14px;font-weight:400;}'
				. '#et-heading-' . $eid . ' a {color:#5A7B2D;}'
				. '#et-heading-' . $eid . ', #et-heading-' . $eid . ' .text-wrapper {line-height:22px;}'
				. '#et-heading-' . $eid . ' {margin:8px 0px 0px 0px;}"]Show on map[/et_heading]';
			$c   = substr( $c, 0, $at ) . $add . substr( $c, $at );
			$n++;
			echo "  $id {$r['post_title']}: \"Show on map\" added under the address\n";
		}
	}

	if ( $c !== $was && $apply ) {
		$stmt->bind_param( 'si', $c, $id );
		$stmt->execute();
	}
}
printf( "  %d footer(s) updated\n", $n );

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
