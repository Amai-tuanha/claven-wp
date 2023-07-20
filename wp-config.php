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
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'FS_METHOD', 'direct' );
define( 'DB_NAME', 'blog' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '123_abc_ABC' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         'B(.,UW|jj)I4 Nq>a -vik~|J@g}<~?^0|d1eP3+Qvq-BC+0~1^?-YbkJKvxcSq?' );
define( 'SECURE_AUTH_KEY',  'j_76NH|73k788-INvItj*{Y)yPijkWuO(w>|v[*G%($[;qt2PEpN{v<Y`gd5{DVx' );
define( 'LOGGED_IN_KEY',    'QUdI&aqT6z];6.H]hETtF$&e1,t-#B(^Vy0x+E>X%ikBKHKQwtngs)r;L9ZKiNIg' );
define( 'NONCE_KEY',        ',},7t@.mYI`/55;}5~YQM7!(>oepg5M<EiV{ddEr#&!Y3P#%i{5z5$WE(xjYg@iX' );
define( 'AUTH_SALT',        '3~5^C2$Ml.@g7WKobaz)Q7cR5$Mgdk9Zd#OVZhT{cgurwe5FYmE(2J <jP f0SFT' );
define( 'SECURE_AUTH_SALT', 'R5:0mrE+%N &#F9Ix[XpoS!7h[1w;X9Y%Od%wF_DVp>^DnZ.q+(<4lK-kbGIgB#1' );
define( 'LOGGED_IN_SALT',   'lit2oGMa{c:7@ZVA-)Y( SN/}?Rh)bJ@8a4>z%/,p:skp=**1&Z2*;g&28GP@Qv?' );
define( 'NONCE_SALT',       '!yZ|o$,xvKp^pY3W,x*G}CwUGa@oHd1V%<v`!Hv&.,n=F^MF]7q[h5@[<ZOZ&$Oe' );

/**#@-*/

/**
 * WordPress database table prefix.
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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
