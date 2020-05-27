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
define( 'AUTH_KEY',         'bwjCJXun.8Dy/HL|d@~,N-PvCZMt`|/Ahez15u#Z=vA`xJ$z3_1LHz)0GT>?2hyf' );
define( 'SECURE_AUTH_KEY',  'V#09U9kW?S%@bK0jyNdDJjJQS`*)|T]k)g`%u^;X9Ri50IkBT+#uXP1cmHzy?muO' );
define( 'LOGGED_IN_KEY',    '+)6Pf2e1vEI7FECvm/CaRcdFvl&tTd JNAaq}K`hu.&`!LgI3}].bYcb{Hu}V y4' );
define( 'NONCE_KEY',        'Lka%0[E`g%*7i+RoqeK@maQ2mPw+:vS5v,A1MpFiU?=_, rm[e50v|:A__@114?%' );
define( 'AUTH_SALT',        '1ZpnPHCJ|!XN/Zc|os|{VSILAYbEX9MsDYs(Yo9r}<ogN1HMhm$04<mGmbil]}Y1' );
define( 'SECURE_AUTH_SALT', '([?fIaUs%oAPj+sJ,=tPRvT$thm[U+?-{Vfp0j,wTNgXUAFS6O6pB]-!]+}m5OU*' );
define( 'LOGGED_IN_SALT',   '(qV gB;w<)QJ5~fx1v}BMW_&Q~F#P}4/no/z.[ h$>d`TO~}S,:[xMv1blWF,di9' );
define( 'NONCE_SALT',       '<c, Sg7U4%]ET:kI>)P7_yBJ=Rry]dnVDkKVDl@=f byW_#GZZY]r#!D>aZGb!qu' );

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
define('WP_ALLOW_REPAIR', true);
