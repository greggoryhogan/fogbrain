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
define( 'DB_NAME', 'wp_fogbrainlocal' );

/** Database username */
define( 'DB_USER', 'gregg_wp' );

/** Database password */
define( 'DB_PASSWORD', 'Skate4Days!' );

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
define( 'AUTH_KEY',         'tx,I<si)wI*8Wz$^)+|cc.H^S;C{lk/xv$yA5E,d4ub){M,;hXgp##DM#!cPb_su' );
define( 'SECURE_AUTH_KEY',  '9PrBOI^E:6X||ejyTJ7kb`/5f`p~bXYHBa;THzG%&=Srolt+;Y86>4[G2zJG-d[j' );
define( 'LOGGED_IN_KEY',    '_UF1hV_k~t>soO=t,(8t[xC0In3vuRlfVF0aXH)6`#M~tG Asf7R+k&CDND6SOuP' );
define( 'NONCE_KEY',        'g4|JQ(*^[GhZ_0Ma>%Udl7ynTmFp1s%YB(H^UNzVpA7irGaM7Y`o0*9Fb~oTPnSf' );
define( 'AUTH_SALT',        '[-qGPVP/tGWUI7&i9nT62wKEt^-h-c7:2j>B~pAI=WEF51lL6VlvPMBKAeOu[dmi' );
define( 'SECURE_AUTH_SALT', '.cI1#M`cm*(HPAG,}QRxLq`*NGzlX;s.xEd>yPn/K+@*:V#NO-}#L:B)+cuDgo m' );
define( 'LOGGED_IN_SALT',   'pr)#s5!?=gmxVS.(jBmKaiws-Boufpi+Z >YW7lrHxd!D-9#y^xI+sQ,O748xh H' );
define( 'NONCE_SALT',       '/!X$qf=Gc V,]Q(Yh#t{9N2|0RNB#w4;%vb$*Fim;D,4$J[qfFW|c,fZ*jUxWg{Q' );

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
