<?php

function env(string $key, mixed $default = null): mixed
{
    $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);

    if ($value === false || $value === null || $value === '') {
        return $default;
    }

    return $value;
}

function config(string $key, mixed $default = null): mixed
{
    static $config = null;

    if ($config === null) {
        $config = require __DIR__ . '/../../config/app.php';
    }

    return $config[$key] ?? $default;
}

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function csrfToken(): string
{
    if (empty($_SESSION['_csrf_token'])) {
        $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['_csrf_token'];
}

function verifyCsrfToken(?string $token): bool
{
    if (!is_string($token)) {
        return false;
    }

    return hash_equals($_SESSION['_csrf_token'] ?? '', $token);
}

function jsonResponse(int $statusCode, array $payload): void
{
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    exit;
}

function renderView(string $viewName, array $data = []): void
{
    $template = __DIR__ . '/../Views/' . $viewName . '.php';
    if (!file_exists($template)) {
        throw new RuntimeException('View not found: ' . $viewName);
    }

    extract($data);
    include $template;
}

function redirect(string $path): void
{
    $target = str_starts_with($path, 'http') ? $path : '/life-network-campus' . $path;
    header('Location: ' . $target);
    exit;
}
