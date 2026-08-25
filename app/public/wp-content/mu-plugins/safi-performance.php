<?php
/**
 * Plugin Name: Safi Performance (loader)
 * Description: Loads the performance mu-plugin modules in safi-performance/.
 * Version:     1.0.0
 *
 * WordPress only auto-loads PHP files at the top level of mu-plugins/, so this
 * loader exists to pull in the modules that live in the subdirectory.
 *
 * All WordPress-side performance customisations for this engagement live in
 * safi-performance/ and nowhere else -- not in the theme, not in a plugin, and
 * never inside the WooCommerce or payment gateway directories.
 */

defined( 'ABSPATH' ) || exit;

foreach ( glob( __DIR__ . '/safi-performance/*.php' ) ?: array() as $safi_module ) {
	require_once $safi_module;
}
unset( $safi_module );
