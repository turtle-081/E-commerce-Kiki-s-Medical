<?php
/**
 * Follow-up content changes:
 *
 *   - add phone numbers and opening hours to the footers that lack them
 *   - stop the address block from being a mailto: link
 *   - deactivate YayCurrency, so the currency selector disappears
 *
 *   php tools/apply-client-content-2.php          # dry run
 *   php tools/apply-client-content-2.php apply    # write
 *
 * Footer 1603 (the one the site actually renders) only carried an address, an
 * email and a copyright line -- there were no phone or hours blocks to replace,
 * so they are created here, mirroring the styling of the existing icon boxes.
 *
 * YayCurrency is deactivated rather than reconfigured: the store is KES-only now,
 * the plugin's only job was the switcher the client asked to remove, and it also
 * formats prices itself, which silently overrode the WooCommerce settings.
 * WooCommerce's own currency options are already correct, so this is a clean
 * removal. Reactivate from the Plugins screen if a switcher is ever wanted.
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

$PHONES = '+254 704 329 920_br_+254 740 928 234';
$HOURS  = 'Monday - Friday: 8:00 - 18:00_br_Saturday: 8:00 - 16:00';
$TEL    = 'tel:+254704329920';

/** Build the phone + hours markup, with ids derived from the footer id. */
$block = function ( $footerId ) use ( $PHONES, $HOURS, $TEL ) {
	$a = 700000 + $footerId; // phone icon box
	$b = 710000 + $footerId; // hours heading
	$g = 720000 + $footerId; // gaps

	return '[et_gap height="24" element_id="' . $g . '" element_css=".et-gap-' . $g . ' {height:24px;}"]'
		. '[et_icon_box icon="493" icon_size="small-x" icon_position="left" icon_color="#5A7B2D"'
		. ' title="' . $PHONES . '" title_tag="div" font_size="14" line_height="22"'
		. ' link="' . $TEL . '" text_color="#4d4d4d" padding="0,0,0,0" element_id="' . $a . '"'
		. ' element_css="#et-icon-box-' . $a . ' .et-icon-box-title {color:#000000;font-size:14px;font-weight:initial;line-height:22px;}'
		. '#et-icon-box-' . $a . ' .et-icon-box-content {color:#4d4d4d;}'
		. '#et-icon-box-' . $a . ' .et-icon svg * {fill:#5A7B2D !important;}'
		. '#et-icon-box-' . $a . ' {padding:0px 0px 0px 0px;}"][/et_icon_box]'
		. '[et_gap height="24" element_id="' . ( $g + 1 ) . '" element_css=".et-gap-' . ( $g + 1 ) . ' {height:24px;}"]'
		. '[et_heading type="p" font_weight="400" font_size="14" line_height="22" margin="0,0,0,0" padding="0,0,0,0"'
		. ' element_id="' . $b . '" element_font="Theme default:400:latin"'
		. ' element_css="#et-heading-' . $b . ' .text-wrapper {background-color:transparent;padding:0;}'
		. '#et-heading-' . $b . ' {color:#000000;font-size:14px;font-weight:400;}'
		. '#et-heading-' . $b . ', #et-heading-' . $b . ' .text-wrapper {line-height:22px;}'
		. '#et-heading-' . $b . ' {margin:0px 0px 0px 0px;}"]' . $HOURS . '[/et_heading]';
};

echo "1. add phone + hours to footers that lack them\n";

$res  = $db->query( "SELECT ID, post_title, post_content FROM wp_posts WHERE post_type='footer' AND post_status='publish'" );
$stmt = $db->prepare( 'UPDATE wp_posts SET post_content=? WHERE ID=?' );
$n    = 0;

while ( $row = $res->fetch_assoc() ) {
	$c  = $row['post_content'];
	$id = (int) $row['ID'];

	if ( false !== strpos( $c, '+254 704 329 920' ) ) {
		echo "  $id {$row['post_title']}: already has the phone numbers, skipped\n";
		continue;
	}

	// Anchor on the email icon box; the new blocks go straight after it.
	$anchor = 'title="info@kikismedsupplies.com"';
	$pos    = strpos( $c, $anchor );
	if ( false === $pos ) {
		echo "  $id {$row['post_title']}: no email block to anchor to, skipped\n";
		continue;
	}
	$close = strpos( $c, '[/et_icon_box]', $pos );
	if ( false === $close ) {
		echo "  $id {$row['post_title']}: email block not closed, skipped\n";
		continue;
	}
	$at  = $close + strlen( '[/et_icon_box]' );
	$new = substr( $c, 0, $at ) . $block( $id ) . substr( $c, $at );

	$n++;
	echo "  $id {$row['post_title']}: phone + hours inserted\n";
	if ( $apply ) {
		$stmt->bind_param( 'si', $new, $id );
		$stmt->execute();
	}
}
echo "  $n footer(s) updated\n";

echo "\n2. address block should not be a mailto: link\n";
$res = $db->query( "SELECT ID, post_title, post_content FROM wp_posts WHERE post_type='footer' AND post_content LIKE '%London Beauty Building%'" );
$n   = 0;
while ( $row = $res->fetch_assoc() ) {
	// Only inside the icon box whose title is the address.
	$new = preg_replace_callback(
		'/\[et_icon_box[^\]]*\]/',
		function ( $m ) {
			if ( false === strpos( $m[0], 'London Beauty Building' ) ) {
				return $m[0];
			}
			return preg_replace( '/\slink="mailto:[^"]*"/', ' link=""', $m[0] );
		},
		$row['post_content']
	);
	if ( $new === $row['post_content'] ) {
		continue;
	}
	$n++;
	echo "  {$row['ID']} {$row['post_title']}: mailto removed from address block\n";
	if ( $apply ) {
		$stmt->bind_param( 'si', $new, $row['ID'] );
		$stmt->execute();
	}
}
echo "  $n footer(s) updated\n";

echo "\n3. deactivate YayCurrency\n";
$row    = $db->query( "SELECT option_value FROM wp_options WHERE option_name='active_plugins'" )->fetch_row();
$active = unserialize( $row[0] );
$target = 'yaycurrency/yay-currency.php';

if ( ! in_array( $target, $active, true ) ) {
	echo "  already inactive\n";
} else {
	echo '  removing ' . $target . ' (' . count( $active ) . ' active plugins -> ' . ( count( $active ) - 1 ) . ")\n";
	if ( $apply ) {
		$active = array_values( array_diff( $active, array( $target ) ) );
		$new    = serialize( $active );
		$s      = $db->prepare( "UPDATE wp_options SET option_value=? WHERE option_name='active_plugins'" );
		$s->bind_param( 's', $new );
		$s->execute();
		echo "  deactivated\n";
	}
}

if ( $apply ) {
	$db->query(
		"DELETE FROM wp_options WHERE option_name LIKE '_transient_enovathemes-%'
		 OR option_name LIKE '_transient_timeout_enovathemes-%'
		 OR option_name LIKE '_transient_yay%'
		 OR option_name LIKE '_transient_wc_%'
		 OR option_name IN ('_transient_dynamic-styles-cached','_transient_timeout_dynamic-styles-cached')"
	);
	printf( "\ncleared %d cache row(s)\n", $db->affected_rows );
	echo "done\n";
} else {
	echo "\nDRY RUN - nothing written. Pass 'apply' to write.\n";
}
