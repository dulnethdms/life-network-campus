<?php

session_start();
$_SESSION['user_id'] = 1;

$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/life-network-campus/public/portfolio';

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/Helpers/helpers.php';

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../app/';

    if (str_starts_with($class, $prefix)) {
        $relativeClass = substr($class, strlen($prefix));
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

        if (file_exists($file)) {
            require_once $file;
        }
    }
});

ob_start();
require __DIR__ . '/../routes/web.php';
$output = ob_get_clean();

if (strpos($output, 'My portfolio') === false) {
    fwrite(STDERR, "FAIL: portfolio index did not render\n");
    exit(1);
}

fwrite(STDOUT, "PASS: portfolio index rendered\n");
