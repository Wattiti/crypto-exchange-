<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'your_db_username');
define('DB_PASS', 'your_db_password');
define('DB_NAME', 'crypto_exchange');

// Site configuration
define('SITE_NAME', 'Crypto Exchange');
define('SITE_URL', 'https://yourdomain.com');
define('SITE_EMAIL', 'support@yourdomain.com');

// API Keys
define('COINMARKETCAP_API', 'your_api_key');
define('BINANCE_API', 'your_api_key');

// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session settings
session_set_cookie_params([
    'lifetime' => 86400,
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Strict'
]);
session_start();
?>
