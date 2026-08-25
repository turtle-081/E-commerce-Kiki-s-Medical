<?php
/**
 * Make the homepage use the same footer as every other page.
 *
 *   php tools/fix-homepage-footer.php          # dry run
 *   php tools/fix-homepage-footer.php apply    # write
 *
 * The theme's default footer is set in the theme options (footer-id), and every
 * page carries enovathemes_addons_footer = "inherit" so they all follow it. The
 * homepage was the exception: it had an explicit override to a different footer
 * layout, left over from the demo import, so it rendered a different footer from
 * the rest of the site.
 *
 * Setting it back to "inherit" means it follows the theme option like everything
 * else, and any future change to the default applies everywhere at once.
 */

$apply = ( ( $argv[1] ?? '' ) === 'apply' );
$port  = isset( $argv[2] ) ? (int) $argv[2] : 10004;

$META = 'enovathemes_addons_footer';

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

$opts    = unserialize( $db->query( "SELECT option_value FROM wp_options WHERE option_name='propharm_enovathemes'" )->fetch_row()[0] );
$default = $opts['footer-id'] ?? '';
$front   = (int) $db->query( "SELECT option_value FROM wp_options WHERE option_name='page_on_front'" )->fetch_row()[0];

printf( "theme default footer: %s\n", $default ?: '(unset)' );
printf( "front page: %d\n\n", $front );

// Anything that is not "inherit" is overriding the site-wide footer.
$res = $db->query(
	"SELECT pm.post_id, pm.meta_value, p.post_title, p.post_type
	 FROM wp_postmeta pm
	 JOIN wp_posts p ON p.ID = pm.post_id
	 WHERE pm.meta_key = '$META' AND pm.meta_value <> 'inherit' AND pm.meta_value <> ''
	   AND p.post_status = 'publish'"
);

$found = 0;
$stmt  = $db->prepare( "UPDATE wp_postmeta SET meta_value='inherit' WHERE post_id=? AND meta_key='$META'" );

while ( $row = $res->fetch_assoc() ) {
	$found++;
	$isFront = ( (int) $row['post_id'] === $front ) ? '  <- front page' : '';
	printf(
		"  %-6s %-8s %-16s overrides footer to %s%s\n",
		$row['post_id'],
		$row['post_type'],
		substr( $row['post_title'], 0, 16 ),
		$row['meta_value'],
		$isFront
	);
	if ( $apply ) {
		$pid = (int) $row['post_id'];
		$stmt->bind_param( 'i', $pid );
		$stmt->execute();
	}
}

if ( ! $found ) {
	echo "  no page overrides the footer - nothing to do\n";
	exit( 0 );
}

printf( "\n%s: %d override(s)\n", $apply ? 'RESET to inherit' : 'DRY RUN (nothing written)', $found );

if ( ! $apply ) {
	echo "pass 'apply' to write\n";
	exit( 0 );
}

$db->query( "DELETE FROM wp_options WHERE option_name LIKE '_transient_enovathemes-%' OR option_name LIKE '_transient_timeout_enovathemes-%'" );
printf( "cleared %d cache row(s)\n", $db->affected_rows );

$left = $db->query( "SELECT COUNT(*) FROM wp_postmeta WHERE meta_key='$META' AND meta_value <> 'inherit' AND meta_value <> ''" )->fetch_row()[0];
printf( "verified: %s page(s) still overriding the footer\n", $left );
