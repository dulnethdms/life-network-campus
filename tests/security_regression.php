<?php

session_start();
require_once __DIR__ . '/../app/Helpers/helpers.php';

$escaped = e('<script>alert("x")</script>');
if ($escaped !== '&lt;script&gt;alert(&quot;x&quot;)&lt;/script&gt;') {
    fwrite(STDERR, "FAIL: HTML escaping regression\n");
    exit(1);
}

$_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
if (!verifyCsrfToken($_SESSION['_csrf_token'])) {
    fwrite(STDERR, "FAIL: valid CSRF token rejected\n");
    exit(1);
}
if (verifyCsrfToken('invalid-token')) {
    fwrite(STDERR, "FAIL: invalid CSRF token accepted\n");
    exit(1);
}

$index = file_get_contents(__DIR__ . '/../public/index.php');
$middleware = file_get_contents(__DIR__ . '/../app/Middleware/AuthMiddleware.php');
$styles = file_get_contents(__DIR__ . '/../public/assets/css/styles.css');

foreach ([
    'X-Content-Type-Options: nosniff',
    'Content-Security-Policy:',
] as $securityHeader) {
    if (strpos($index, $securityHeader) === false) {
        fwrite(STDERR, "FAIL: missing security header configuration\n");
        exit(1);
    }
}

if (strpos($middleware, '/life-network-campus/public/login') === false) {
    fwrite(STDERR, "FAIL: protected redirect does not target public login route\n");
    exit(1);
}

if (strpos($styles, '@media (max-width: 700px)') === false) {
    fwrite(STDERR, "FAIL: responsive stylesheet rule missing\n");
    exit(1);
}

fwrite(STDOUT, "PASS: security and responsive regression checks\n");
