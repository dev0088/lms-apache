<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'database' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root1234' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '=d&}c-kKbX&Zm[?k}RR50rcXe$6NpuCdFanOkii~);T^G||R17;7SkPY;oU~6L?t' );
define( 'SECURE_AUTH_KEY',  'ZUq`.:U[gb8SEftXN&6=(SQ/DzA*EUp8Q=i9l.]QJ1b0C3Cr#rjskCQxW5)gB0]|' );
define( 'LOGGED_IN_KEY',    '6#w:5EN6^4;dNf{4XeV{tbE7ZA3xSAs:?xKxa,UfVh-eq1Y@/G7Cwt$m2,Y*ka&&' );
define( 'NONCE_KEY',        'Y>%CA{*W]0r=2wZ+(XN>0)^)p.XmC<bv*}aXU)VPIMrpDGfrU<1~!(MP8~D]Hv+x' );
define( 'AUTH_SALT',        '-J>^J5CS~geA*z#;|4&W_7/mm+TrhU5i^{1,m}!O6l&qr-9h Io(x[26<wkyQM$v' );
define( 'SECURE_AUTH_SALT', 'a_gj1!{D1vUk{}u9W`LDg/W!lLl!0/C8Np4=~J?I#E(j065C7zPMFOmW-772OqYY' );
define( 'LOGGED_IN_SALT',   '<VY.+e2z1&E.|vpjB SKF~6tG9EyvRNo4qnHozRQ,#e@u5r(R,sv2_*D#)X5^|b#' );
define( 'NONCE_SALT',       '=OTqToe_mxZ#t3_pWDicG,=lC6wol9i{+9bBSDxEGa^B:_`jo)RKM[:Br%GMYF=L' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
