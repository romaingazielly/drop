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
define( 'DB_NAME', 'wordpress' );

/** MySQL database username */
define( 'DB_USER', 'wordpress' );

/** MySQL database password */
define( 'DB_PASSWORD', 'wordpress' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

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
define('AUTH_KEY',         'V:bPdts|x@~|;NAgS*|f rq%z]4+4.oS+?>mj3)>%5BOXB;uZv#BtamWlg2@j2O@');
define('SECURE_AUTH_KEY',  '~2UkxOcW&Rh^vI<{o@=}W}![d~^7n3&51,xs;DpsNJ>Z.izn.{${z5SFE8bBq85O');
define('LOGGED_IN_KEY',    '(`lVEwbI6{H.b{!M5DPR3C%i*z%lz,Ty%=QP%?EyGHV5ka}+bU;A[H@.[*<*i<|F');
define('NONCE_KEY',        'yd{Ke$kQ2Y)fvy2z2mUB}./km-x{qOk?[HXwz b5biz-]?FnUu=jA-IVJD^U`Z_b');
define('AUTH_SALT',        '$vv~.hDZ@QEj(;En.P7:+f|QZ[(q$&? h<]@q<yoZ@V]P-}m%.x MK!: s >]F=i');
define('SECURE_AUTH_SALT', '-~y$+KDosdDOCeA-P$7sU^~PwyIY./]MP!z|+^4p=ur^ F-+[zTa,9[pN^HOL%GI');
define('LOGGED_IN_SALT',   'HR9|]^)hz~4rmA/EXADns?$_<.l82Prxecr2NOM@{vB6VGb:GTy2>In!kn4]apX0');
define('NONCE_SALT',       '(Ds0Sk|TND9U9P&To+BH$-qq>+ao<:HhGW^y&|Vt;JBcAr@y{O1$i6m/C;4np.xq');
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

define( 'WP_HOME', 'https://drop.romain.ninja' );
define( 'WP_SITEURL', 'https://drop.romain.ninja' );


/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
