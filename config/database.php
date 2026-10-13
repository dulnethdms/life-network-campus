<?php

if (!function_exists('loadEnvFile')) {
    function loadEnvFile(string $path): void
    {
        if (!is_file($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $trimmed = trim($line);
            if ($trimmed === '' || str_starts_with($trimmed, '#')) {
                continue;
            }

            [$key, $value] = array_pad(explode('=', $trimmed, 2), 2, '');
            $key = trim($key);
            $value = trim($value);

            if ($value !== '' && ($value[0] === '"' || $value[0] === "'")) {
                $value = substr($value, 1, -1);
            }

            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
            putenv("{$key}={$value}");
        }
    }
}

loadEnvFile(__DIR__ . '/../.env');

return [
    'host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
    'port' => $_ENV['DB_PORT'] ?? '3306',
    'database' => $_ENV['DB_NAME'] ?? 'life_network_campus',
    'username' => $_ENV['DB_USER'] ?? 'root',
    'password' => $_ENV['DB_PASSWORD'] ?? '',
    'charset' => 'utf8mb4',
    'dsn' => sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=%s',
        $_ENV['DB_HOST'] ?? '127.0.0.1',
        $_ENV['DB_PORT'] ?? '3306',
        $_ENV['DB_NAME'] ?? 'life_network_campus',
        'utf8mb4'
    ),
];
