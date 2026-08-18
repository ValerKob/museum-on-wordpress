<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

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
define( 'AUTH_KEY',         'B1;Z&J;@Fe(qRtEV=Jbivq_=/jL1FD[(R]~6?^[OW]mCpgZ,PRsDDhSr0?SV8m<P' );
define( 'SECURE_AUTH_KEY',  'j6Ir%.vqc0,Z~If`6G{g_*ZiHd0!^!y!(Sijtn:J,:L*[sok9EAclc[u8]8oP+S=' );
define( 'LOGGED_IN_KEY',    ' ;Gtx^JMI#M%[bqW+c~.tN>TtpuyW-[8GmE&rE%pe)G?ms:3V0*h.LyXq$F]]tQx' );
define( 'NONCE_KEY',        'EV|2GX;$QB=4JPB;b&/Eoj6k[5X1IuT{oTT+DSiPb*[#539UE5d9J%zP= -gBggx' );
define( 'AUTH_SALT',        'MWTNPhC$WZADvU;K^Hz-tBu77<>;0^$d=qGWM-m[66+<-uz$dwmxgAtWM%coCEOr' );
define( 'SECURE_AUTH_SALT', 'b|,3TLF01;FM5,O3f;%X9[]|z9658{o?h^n:$7R40)NbiLu}u[,osFI@MYDwe/]d' );
define( 'LOGGED_IN_SALT',   's?2{[lr|jk/b~B;<h_a#s4vWML/{r?!S^la3Sf@+3^P(y6TxvSb^9#*1UB4 .eZG' );
define( 'NONCE_SALT',       'Us<`==5 j%zO9:!mk0Lq%*P@tD@~~~lQKisFHyD~!eO/ynA]Ai* %>xGHsJpl[Gr' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
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
