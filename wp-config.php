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
define( 'DB_NAME', 'ergophone' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'clashofclan' );

/** MySQL hostname */
define( 'DB_HOST', 'db:3306' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define('AUTH_KEY',         '8k|-!k(z+}by|Y)$Y_D4*;8SK TRD_SP8:[g}ZtxhsE_%+acCWlKOrL4/!(#tmr]');
define('SECURE_AUTH_KEY',  '!}}q#]LksD[$G~oMo4SqaK-k7i^5K]-K.2=x tv:Ihi|Y/_2.Inj)jiB`TW,v7Tv');
define('LOGGED_IN_KEY',    'Uh~,~#rMb+~M3R]f{8d|(Zez+h###m<o,XizPY[~FWc1|g1D__o~A#>kLSB)FDAs');
define('NONCE_KEY',        'te1YTuXQH.XeL5pzl>1D4rAyZ0h(b:[u{A+|^-|uZ&vhl&ddkxADS*M3-RoWij82');
define('AUTH_SALT',        '0+^YG|]tZZl26&16?bj.;n@L=MK}G>P-YExD9|B^6-f9V.W{eB|K3|o_T8Qv7c?9');
define('SECURE_AUTH_SALT', 'G2XLyLeU}Q?BARB!K.!Op&hj)Mom{n3P$>nBaac&>(81wn%Zu9xI-N]5P[CNI/r_');
define('LOGGED_IN_SALT',   'Soh>ex1d#/#?PT0DJ-uh2Csva5;n97IuNJgKQS;K-B_q8;L(ov3jAfb`:-!a 3>k');
define('NONCE_SALT',       'dH,-}o~,9~kNate P9DbtB6gwbkIL4#EYWw=LIYQ}Z;D*KvjAouFeIQfB-vSG+.q');

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

define( 'WP_HOME', 'http://localhost' );
define( 'WP_SITEURL', 'http://localhost' );

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';