<?php
/**
 * Replace the demo phone number in the site headers.
 *
 *   php tools/fix-header-phone.php          # dry run
 *   php tools/fix-header-phone.php apply    # write
 *
 * The header's "Sales & Service Support" block had two separate demo values: the
 * number shown to visitors (986-456-6782) and the number actually dialled
 * (tel:555555). They are different fields, so both have to be replaced -- fixing
 * only the visible one would leave a header that displays the right number and
 * dials the wrong one.
 *
 * The header shows a single primary number; both numbers remain in the footer.
 */

$apply = ( ( $argv[1] ?? '' ) === 'apply' );
$port  = isset( $argv[2] ) ? (int) $argv[2] : 10004;

$REPLACE = array(
	// Displayed number.
	'986-456-6782' => '+254 704 329 920',
	// The dialled href.
	'tel:555555'   => 'tel:+254704329920',
);

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

// Not restricted to headers: if a demo number is sitting in a footer or page it
// should go too.
$like = array();
foreach ( array_keys( $REPLACE ) as $needle ) {
	$like[] = "post_content LIKE '%" . $db->real_escape_string( $needle ) . "%'";
}
$res  = $db->query( "SELECT ID, post_type, post_title, post_content FROM wp_posts WHERE " . implode( ' OR ', $like ) );
$stmt = $db->prepare( 'UPDATE wp_posts SET post_content=? WHERE ID=?' );

$rows  = 0;
$total = 0;
while ( $row = $res->fetch_assoc() ) {
	$c    = $row['post_content'];
	$hits = array();
	foreach ( $REPLACE as $from => $to ) {
		$n = substr_count( $c, $from );
		if ( $n ) {
			$c        = str_replace( $from, $to, $c );
			$hits[]   = "$from x$n";
			$total   += $n;
		}
	}
	if ( ! $hits ) {
		continue;
	}
	$rows++;
	printf( "  %-6s %-8s %-12s %s\n", $row['ID'], $row['post_type'], substr( $row['post_title'], 0, 12 ), implode( ', ', $hits ) );
	if ( $apply ) {
		$stmt->bind_param( 'si', $c, $row['ID'] );
		$stmt->execute();
	}
}

printf( "\n%s: %d replacement(s) across %d post(s)\n", $apply ? 'REPLACED' : 'DRY RUN (nothing written)', $total, $rows );

if ( ! $apply ) {
	echo "pass 'apply' to write\n";
	exit( 0 );
}

$db->query( "DELETE FROM wp_options WHERE option_name LIKE '_transient_enovathemes-%' OR option_name LIKE '_transient_timeout_enovathemes-%'" );
printf( "cleared %d cache row(s)\n", $db->affected_rows );

// Read back so a partial replacement cannot pass unnoticed.
foreach ( array_keys( $REPLACE ) as $needle ) {
	$n = $db->query( "SELECT COUNT(*) FROM wp_posts WHERE post_content LIKE '%" . $db->real_escape_string( $needle ) . "%'" )->fetch_row()[0];
	printf( "verified: \"%s\" now in %s post(s)\n", $needle, $n );
}
