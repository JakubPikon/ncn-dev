<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'ncn');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'lYp+8hUQ6qpHtH-8sKf_;k6},)stn9z*09A??^tK*,gaeZr4F[,Fh,CegD`:*u/8');
define('SECURE_AUTH_KEY',  'RvLA3T11Q3|!Ev{Kmgz1/!}F$C`EoBJL[,RnJ7+M:4Gu::+Si2jA:u$u(gQLYgS|');
define('LOGGED_IN_KEY',    ',(]c5C.!Sx {BE;|$^s3NjFFZ^/&3Mu_-]] s1T,3<4FV.StrdRP7!W?>Q+cMG v');
define('NONCE_KEY',        '&#C_R8XH5?eYe]Q)w1hdAB*hM!2ATe<G2J>1N5Cq_@N|$Wj6>[J+`U3KI,x<>Hjd');
define('AUTH_SALT',        'Yq#e*w<[btx<xnVl+s8]xeOH!&VL2Do+o&GnhZDS14m^uO2(AoVD4eeuarq4 (6t');
define('SECURE_AUTH_SALT', 'b~#i>+]x8]]9oPVwfWT}L*ki9[x/lW^>kq=tq-Dv_I+&?8+q93kvOnU{#OlzWHWR');
define('LOGGED_IN_SALT',   'aXIpnq?H57(;fu[LQpa_:XH$/XU#Kh2pd.yJWM4m>$[gqu!9`}~YV*y5x6^dT`:U');
define('NONCE_SALT',       'j<X24Fekx9PZols/2uve|3)i2]$dBU7jlx}~En_Lw~OwgIl!Xb1Y,R<y/U*rMO},');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
