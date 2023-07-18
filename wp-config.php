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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'pardzworld2' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'Guna2365@' );

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
define( 'AUTH_KEY',         '{BBxxpwm>nW%%W7!tJE9/:E?/]ZU#hc1 JL)QE[gw@Y:auj,s%*MOD)|=&E%_NG7' );
define( 'SECURE_AUTH_KEY',  '`R*{k;`;d,.v${+yRhsTM3C*j^Iho}oH/NQA^QIvoq/}_Iz3DhDz;f@Fgc_BM_OY' );
define( 'LOGGED_IN_KEY',    '/w,4,-egzE%|fevs*|uWiPpz`B+4e+E(~ZU41y_n_q~Nn&0SN0D_<(0L.8bVzDy]' );
define( 'NONCE_KEY',        '22W)sF->=i]F8&Vj-e]2UY;E}/c%;P7A}%Gv-oWv_of^|F$SYZYCt`1,K^UT#*T*' );
define( 'AUTH_SALT',        'Cc={1YHaVO$uJaF}$z]41[boX *;xw2H7ooQvF2&nwcm=t}SBr=m4!]J+V!AX;GU' );
define( 'SECURE_AUTH_SALT', ']BUQr[HTy~u4aAw#$zi;v]G7%dY9m018,jo~pObx-hl>k f?cW5BH2$1cvZlO7WO' );
define( 'LOGGED_IN_SALT',   '[J1u[BY!>0u.~W^hL&m3G}|cj/wnwIoy@$e?x3]68-Zt?j#L2%u7Ijl_tH=!:myJ' );
define( 'NONCE_SALT',       'O8miFy4vZ?]QOz1$~(bygjZ.366yEypuC`Su(_pTwQ7<A-G$h,$;O_oy&srVEuf<' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'pw_';

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
define('WP_MEMORY_LIMIT', '256M');
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
/* Add any custom values between this line and the "stop editing" line. */

/** The Coupon Code client id . Don't change this if in doubt. */
define( 'Client_id', '188286fdf73e4adbae116842bd662bd3' );

/** The Coupon Code client secret key . Don't change this if in doubt. */
define( 'Client_secret_key', 'addb312353ca4A5dB36662A67531119C' );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}
if ( ! defined( 'menu_type' ) ) {
	define( 'menu_type', 'appliances' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
