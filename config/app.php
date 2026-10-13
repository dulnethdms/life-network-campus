<?php

define('APP_NAME', 'Life Network Campus');
define('APP_ENV', $_ENV['APP_ENV'] ?? 'development');
define('APP_DEBUG', filter_var($_ENV['APP_DEBUG'] ?? true, FILTER_VALIDATE_BOOLEAN));
define('APP_URL', $_ENV['APP_URL'] ?? 'http://localhost/life-network-campus');
define('SESSION_LIFETIME', (int) ($_ENV['SESSION_LIFETIME'] ?? 3600));
define('SESSION_NAME', $_ENV['SESSION_NAME'] ?? 'lnc_session');
define('CSRF_SECRET', $_ENV['CSRF_SECRET'] ?? 'change-me');

return [
    'name' => APP_NAME,
    'env' => APP_ENV,
    'debug' => APP_DEBUG,
    'url' => APP_URL,
    'session_lifetime' => SESSION_LIFETIME,
    'session_name' => SESSION_NAME,
    'csrf_secret' => CSRF_SECRET,
];
