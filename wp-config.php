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
define('DB_NAME', 'wp_npr_jm');

/** MySQL database username */
define('DB_USER', 'npr_admin');

/** MySQL database password */
define('DB_PASSWORD', 'balloons_npr');

/** MySQL hostname */
define('DB_HOST', 'mysql1.domains4less.net.nz:3306');

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
define('AUTH_KEY',         'y*Y(zngT;j5{lcEqRmr.~Jyz#,O<a@O8P*fsCts}~yyYL7aRG|5{kyEEQP{YTX.Q');
define('SECURE_AUTH_KEY',  'bJL.lue/tY239lEZ&Q?W|03W9VY#-aw&wr9qFz|H,I&2n4_x1w:ZEgQJGlD?ohsY');
define('LOGGED_IN_KEY',    't4{O/D~cS[25h}O-Q6<dH,qALy|i&5XnN@kQ0fe2re7?U/zJ{{Hm^wrau&-#qaa5');
define('NONCE_KEY',        'Wb7U0}|+Q)ZRl@}RXs!4avUr&|.|W^[]N>b5?+d3*d}7Ch=%ayE{WZJuqNIW+lQN');
define('AUTH_SALT',        '[tL_cj2?_D~]4KSuq0 fu#D7V~Tq!W?qY8ax)_*,;cKwoBQ_y/H1p0CoZC#Sc<AI');
define('SECURE_AUTH_SALT', 'X%Wf[E*qv6ydos*CZ=v|>@)l@e^)(6nx$OFi~]g^^!]w!eyz5w6N.b{<;,Ml0-f@');
define('LOGGED_IN_SALT',   'lHI46R(d<_+Z|NwaGC1o;.A?d4c@z>[0b %7jms.%t&CA*g::0`<4~l<BK3> {|v');
define('NONCE_SALT',       'uAv8z:z{ugme]wW/VQ}9f,#}|V9qQF,9&F#jAPGY^0cL)}-`Fk1mXt_!,<0/c|kK');

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
