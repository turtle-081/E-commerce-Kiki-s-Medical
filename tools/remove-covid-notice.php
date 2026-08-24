<?php
/**
 * Remove the COVID-19 notice from the site headers.
 *
 *   php tools/remove-covid-notice.php          # dry run
 *   php tools/remove-covid-notice.php apply    # write
 *
 * The notice is an [et_header_slogan] element inside the 48px top row of the
 * header posts. That row also carries the login toggle, currency switcher and
 * language switcher, so only the slogan element is removed -- not the row.
 *
 * Only slogans whose text actually mentions COVID are touched, so an unrelated
 * slogan added later is left alone.
 *
 * Header content is cached in the no-expiry '_transient_enovathemes-headers'
 * transient, so that is cleared too; otherwise the notice keeps being served.
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

$res   = $db->query( "SELECT ID, post_type, post_title, post_content FROM wp_posts WHERE post_content LIKE '%et_header_slogan%'" );
$stmt  = $db->prepare( 'UPDATE wp_posts SET post_content=? WHERE ID=?' );
$total = 0;
$rows  = 0;

while ( $row = $res->fetch_assoc() ) {
	$removed = 0;

	$new = preg_replace_callback(
		'/\[et_header_slogan\b.*?\[\/et_header_slogan\]/s',
		function ( $m ) use ( &$removed ) {
			if ( stripos( $m[0], 'covid' ) === false ) {
				return $m[0]; // an unrelated slogan - leave it
			}
			$removed++;
			return '';
		},
		$row['post_content']
	);

	if ( ! $removed ) {
		continue;
	}

	$rows++;
	$total += $removed;
	printf( "  %-6s %-10s %-22s  %d slogan(s)\n", $row['ID'], $row['post_type'], substr( $row['post_title'], 0, 22 ), $removed );

	if ( $apply ) {
		$stmt->bind_param( 'si', $new, $row['ID'] );
		$stmt->execute();
	}
}

printf( "\n%s: %d slogan(s) across %d post(s)\n", $apply ? 'REMOVED' : 'DRY RUN (nothing written)', $total, $rows );

if ( $apply ) {
	$db->query( "DELETE FROM wp_options WHERE option_name LIKE '_transient_enovathemes-%' OR option_name LIKE '_transient_timeout_enovathemes-%'" );
	printf( "cleared %d cached header/content transient row(s)\n", $db->affected_rows );
} else {
	echo "pass 'apply' to write\n";
}
