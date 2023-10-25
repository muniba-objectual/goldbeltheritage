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
define( 'DB_NAME', 'ghfportal' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

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
define( 'AUTH_KEY',         'I2y(8Q|fV&ben>ena!7fj9G#t,xi)bz|D<K9$rw)vX0:)tv3)qM4)uko.?x*ZU/8' );
define( 'SECURE_AUTH_KEY',  'Mrny.oyhd@aMn2n_o13b6I@|Bq5B8<vcHP]f2BHNGV}[V].H/(%FQ|gW+~U4Udwi' );
define( 'LOGGED_IN_KEY',    'mD,YzzD*L,X$kw`<M51/(!xK.kRwK_Di>aH;WkReaNC0Mk`{<<Nc&q0HW.fKJ%M=' );
define( 'NONCE_KEY',        '[Tv=t6_N-b?Mn|cZ/v&58myyJxHMkP<#>waj)sbad[1GAR@ y|_7n6;sOF5PRmFv' );
define( 'AUTH_SALT',        '?/ >DuT;8&h:CwS+9>~-qu9P]b&(hsZbb0 uTeXj]cM@+*U]%&^ZGX89b/B@IgqH' );
define( 'SECURE_AUTH_SALT', ',h0LE<pZWmhWwEmIiH_Od!1|R>cWS=B|k<N{prn= <}`rUH%z]P9(w:35Lfl2b;9' );
define( 'LOGGED_IN_SALT',   '%;mY=L<KR%IK8cRV[I3T1u)OFsd)4dSXTUZ9QqXI?Gpc*yTKUbeh[$>`vt2h-=7y' );
define( 'NONCE_SALT',       '%^6f|G=b^fbXWx%zn<g3KJKJQYiti&mC7COAa~%u&W&?dxO,i,<&Ns(_x/0c*Z*O' );

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
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
