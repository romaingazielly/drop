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
/** The database for WordPress */
define( 'DB_NAME', 'ergophone' );
define( 'DB_USER', 'root' );
define( 'DB_PASSWORD', 'clashofclan' );
define( 'DB_HOST', 'localhost' );
define( 'DB_CHARSET', 'utf8' );
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
define('AUTH_KEY',         'I#>s >c_}jc S[b%hy+)hP?nO:u1A.d46O~}./M?6}KCej-W0<UH|TTe]|Nq#I?a');
define('SECURE_AUTH_KEY',  'M;w;useNDLs2+!w PA+-]!q9-(]kU%s@Z% xJS4q+Q zp2%F*`d>tHV-WC:*x=US');
define('LOGGED_IN_KEY',    '10U{1Du+pR.Ku_.cqc-(i,L47HTV4ZB|R1oE5?F2X [M1%kvE.D>D872xp1-NngR');
define('NONCE_KEY',        '.J%-Dys7FX6SrQ18HZj/oDnFQ1P+/Dlm5O;#m5q+a@82xbrAVb-ky{d;QWao . z');
define('AUTH_SALT',        'ptdzm]V;0==~-p+dxRt|j KV{D#l(+yruVADbD,D+V]htaU2Rc/_,_6|2FMSf8|f');
define('SECURE_AUTH_SALT', '`m)y.(M+h8![9E!/`$&Tt?<F1gWO%M0Qox(MNXC1BrSbL!FeY?GXs %;0p*C@q|r');
define('LOGGED_IN_SALT',   '{dF|}^B)u/Ed,uf^~LT>:Sa!gIQnaI!j3},%K{,*,?BNm%oanF`(E{W~Ghke1ntA');
define('NONCE_SALT',       'V8($=nZ,9M/}^5m /NL$$+Ij@E9A-sLq7;[k<`+qRsTZkS9IW-!_+Y]CFF?-1mL6');
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

/* Custom variables */
define( 'WP_MAX_MEMORY_LIMIT', '256M' );

/* That's all, stop editing! Happy publishing. */

define( 'WP_HOME', 'http://localhost' );
define( 'WP_SITEURL', 'http://localhost' );


/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
