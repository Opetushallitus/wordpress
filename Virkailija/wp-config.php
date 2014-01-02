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
define('DB_NAME', 'testiVirkailija');

/** MySQL database username */
define('DB_USER', 'testiVirkailija');

/** MySQL database password */
define('DB_PASSWORD', 'P1ppur1');

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
define('AUTH_KEY',         '9h+ys9e{m6T16S,:(t%tbM+hG-j2exd;3]`+2U2%3XtZWrMyKn^+_%TnfkHi+Tuw');
define('SECURE_AUTH_KEY',  '>p&@<13hXAJ]xdylYn<2-}@7B,.JC=_XjhDwLi}aX^H6cii:Ca;RWxXaOPKK -;V');
define('LOGGED_IN_KEY',    '[m`-,(G2B~VV*Ry=hI|f7TQ&I)jd+S/rs$`aId,~ckYRXr>m>7@&3DX.q;0p_.n.');
define('NONCE_KEY',        '1zE.L&M<8<0-hs`:&%pQ%9EaRhZx|9e]#qv>HHbTwCf30DbPn}Aj~=#,?,;6&D;|');
define('AUTH_SALT',        '(rRE2/?y=+nnIa4|^Qo*+QC,<*]1H>+V=vJZrOY}I4Io=-ok%E5{Jz>in^^cB238');
define('SECURE_AUTH_SALT', 'y?wd04)Ttx*a[ )B|Nqi12hQIoA|lgdF.ap}=teG*(SDS2b,=Jug__Qfs6l||[xg');
define('LOGGED_IN_SALT',   'bIavr=z!OD#_qLMUx `-0%y@yyE<gU]V`>^Y!}Me9TR09IEAk4K>21FXQ%2_#+m#');
define('NONCE_SALT',       'Nr1tZnpB0s1FCO}Q7=w-!XYdQthdI4G+$_gti!tejc;%(oWR2Vz=ZxzpatUSs_`W');

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
define('WPLANG', 'fi');

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

/* Automatically update plugins  */
define('FS_METHOD','direct');
