<?php
/**
 * Remove LiteSpeed Cache.
 *
 *   php tools/remove-litespeed.php          # dry run
 *   php tools/remove-litespeed.php apply    # write
 *
 * Why it goes rather than gets reconfigured: the server is nginx, and LSCache
 * cannot page-cache without a LiteSpeed server. Discovery confirmed it installed
 * neither advanced-cache.php nor object-cache.php, so its page cache and object
 * cache never functioned at all -- it was reporting "cache enabled, TTL 7 days"
 * while caching nothing. Keeping it would also breach working rule 5 once the
 * nginx FastCGI cache goes in at Phase 2.
 *
 * The one thing it was really doing is image lazy-loading. WordPress core has
 * done that natively since 5.5 and, unlike LSCache here, core deliberately skips
 * the first in-viewport image so the LCP candidate is not deferred. So removing
 * it should improve LCP rather than hurt it -- verified after the change.
 *
 * This does NOT delete the plugin files; that is left to `wp plugin delete` so
 * the removal is reversible from the plugin screen until you are satisfied.
 */

$apply = ( ( $argv[1] ?? '' ) === 'apply' );
$port  = isset( $argv[2] ) ? (int) $argv[2] : 10004;

$root   = dirname( __DIR__ );
$public = $root . '/app/public';
$config = @file_get_contents( $public . '/wp-config.php' );
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

$PLUGIN = 'litespeed-cache/litespeed-cache.php';

/* 1. deactivate ---------------------------------------------------------- */
echo "1. deactivate the plugin\n";
$row    = $db->query( "SELECT option_value FROM wp_options WHERE option_name='active_plugins'" )->fetch_row();
$active = unserialize( $row[0] );
if ( in_array( $PLUGIN, $active, true ) ) {
	printf( "  removing from active_plugins (%d -> %d)\n", count( $active ), count( $active ) - 1 );
	if ( $apply ) {
		$new = serialize( array_values( array_diff( $active, array( $PLUGIN ) ) ) );
		$s   = $db->prepare( "UPDATE wp_options SET option_value=? WHERE option_name='active_plugins'" );
		$s->bind_param( 's', $new );
		$s->execute();
	}
} else {
	echo "  already inactive\n";
}

/* 2. options ------------------------------------------------------------- */
echo "\n2. plugin options\n";
$x = $db->query( "SELECT COUNT(*) n, COALESCE(SUM(LENGTH(option_value)),0) b FROM wp_options WHERE option_name LIKE 'litespeed%'" )->fetch_assoc();
printf( "  %s option row(s), %s KB -- all autoloaded\n", $x['n'], number_format( $x['b'] / 1024, 1 ) );
if ( $apply && $x['n'] ) {
	$db->query( "DELETE FROM wp_options WHERE option_name LIKE 'litespeed%'" );
	printf( "  deleted %d\n", $db->affected_rows );
}

/* 3. WP_CACHE ------------------------------------------------------------ */
echo "\n3. WP_CACHE in wp-config.php\n";
if ( preg_match( "/^define\(\s*'WP_CACHE'.*$/m", $config ) ) {
	echo "  present -- removing (it is meaningless with no advanced-cache.php drop-in,\n";
	echo "  and leaving it set makes it look as though a page cache is running)\n";
	if ( $apply ) {
		copy( $public . '/wp-config.php', $public . '/wp-config.php.bak-litespeed' );
		$new = preg_replace( "/^define\(\s*'WP_CACHE'.*\R/m", '', $config );
		file_put_contents( $public . '/wp-config.php', $new );
		echo "  removed (original kept at wp-config.php.bak-litespeed)\n";
	}
} else {
	echo "  not present\n";
}

/* 4. .htaccess ----------------------------------------------------------- */
echo "\n4. .htaccess rules\n";
$ht = $public . '/.htaccess';
if ( is_file( $ht ) ) {
	$c    = file_get_contents( $ht );
	$orig = $c;
	// Both blocks the plugin writes.
	$c = preg_replace( '/# BEGIN LSCACHE.*?# END LSCACHE\s*/s', '', $c );
	$c = preg_replace( '/# BEGIN NON_LSCACHE.*?# END NON_LSCACHE\s*/s', '', $c );
	if ( $c !== $orig ) {
		printf( "  removing LSCACHE blocks (%d -> %d bytes)\n", strlen( $orig ), strlen( $c ) );
		echo "  note: nginx does not read .htaccess, so these were already inert\n";
		if ( $apply ) {
			file_put_contents( $ht, $c );
		}
	} else {
		echo "  no LSCACHE blocks found\n";
	}
} else {
	echo "  no .htaccess\n";
}

/* 5. cache directory ----------------------------------------------------- */
echo "\n5. wp-content/litespeed/\n";
$dir = $public . '/wp-content/litespeed';
if ( is_dir( $dir ) ) {
	echo "  present -- removing\n";
	if ( $apply ) {
		$it = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $dir, FilesystemIterator::SKIP_DOTS ), RecursiveIteratorIterator::CHILD_FIRST );
		foreach ( $it as $f ) {
			$f->isDir() ? @rmdir( $f->getPathname() ) : @unlink( $f->getPathname() );
		}
		@rmdir( $dir );
		echo is_dir( $dir ) ? "  could not fully remove\n" : "  removed\n";
	}
} else {
	echo "  absent\n";
}

if ( ! $apply ) {
	echo "\nDRY RUN - nothing written. Pass 'apply' to write.\n";
	exit( 0 );
}

echo "\nverifying:\n";
printf( "  litespeed options remaining : %s\n", $db->query( "SELECT COUNT(*) FROM wp_options WHERE option_name LIKE 'litespeed%'" )->fetch_row()[0] );
$active = unserialize( $db->query( "SELECT option_value FROM wp_options WHERE option_name='active_plugins'" )->fetch_row()[0] );
printf( "  plugin active               : %s\n", in_array( $PLUGIN, $active, true ) ? 'YES' : 'no' );
printf( "  active plugin count         : %d\n", count( $active ) );
printf( "  WP_CACHE still defined      : %s\n", preg_match( "/define\(\s*'WP_CACHE'/", file_get_contents( $public . '/wp-config.php' ) ) ? 'YES' : 'no' );
echo "\ndone. Plugin files are still on disk -- delete from the Plugins screen when satisfied.\n";
