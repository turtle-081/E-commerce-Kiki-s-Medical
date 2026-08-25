<?php


/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          'G=%g[|fh5~A-mffr]~-()OGEcsnt?>JX0%qCVEc#&y0EnYQk638.Mi&xY:RYaazb' );
define( 'SECURE_AUTH_KEY',   '*?]u&G7MbHvcRk7wzd:-i`x$$5Gv!fRrgLm:c>0!Eu2>[qp.a*T#dyIj<]x>aD^A' );
define( 'LOGGED_IN_KEY',     '.uAIrbS^LWBHwQ8%goUytQfMX[V I=kV<*&dsWLb!j8ET[q91Ui0;O@,(! !4Xvj' );
define( 'NONCE_KEY',         'sLKp*BEhf3#<J/iJS1gj^~k9;FQq_lK]3yekICqkK3gJjF>9[!0t}A?3^mDjv`Ln' );
define( 'AUTH_SALT',         'btO{{e?UHSz<O?=HS~|kIuauN&R=mX?X.5/n^{W%,<&wN!+Cx5~m&3vOFq/h&;%<' );
define( 'SECURE_AUTH_SALT',  'oLW<m^9{?Ab&4&{-G{Ta>.i+lkKe!;X&#4G+eg+#i&#U=j,*,RFXJ5,Q<r~ni[EM' );
define( 'LOGGED_IN_SALT',    '9EfZc;Ji-r,i81#6<OD8$Zt}-3Xi&5Df0DnHcm(oTS7 xF-*f1igWTpo+I3-,yQ$' );
define( 'NONCE_SALT',        'p)b)DPBZrY<x5|3eVclM%@7R!|=FjYY`sKOy!6{,>MFF;eJEA(64cj|g(&gkG3xf' );
define( 'WP_CACHE_KEY_SALT', '=MpDb1@G3R!LXF?/>xJocB5^ NW2yET0`/7H5IE@o6Rf;OK>)lczkXkr.wwKO,0`' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );

/*
 * Stop page views from spawning wp-cron.php. Each spawn was a full WordPress
 * bootstrap costing ~2.4s on this machine, on top of the page's own render.
 *
 * Nothing runs scheduled tasks now, so run them by hand when you need them
 * (WooCommerce's Action Scheduler, scheduled posts, etc.):
 *
 *     wp cron event run --due-now
 */
define( 'DISABLE_WP_CRON', true );

define( 'WP_POST_REVISIONS', 5 );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
