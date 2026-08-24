<?php
/**
 * Switch the store to Kenyan shillings and convert existing prices.
 *
 *   php tools/convert-currency-kes.php               # dry run at the default rate
 *   php tools/convert-currency-kes.php apply         # write
 *   php tools/convert-currency-kes.php apply 135     # write using a different rate
 *
 * Changing woocommerce_currency alone only swaps the symbol: a product stored as
 * 145.55 would display as "KSh 145.55", roughly a hundredth of its intended
 * value. So the stored numbers are converted too.
 *
 * The rate below is an approximation, not a client-approved figure. Prices are
 * rounded to the nearest 10 shillings, and _price is recomputed from the regular
 * and sale prices rather than converted on its own, so a rounded sale price can
 * never end up at or above its rounded regular price.
 *
 * TAKE A DATABASE BACKUP FIRST -- this rewrites every price in the catalogue.
 */

$apply = ( ( $argv[1] ?? '' ) === 'apply' );
$RATE  = isset( $argv[2] ) ? (float) $argv[2] : 129.0;
$port  = isset( $argv[3] ) ? (int) $argv[3] : 10004;
$ROUND = 10; // round to the nearest N shillings

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

printf( "rate: 1 USD = %s KES, rounding to the nearest %d\n\n", $RATE, $ROUND );

$conv = function ( $v ) use ( $RATE, $ROUND ) {
	if ( '' === $v || null === $v || ! is_numeric( $v ) ) {
		return null;
	}
	$n = (float) $v * $RATE;
	$r = (int) ( round( $n / $ROUND ) * $ROUND );
	return max( $ROUND, $r );
};

// Collect every product / variation that has any price meta.
$ids = array();
$res = $db->query( "SELECT DISTINCT post_id FROM wp_postmeta WHERE meta_key IN ('_price','_regular_price','_sale_price') AND meta_value <> ''" );
while ( $row = $res->fetch_row() ) {
	$ids[] = (int) $row[0];
}

$get = $db->prepare( 'SELECT meta_key, meta_value FROM wp_postmeta WHERE post_id=? AND meta_key IN (?,?,?)' );
$set = $db->prepare( 'UPDATE wp_postmeta SET meta_value=? WHERE post_id=? AND meta_key=?' );

$changed  = 0;
$products = 0;
$samples  = array();

foreach ( $ids as $id ) {
	$k1 = '_price';
	$k2 = '_regular_price';
	$k3 = '_sale_price';
	$get->bind_param( 'isss', $id, $k1, $k2, $k3 );
	$get->execute();
	$r    = $get->get_result();
	$meta = array();
	while ( $m = $r->fetch_assoc() ) {
		$meta[ $m['meta_key'] ] = $m['meta_value'];
	}

	$regular = $conv( $meta['_regular_price'] ?? null );
	$sale    = $conv( $meta['_sale_price'] ?? null );

	// A rounded sale price must stay below its rounded regular price.
	if ( null !== $sale && null !== $regular && $sale >= $regular ) {
		$sale = max( $ROUND, $regular - $ROUND );
	}

	// Derive _price the way WooCommerce does, rather than converting it alone.
	if ( null !== $regular ) {
		$price = ( null !== $sale ) ? $sale : $regular;
	} else {
		$price = $conv( $meta['_price'] ?? null );
	}

	$updates = array();
	if ( null !== $regular && isset( $meta['_regular_price'] ) ) {
		$updates['_regular_price'] = (string) $regular;
	}
	if ( null !== $sale && isset( $meta['_sale_price'] ) && '' !== $meta['_sale_price'] ) {
		$updates['_sale_price'] = (string) $sale;
	}
	if ( null !== $price && isset( $meta['_price'] ) ) {
		$updates['_price'] = (string) $price;
	}
	if ( ! $updates ) {
		continue;
	}

	$products++;
	if ( count( $samples ) < 6 ) {
		$samples[] = sprintf(
			'  #%-6d %-10s -> %-10s %s',
			$id,
			( $meta['_regular_price'] ?? $meta['_price'] ?? '?' ),
			( $updates['_regular_price'] ?? $updates['_price'] ?? '?' ),
			isset( $updates['_sale_price'] ) ? '(sale ' . ( $meta['_sale_price'] ?? '' ) . ' -> ' . $updates['_sale_price'] . ')' : ''
		);
	}

	foreach ( $updates as $key => $val ) {
		$changed++;
		if ( $apply ) {
			$set->bind_param( 'sis', $val, $id, $key );
			$set->execute();
		}
	}
}

echo "sample conversions:\n" . implode( "\n", $samples ) . "\n\n";
printf( "%s: %d price value(s) across %d product(s)/variation(s)\n", $apply ? 'CONVERTED' : 'DRY RUN (nothing written)', $changed, $products );

$OPTIONS = array(
	'woocommerce_currency'            => 'KES',
	'woocommerce_price_num_decimals'  => '0',      // shillings are not quoted in cents
	'woocommerce_currency_pos'        => 'left_space', // "KSh 18,780"
);

echo "\nstore options:\n";
foreach ( $OPTIONS as $k => $v ) {
	$r   = $db->query( "SELECT option_value FROM wp_options WHERE option_name='$k' LIMIT 1" );
	$cur = $r && $r->num_rows ? $r->fetch_row()[0] : '(unset)';
	printf( "  %-34s %-12s -> %s\n", $k, $cur, $v );
}

if ( ! $apply ) {
	echo "\npass 'apply' to write\n";
	exit( 0 );
}

foreach ( $OPTIONS as $k => $v ) {
	$s = $db->prepare( 'UPDATE wp_options SET option_value=? WHERE option_name=?' );
	$s->bind_param( 'ss', $v, $k );
	$s->execute();
}

// The YayCurrency switcher holds one entry mirroring the base currency, and it
// formats prices itself -- so its own position/decimal settings have to match
// the WooCommerce ones or they silently win.
$db->query( "UPDATE wp_posts SET post_title='KES' WHERE post_type='yay-currency-manage' AND post_title='USD'" );
$db->query( "UPDATE wp_postmeta SET meta_value='0' WHERE meta_key='number_decimal' AND post_id IN (SELECT ID FROM wp_posts WHERE post_type='yay-currency-manage')" );
$db->query( "UPDATE wp_postmeta SET meta_value='left_space' WHERE meta_key='currency_position' AND post_id IN (SELECT ID FROM wp_posts WHERE post_type='yay-currency-manage')" );
echo "\nupdated the YayCurrency entry to KES, 0 decimals, symbol + space\n";

// Price-dependent caches.
$db->query( "DELETE FROM wp_options WHERE option_name LIKE '_transient_wc_%' OR option_name LIKE '_transient_timeout_wc_%' OR option_name LIKE '_transient_yay%' OR option_name LIKE '_transient_timeout_yay%'" );
printf( "cleared %d price/currency cache row(s)\n", $db->affected_rows );
$db->query( "DELETE FROM wp_wc_product_meta_lookup" );
echo "emptied wc_product_meta_lookup so WooCommerce rebuilds it\n";
