<?php
/**
 * Apply (or roll back) the client's brand palette in the propharm theme options.
 *
 * The palette lives in a single serialised wp_options row, so it is not something
 * git can track. This script is the record of what was changed, and makes it
 * repeatable on another machine or after a database restore.
 *
 *   php tools/apply-brand-palette.php show      # print current vs target
 *   php tools/apply-brand-palette.php apply     # write, backing up first
 *   php tools/apply-brand-palette.php restore   # put the backup back
 *
 * Credentials are read from app/public/wp-config.php so nothing is hardcoded.
 * DB_HOST there is "localhost", but Local runs MySQL on a non-default port, so
 * pass it explicitly if 10004 is not right for your install:
 *
 *   php tools/apply-brand-palette.php apply 10004
 *
 * See PATCHES.md for the rationale, including why the interactive green differs
 * from the brand green.
 */

$mode = $argv[1] ?? 'show';
$port = isset( $argv[2] ) ? (int) $argv[2] : 10004;

$root       = dirname( __DIR__ );
$configPath = $root . '/app/public/wp-config.php';
$backupPath = $root . '/tools/brand-palette-backup.txt';

if ( ! is_file( $configPath ) ) {
	fwrite( STDERR, "wp-config.php not found at $configPath\n" );
	exit( 1 );
}

$config = file_get_contents( $configPath );
$conf   = array();
foreach ( array( 'DB_NAME', 'DB_USER', 'DB_PASSWORD', 'DB_HOST' ) as $k ) {
	if ( preg_match( "/define\(\s*'$k'\s*,\s*'([^']*)'/", $config, $m ) ) {
		$conf[ $k ] = $m[1];
	}
}
if ( count( $conf ) < 4 ) {
	fwrite( STDERR, "could not read DB constants from wp-config.php\n" );
	exit( 1 );
}
$host = preg_replace( '/:\d+$/', '', $conf['DB_HOST'] );
if ( 'localhost' === $host ) {
	$host = '127.0.0.1'; // mysqli would otherwise try a named pipe / default port
}

$db = mysqli_init();
$db->options( MYSQLI_OPT_CONNECT_TIMEOUT, 5 );
if ( ! @$db->real_connect( $host, $conf['DB_USER'], $conf['DB_PASSWORD'], $conf['DB_NAME'], $port ) ) {
	fwrite( STDERR, "connect failed on $host:$port - " . mysqli_connect_error() . "\n" );
	exit( 1 );
}
$db->set_charset( 'utf8mb4' );

$OPTION = 'propharm_enovathemes';

/** The palette. Keep in sync with PATCHES.md and assets/css/brand.css. */
$TARGET = array(
	'main-color'     => '#80AF40', // client brand green - identity surfaces
	'area-color'     => '#F5F9F0', // 92% white tint of the brand green
	'sale-color'     => '#DC2222', // client brand red
	'discount-color' => '#DC2222',
);
$TARGET_BUTTON = array(
	'regular' => '#5A7B2D', // darkened brand green; white text 4.88:1 (AA)
	'hover'   => '#4D6926', // darker still; white text 6.24:1
);

/**
 * Form field colours. The focus border deliberately uses the darker green, not
 * #80AF40: a focus indicator is a UI component and needs 3:1 against what is
 * around it, and the brand green manages only 2.58:1 on white.
 */
$TARGET_FORM = array(
	'form-back-color'   => array( 'regular' => '#F5F9F0' ),
	'form-border-color' => array(
		'regular' => '#F5F9F0',
		'hover'   => '#5A7B2D',
	),
);

/**
 * Typography. The client asked for black text, replacing the demo's navy
 * headings (#184363) and grey body copy (#56778f). Black is 21:1 on white.
 */
$TARGET_TYPO = array(
	'main-typo'       => array( 'color' => '#000000' ),
	'headings-typo'   => array( 'color' => '#000000' ),
	'form-text-color' => array(
		'regular' => '#000000',
		'hover'   => '#000000',
	),
);

$res = $db->query( "SELECT option_value FROM wp_options WHERE option_name='$OPTION' LIMIT 1" );
if ( ! $res || ! $res->num_rows ) {
	fwrite( STDERR, "option '$OPTION' not found - is the propharm theme installed?\n" );
	exit( 1 );
}
$raw = $res->fetch_row()[0];

/** Write a serialised option back and drop the theme's generated-CSS cache. */
$write = function ( $serialised ) use ( $db, $OPTION ) {
	$stmt = $db->prepare( "UPDATE wp_options SET option_value=? WHERE option_name='$OPTION'" );
	$stmt->bind_param( 's', $serialised );
	$stmt->execute();
	$rows = $stmt->affected_rows;
	// Same invalidation the theme does on its own Redux save hook. The transient
	// is stored with no expiry, so without this the old CSS would never rebuild.
	$db->query( "DELETE FROM wp_options WHERE option_name IN ('_transient_dynamic-styles-cached','_transient_timeout_dynamic-styles-cached')" );
	return $rows;
};

if ( 'restore' === $mode ) {
	if ( ! is_file( $backupPath ) ) {
		fwrite( STDERR, "no backup at $backupPath\n" );
		exit( 1 );
	}
	printf( "restored %d row(s) from backup; CSS cache cleared\n", $write( file_get_contents( $backupPath ) ) );
	echo "load any front-end page once to regenerate the stylesheet\n";
	exit( 0 );
}

$opts = unserialize( $raw );
if ( ! is_array( $opts ) ) {
	fwrite( STDERR, "option did not unserialize to an array - aborting\n" );
	exit( 1 );
}

echo "option                current    target\n";
echo "--------------------  ---------  ---------\n";
foreach ( $TARGET as $k => $v ) {
	printf( "%-20s  %-9s  %s\n", $k, is_string( $opts[ $k ] ?? null ) ? $opts[ $k ] : '(unset)', $v );
}
foreach ( $TARGET_BUTTON as $state => $v ) {
	printf( "%-20s  %-9s  %s\n", "form-button-back.$state", $opts['form-button-back'][ $state ] ?? '(unset)', $v );
}
foreach ( $TARGET_FORM as $key => $states ) {
	foreach ( $states as $state => $v ) {
		printf( "%-20s  %-9s  %s\n", "$key.$state", $opts[ $key ][ $state ] ?? '(unset)', $v );
	}
}
foreach ( $TARGET_TYPO as $key => $states ) {
	foreach ( $states as $state => $v ) {
		printf( "%-20s  %-9s  %s\n", "$key.$state", $opts[ $key ][ $state ] ?? '(unset)', $v );
	}
}

if ( 'apply' !== $mode ) {
	echo "\ndry run - pass 'apply' to write\n";
	exit( 0 );
}

if ( ! is_file( $backupPath ) ) {
	file_put_contents( $backupPath, $raw );
	echo "\nbacked up previous options -> tools/" . basename( $backupPath ) . "\n";
} else {
	echo "\nbackup already exists, left untouched (it holds the pre-brand palette)\n";
}

foreach ( $TARGET as $k => $v ) {
	$opts[ $k ] = $v;
}
foreach ( $TARGET_BUTTON as $state => $v ) {
	$opts['form-button-back'][ $state ] = $v;
}
foreach ( array( $TARGET_FORM, $TARGET_TYPO ) as $group ) {
	foreach ( $group as $key => $states ) {
		foreach ( $states as $state => $v ) {
			$opts[ $key ][ $state ] = $v;
		}
	}
}

$before = count( $opts );
printf( "updated %d row(s); CSS cache cleared\n", $write( serialize( $opts ) ) );

$res  = $db->query( "SELECT option_value FROM wp_options WHERE option_name='$OPTION' LIMIT 1" );
$back = unserialize( $res->fetch_row()[0] );
echo "verified read-back:\n";
foreach ( array_keys( $TARGET ) as $k ) {
	printf( "  %-20s %s\n", $k, $back[ $k ] );
}
printf( "  %-20s %s / %s\n", 'form-button-back', $back['form-button-back']['regular'], $back['form-button-back']['hover'] );
printf( "  option keys: %d (was %d)%s\n", count( $back ), $before, count( $back ) === $before ? ' - intact' : ' - MISMATCH' );
echo "\nload any front-end page once to regenerate the stylesheet\n";
