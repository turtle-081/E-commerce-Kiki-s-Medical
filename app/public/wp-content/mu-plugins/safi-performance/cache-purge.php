<?php
/**
 * Phase 2A.2 — purge the nginx FastCGI cache when content changes.
 *
 * The brief suggests the Nginx Helper plugin. It is not used here for two
 * reasons: this nginx has no ngx_cache_purge module (checked in discovery), so
 * Helper would fall back to deleting files itself — exactly what this does — and
 * rule 4 says WordPress-side customisations belong in this mu-plugin rather than
 * in another plugin.
 *
 * Purge strategy is "delete everything on any content change". The brief's own
 * example calls that the simplest reliable option and suggests refining to
 * per-URL later. It is the right trade-off here: the cache key includes the
 * request URI, so a single product edit can invalidate the product page, the
 * shop archive, every category page it appears in, the homepage grids and the
 * theme's cached megamenus. Enumerating those correctly is easy to get subtly
 * wrong, and a full purge costs one slow request per page afterwards —
 * `fastcgi_cache_background_update` means only the first visitor pays it.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Absolute path of the FastCGI cache store, matching fastcgi_cache_path in
 * conf/nginx/nginx.conf.hbs.
 */
function safi_fastcgi_cache_dir() {
	/**
	 * Filter the cache directory, so a different install can point elsewhere
	 * without editing this file.
	 */
	return apply_filters(
		'safi_fastcgi_cache_dir',
		dirname( ABSPATH, 1 ) . '/nginx-cache'
	);
}

/**
 * Delete every cached response.
 *
 * Returns the number of files removed, or -1 if the directory is unreadable.
 */
function safi_purge_fastcgi_cache() {
	$dir = safi_fastcgi_cache_dir();

	if ( ! is_dir( $dir ) || ! is_readable( $dir ) ) {
		return -1;
	}

	$removed = 0;
	$items   = new RecursiveIteratorIterator(
		new RecursiveDirectoryIterator( $dir, FilesystemIterator::SKIP_DOTS ),
		RecursiveIteratorIterator::CHILD_FIRST
	);

	foreach ( $items as $item ) {
		if ( $item->isDir() ) {
			// Leave the level-1/level-2 directories; nginx recreates them anyway
			// and removing them races with in-flight writes.
			continue;
		}
		if ( @unlink( $item->getPathname() ) ) {
			$removed++;
		}
	}

	/**
	 * Fires after the cache has been emptied.
	 */
	do_action( 'safi_fastcgi_cache_purged', $removed );

	return $removed;
}

/**
 * Purge at most once per request, however many hooks fire.
 */
function safi_purge_fastcgi_cache_once() {
	static $done = false;
	if ( $done ) {
		return;
	}
	$done = true;
	// Defer to shutdown so a bulk edit or an importer purges once at the end
	// rather than after every single save.
	add_action( 'shutdown', 'safi_purge_fastcgi_cache', 1 );
}

/* -------------------------------------------------------------------------
 * Content changes
 * ---------------------------------------------------------------------- */

// Any post type going in or out of published state, including products.
add_action(
	'transition_post_status',
	function ( $new_status, $old_status, $post ) {
		if ( 'publish' !== $new_status && 'publish' !== $old_status ) {
			return; // draft -> draft and similar never affect a cached page
		}
		if ( wp_is_post_revision( $post ) || 'auto-draft' === $new_status ) {
			return;
		}
		safi_purge_fastcgi_cache_once();
	},
	10,
	3
);

foreach ( array( 'deleted_post', 'trashed_post', 'untrashed_post' ) as $safi_hook ) {
	add_action( $safi_hook, 'safi_purge_fastcgi_cache_once' );
}
unset( $safi_hook );

// Menus, widgets, theme options and permalinks all change cached markup.
foreach ( array( 'wp_update_nav_menu', 'update_option_sidebars_widgets', 'switch_theme', 'customize_save_after', 'permalink_structure_changed' ) as $safi_hook ) {
	add_action( $safi_hook, 'safi_purge_fastcgi_cache_once' );
}
unset( $safi_hook );

// Comments change the markup of the post they are on.
foreach ( array( 'comment_post', 'edit_comment', 'delete_comment', 'wp_set_comment_status' ) as $safi_hook ) {
	add_action( $safi_hook, 'safi_purge_fastcgi_cache_once' );
}
unset( $safi_hook );

/* -------------------------------------------------------------------------
 * WooCommerce
 * ---------------------------------------------------------------------- */

/**
 * Stock, price or status changes alter the product page, the shop archive and
 * every grid the product appears in, so they must purge even though no post
 * save necessarily fires.
 */
add_action(
	'woocommerce_product_object_updated_props',
	function ( $product, $updated_props ) {
		$watched = array( 'stock_quantity', 'stock_status', 'price', 'regular_price', 'sale_price', 'status', 'catalog_visibility' );
		if ( array_intersect( (array) $updated_props, $watched ) ) {
			safi_purge_fastcgi_cache_once();
		}
	},
	10,
	2
);

// An order reducing stock has the same effect.
add_action( 'woocommerce_reduce_order_stock', 'safi_purge_fastcgi_cache_once' );
add_action( 'woocommerce_variation_set_stock', 'safi_purge_fastcgi_cache_once' );
add_action( 'woocommerce_product_set_stock', 'safi_purge_fastcgi_cache_once' );

/* -------------------------------------------------------------------------
 * Manual purge, for the admin bar
 * ---------------------------------------------------------------------- */

add_action(
	'admin_bar_menu',
	function ( $bar ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$bar->add_node(
			array(
				'id'    => 'safi-purge-cache',
				'title' => 'Purge page cache',
				'href'  => wp_nonce_url( admin_url( '?safi_purge=1' ), 'safi_purge' ),
			)
		);
	},
	100
);

add_action(
	'admin_init',
	function () {
		if ( empty( $_GET['safi_purge'] ) || ! current_user_can( 'manage_options' ) ) {
			return;
		}
		check_admin_referer( 'safi_purge' );
		$n = safi_purge_fastcgi_cache();
		add_action(
			'admin_notices',
			function () use ( $n ) {
				printf(
					'<div class="notice notice-success is-dismissible"><p>%s</p></div>',
					-1 === $n
						? 'Page cache directory not found or not readable.'
						: esc_html( sprintf( 'Page cache purged: %d file(s) removed.', $n ) )
				);
			}
		);
	}
);
