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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //

if (strstr($_SERVER['SERVER_NAME'], 'fictional-university.local')) {
	define('DB_NAME', 'local');
	define('DB_USER', 'root');
	define('DB_PASSWORD', 'root');
	define('DB_HOST', 'localhost');
} else {		// public host
	define('DB_NAME', 'db56vur9n3g54s');
	define('DB_USER', 'unmwba2acmjs2');
	define('DB_PASSWORD', 'pleasedonthackme2020');
	define('DB_HOST', '127.0.0.1');
}


/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'tMCqTanooVeEePHHRj1yf/OjsZNn7oXV3fMXcKFE+diA7535Q/AP/QFR6SIUtw3w5ddjEGGBGC5aYxTHAazHPg==');
define('SECURE_AUTH_KEY',  'ukiKiI3en/u28tsysKM+huUrZ1W7rEUSfMi8MrOMmio4l/rlBmOiGSqtU8QRc2nRbv4oy2Y+NOpyd9wN3ijedw==');
define('LOGGED_IN_KEY',    'RYQ7mKeWCb37lXOaFACYVMLyFJlJMYdr4GfHJP2xDkY46sformYr7FMQlBApNcqTNf54vnTs4P5FCPX3zNaQrg==');
define('NONCE_KEY',        'cwauarltiwiWG+HmO2PvanJNyKJkU9B2x0ju30LCmxdpe4wF6P/vfDLZ60bW06MU2yn4BwrH4zsih3FVjcv0Jg==');
define('AUTH_SALT',        'DQKM27H+jJqx6cmv8fbfTyhoHZYT2/Yw9aMN+W0cM18KAkqVdxgXeHN/zSwNHJxzaeMnv/Y+kxUz/9Isgk5THQ==');
define('SECURE_AUTH_SALT', '7Z2VJpigPo1jjEbcpF8k5AZDC01eLtLsZ5dUh8DkrY0fqVB5b0opwgD9kE/tSwBBn1DEq/9N6Nev6QNCLxoXaQ==');
define('LOGGED_IN_SALT',   'tYT9xe40Xd+h6IkUE+L83rETbePbCaPQqPgkUt8BHXF7z9oyNKnfyoxZp9/UvYv2LpxODNzzF/HFASykJcsvew==');
define('NONCE_SALT',       'BMrFdZjrsjyUgFQoqdTNFOu/+AOmBCD/zZoUtrv0wpHuGNeMxu14yE4m85zKkOCkDQCO7HAQkGEHneEhWGCiAA==');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

// Increase WP Memory Limit
define('WP_MEMORY_LIMIT', '768M');


/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
