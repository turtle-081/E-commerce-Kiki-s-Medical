<?php
/**
 * Apply the client's content changes: top-bar notice, header cleanup, menu
 * pruning, logo swap and footer details.
 *
 *   php tools/apply-client-content.php          # dry run
 *   php tools/apply-client-content.php apply    # write
 *
 * Take a database backup first. This edits header and footer layouts, deletes
 * navigation items and repoints the logo attachment.
 *
 * The logo file itself must already be at wp-content/uploads/kiki-logo.png.
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

$log = function ( $msg ) {
	echo "  $msg\n";
};

/* ---------------------------------------------------------------------------
 * 1. Top-bar notice.
 *
 * The COVID slogan was removed earlier, so the element is re-created with the
 * client's text. It goes back where it was: first child of the top row's column,
 * ahead of the login toggle. "_br_" is the theme's line-break token; not needed
 * for a single line.
 * ------------------------------------------------------------------------ */
$NOTICE = 'Free delivery within Nairobi';

echo "\n1. top-bar notice -> \"$NOTICE\"\n";
$res  = $db->query( "SELECT ID, post_title, post_content FROM wp_posts WHERE post_type='header' AND post_status='publish'" );
$stmt = $db->prepare( 'UPDATE wp_posts SET post_content=? WHERE ID=?' );
$n    = 0;

while ( $row = $res->fetch_assoc() ) {
	$c = $row['post_content'];

	if ( false !== strpos( $c, 'et_header_slogan' ) ) {
		$log( "{$row['ID']} {$row['post_title']}: already has a slogan, skipped" );
		continue;
	}
	// Insert right after the opening [vc_column] of the first row.
	$eid     = 500000 + (int) $row['ID'];
	$slogan  = '[et_header_slogan align="left" margin="0,0,0,0" element_id="' . $eid . '"'
		. ' element_css="#header-slogan-' . $eid . ' {margin:0px 0px 0px 0px;}"]'
		. '<span style="color: #ffffff; font-weight: 500; font-size: 14px;">' . $NOTICE . '</span>'
		. '[/et_header_slogan]';
	$new = preg_replace( '/(\[vc_column\])/', '$1' . str_replace( '$', '\\$', $slogan ), $c, 1, $count );

	if ( ! $count ) {
		$log( "{$row['ID']} {$row['post_title']}: no [vc_column] found, skipped" );
		continue;
	}
	$n++;
	$log( "{$row['ID']} {$row['post_title']}: notice inserted" );
	if ( $apply ) {
		$stmt->bind_param( 'si', $new, $row['ID'] );
		$stmt->execute();
	}
}
$log( "$n header(s) updated" );

/* ---------------------------------------------------------------------------
 * 2. Remove the language and currency switchers from the top bar.
 * ------------------------------------------------------------------------ */
echo "\n2. remove language + currency switchers\n";
$res = $db->query( "SELECT ID, post_title, post_content FROM wp_posts WHERE post_type='header' AND post_status='publish'" );
$n   = 0;

while ( $row = $res->fetch_assoc() ) {
	$c       = $row['post_content'];
	$removed = 0;
	// These are self-closing shortcodes, so a single non-greedy match is enough.
	foreach ( array( 'et_language_switcher', 'et_currency_switcher' ) as $sc ) {
		$c = preg_replace( '/\[' . $sc . '\b[^\]]*\]/', '', $c, -1, $cnt );
		$removed += $cnt;
	}
	if ( ! $removed ) {
		continue;
	}
	$n++;
	$log( "{$row['ID']} {$row['post_title']}: removed $removed switcher element(s)" );
	if ( $apply ) {
		$stmt->bind_param( 'si', $c, $row['ID'] );
		$stmt->execute();
	}
}
$log( "$n header(s) updated" );

/* ---------------------------------------------------------------------------
 * 3. Delete the Elements and Features items from the header menus.
 *
 * Children must go too. A menu item whose parent is deleted is not removed by
 * WordPress -- it is promoted to the top level, so the sub-items would reappear
 * as new top-level entries.
 * ------------------------------------------------------------------------ */
echo "\n3. delete Elements / Features from header menus\n";

$HEADER_MENUS = array( 'Header menu', 'Header menu boxy megamenu', 'Mobile header' );
$in           = "'" . implode( "','", array_map( array( $db, 'real_escape_string' ), $HEADER_MENUS ) ) . "'";

$res = $db->query(
	"SELECT p.ID, t.name AS menu,
	        (SELECT meta_value FROM wp_postmeta WHERE post_id=p.ID AND meta_key='_menu_item_object_id') AS objid
	 FROM wp_posts p
	 JOIN wp_term_relationships tr ON tr.object_id = p.ID
	 JOIN wp_term_taxonomy tt ON tt.term_taxonomy_id = tr.term_taxonomy_id AND tt.taxonomy='nav_menu'
	 JOIN wp_terms t ON t.term_id = tt.term_id
	 WHERE p.post_type='nav_menu_item' AND t.name IN ($in)
	 HAVING objid IN ('343','345')"
);

$doomed = array();
while ( $row = $res->fetch_assoc() ) {
	$doomed[ (int) $row['ID'] ] = $row['menu'] . ' (page ' . $row['objid'] . ')';
}

// Pull in descendants.
$frontier = array_keys( $doomed );
while ( $frontier ) {
	$list = implode( ',', array_map( 'intval', $frontier ) );
	$r    = $db->query( "SELECT post_id FROM wp_postmeta WHERE meta_key='_menu_item_menu_item_parent' AND meta_value IN ($list)" );
	$next = array();
	while ( $x = $r->fetch_row() ) {
		$id = (int) $x[0];
		if ( ! isset( $doomed[ $id ] ) ) {
			$doomed[ $id ] = 'child';
			$next[]        = $id;
		}
	}
	$frontier = $next;
}

$log( count( $doomed ) . ' menu item(s) to delete' );
foreach ( array_slice( $doomed, 0, 8, true ) as $id => $why ) {
	$log( "  id=$id  $why" );
}
if ( count( $doomed ) > 8 ) {
	$log( '  ... and ' . ( count( $doomed ) - 8 ) . ' more' );
}

if ( $apply && $doomed ) {
	$list = implode( ',', array_map( 'intval', array_keys( $doomed ) ) );
	$db->query( "DELETE FROM wp_postmeta WHERE post_id IN ($list)" );
	$db->query( "DELETE FROM wp_term_relationships WHERE object_id IN ($list)" );
	$db->query( "DELETE FROM wp_posts WHERE ID IN ($list)" );
	$log( 'deleted' );
}

/* ---------------------------------------------------------------------------
 * 4. Logo.
 *
 * A new attachment is created rather than overwriting the old one, so the demo
 * logo stays intact and the swap is a one-line revert.
 * ------------------------------------------------------------------------ */
echo "\n4. logo\n";
$OLD_LOGO_ID = 335;
$file        = $root . '/app/public/wp-content/uploads/kiki-logo.png';

if ( ! is_file( $file ) ) {
	$log( 'kiki-logo.png missing from uploads - skipping logo swap' );
} else {
	$r      = $db->query( "SELECT ID FROM wp_posts WHERE post_type='attachment' AND guid LIKE '%kiki-logo.png' LIMIT 1" );
	$newId  = $r && $r->num_rows ? (int) $r->fetch_row()[0] : 0;
	$size   = @getimagesize( $file );
	$r2     = $db->query( "SELECT COUNT(*) FROM wp_posts WHERE post_content LIKE '%\"$OLD_LOGO_ID\"%'" );
	$usedBy = (int) $r2->fetch_row()[0];

	$log( sprintf( 'file %dx%d, referenced by %d post(s) via attachment %d', $size[0] ?? 0, $size[1] ?? 0, $usedBy, $OLD_LOGO_ID ) );

	if ( $apply ) {
		if ( ! $newId ) {
			$guid = 'http://client1.local/wp-content/uploads/kiki-logo.png';
			// to_ping / pinged / post_content_filtered are NOT NULL with no default,
			// so they have to be supplied explicitly under strict SQL mode.
			$db->query(
				"INSERT INTO wp_posts (post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt,
				 post_status, comment_status, ping_status, post_password, post_name, to_ping, pinged,
				 post_modified, post_modified_gmt, post_content_filtered, post_parent,
				 guid, menu_order, post_type, post_mime_type)
				 VALUES (1, NOW(), UTC_TIMESTAMP(), '', 'Kiki logo', '', 'inherit', 'closed', 'closed', '', 'kiki-logo', '', '',
				 NOW(), UTC_TIMESTAMP(), '', 0, '$guid', 0, 'attachment', 'image/png')"
			);
			$newId = $db->insert_id;
			$meta  = serialize( array(
				'width'  => $size[0] ?? 0,
				'height' => $size[1] ?? 0,
				'file'   => 'kiki-logo.png',
				'sizes'  => array(),
				'image_meta' => array(),
			) );
			$s = $db->prepare( "INSERT INTO wp_postmeta (post_id, meta_key, meta_value) VALUES (?, '_wp_attached_file', 'kiki-logo.png'), (?, '_wp_attachment_metadata', ?)" );
			$s->bind_param( 'iis', $newId, $newId, $meta );
			$s->execute();
			$log( "created attachment $newId" );
		} else {
			$log( "reusing existing attachment $newId" );
		}

		// Repoint every logo/sticky_logo reference.
		$oldAttr = 'logo="' . $OLD_LOGO_ID . '"';
		$newAttr = 'logo="' . $newId . '"';
		// Matching on logo="N" also covers sticky_logo="N", since that string ends
		// with the same characters.
		$s = $db->prepare( 'UPDATE wp_posts SET post_content = REPLACE(post_content, ?, ?) WHERE post_content LIKE ?' );
		$like = '%' . $oldAttr . '%';
		$s->bind_param( 'sss', $oldAttr, $newAttr, $like );
		$s->execute();
		$log( "repointed logo references to $newId ({$s->affected_rows} post(s))" );
	}
}

/* ---------------------------------------------------------------------------
 * 5. Footer details.
 * ------------------------------------------------------------------------ */
echo "\n5. footer details\n";

$FOOTER_REPLACEMENTS = array(
	'70 Washington Square South,_br_New York, NY 10012, United States'
		=> 'London Beauty Building, 4th Floor, Suite A6_br_Taveta Road, Nairobi CBD',
	'9876 788 - HGGGY -888'
		=> '+254 704 329 920_br_+254 740 928 234',
	'Monday - Friday: 9:00 - 20:00_br_Saturday: 9:00 - 15:00'
		=> 'Monday - Friday: 8:00 - 18:00_br_Saturday: 8:00 - 16:00',
	'Copyright 2020 Propharm. All Rights Reserved'
		=> '© 2026 Kiki&#039;s Medical Equipment and Hospital Supplies Limited. All rights reserved. · Reg. PVT-Y2ULB55R',
	'inbox@propharm.com'
		=> 'info@kikismedsupplies.com',
);

$res = $db->query( "SELECT ID, post_type, post_title, post_content FROM wp_posts WHERE post_type IN ('footer','header') AND post_status='publish'" );
$n   = 0;

while ( $row = $res->fetch_assoc() ) {
	$c    = $row['post_content'];
	$hits = 0;
	foreach ( $FOOTER_REPLACEMENTS as $from => $to ) {
		$cnt = substr_count( $c, $from );
		if ( $cnt ) {
			$c     = str_replace( $from, $to, $c );
			$hits += $cnt;
		}
	}
	if ( ! $hits ) {
		continue;
	}
	$n++;
	$log( "{$row['ID']} ({$row['post_type']}) {$row['post_title']}: $hits replacement(s)" );
	if ( $apply ) {
		$stmt->bind_param( 'si', $c, $row['ID'] );
		$stmt->execute();
	}
}
$log( "$n post(s) updated" );

/* ------------------------------------------------------------------------ */
if ( $apply ) {
	$db->query(
		"DELETE FROM wp_options WHERE option_name LIKE '_transient_enovathemes-%'
		 OR option_name LIKE '_transient_timeout_enovathemes-%'
		 OR option_name LIKE '_transient_et_icon_%'
		 OR option_name IN ('_transient_dynamic-styles-cached','_transient_timeout_dynamic-styles-cached')"
	);
	printf( "\ncleared %d cache row(s)\n", $db->affected_rows );
	echo "done - load a front-end page once to rebuild\n";
} else {
	echo "\nDRY RUN - nothing written. Pass 'apply' to write.\n";
}
