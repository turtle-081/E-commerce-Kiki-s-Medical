<?php
/**
 * Flush the enovathemes-addons layout transients (headers, footers, megamenus,
 * banners, icons, filters, dynamic css).
 *
 * These are normally purged by the theme when the underlying content changes,
 * but a direct post_content edit made outside the header builder does not fire
 * those hooks, so they are cleared explicitly here.
 */
require dirname( __DIR__ ) . '/app/public/wp-load.php';

global $wpdb;

$like = $wpdb->esc_like( '_transient_' ) . '%';
$rows = $wpdb->get_col( $wpdb->prepare( "SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE %s", $like ) );

$patterns = array( 'header', 'footer', 'megamenu', 'banner', 'icon', 'social', 'categor', 'filter', 'dynamic', 'title_section', 'product_query', 'post_query' );

$deleted = 0;
foreach ( $rows as $name ) {
	$key = preg_replace( '/^_transient_(timeout_)?/', '', $name );
	foreach ( $patterns as $p ) {
		if ( false !== stripos( $key, $p ) ) {
			if ( delete_transient( $key ) ) {
				$deleted++;
			}
			break;
		}
	}
}

printf( "  deleted %d theme transient(s)\n", $deleted );
