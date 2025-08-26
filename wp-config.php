<?php
/**
 * wp-config.php (Wasmer Edge / MySQL Instant DB)
 */

/* --- HTTPS/Proxy hinter Wasmer --- */
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strpos($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') !== false) {
    $_SERVER['HTTPS'] = 'on';
}
if (!defined('FORCE_SSL_ADMIN')) define('FORCE_SSL_ADMIN', true);

/* --- Datenbank aus Wasmer-Umgebungsvariablen --- */
$host = getenv('DB_HOST');
$port = getenv('DB_PORT') ?: '3306';
$name = getenv('DB_NAME');
$user = getenv('DB_USERNAME') ?: getenv('DB_UI') ?: getenv('DB_USER'); // Fallback für DB_UI
$pass = getenv('DB_PASSWORD');

foreach (['DB_HOST'=>$host,'DB_PORT'=>$port,'DB_NAME'=>$name,'DB_USER'=>$user,'DB_PASSWORD'=>$pass] as $k => $v) {
    if ($v === false || $v === '' || $v === null) {
        die('Missing required database env: '.$k);
    }
}

define('DB_NAME',     $name);
define('DB_USER',     $user);
define('DB_PASSWORD', $pass);
define('DB_HOST',     (strpos($host, ':') !== false ? $host : "{$host}:{$port}"));

if (!defined('DB_CHARSET')) define('DB_CHARSET', 'utf8mb4');
if (!defined('DB_COLLATE')) define('DB_COLLATE', '');

/* TLS für MySQL (üblich bei Edge/Managed DBs) */
if (!defined('MYSQL_CLIENT_FLAGS')) {
    define('MYSQL_CLIENT_FLAGS', MYSQLI_CLIENT_SSL);
}

/*  Authentication Keys/Salts – echte Werte einsetzen! */
define('AUTH_KEY',         'REPLACE_ME');
define('SECURE_AUTH_KEY',  'REPLACE_ME');
define('LOGGED_IN_KEY',    'REPLACE_ME');
define('NONCE_KEY',        'REPLACE_ME');
define('AUTH_SALT',        'REPLACE_ME');
define('SECURE_AUTH_SALT', 'REPLACE_ME');
define('LOGGED_IN_SALT',   'REPLACE_ME');
define('NONCE_SALT',       'REPLACE_ME');

/* Tabellenpräfix */
$table_prefix = 'wp_';

/* Debug – Anzeige im HTML aus, ins Log an */
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
@ini_set('display_errors', 0);

/* That's all, stop editing! */
if (!defined('ABSPATH')) define('ABSPATH', __DIR__ . '/');
require_once ABSPATH . 'wp-settings.php';