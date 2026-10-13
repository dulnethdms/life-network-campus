<?php

namespace App\Core;

use App\Controllers\AuthController;
use App\Controllers\ProfileController;

class Router
{
    public function dispatch(string $requestUri): void
    {
        $uri = parse_url($requestUri, PHP_URL_PATH) ?? '/';
        $basePaths = ['/life-network-campus/public', '/life-network-campus'];

        foreach ($basePaths as $basePath) {
            if (str_starts_with($uri, $basePath)) {
                $uri = substr($uri, strlen($basePath));
                break;
            }
        }

        if ($uri === '' || $uri === '/') {
            $uri = '/';
        }

        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $routes = [
            ['GET', '/', [AuthController::class, 'home']],
            ['GET', '/login', [AuthController::class, 'showLogin']],
            ['POST', '/login', [AuthController::class, 'login']],
            ['GET', '/register', [AuthController::class, 'showRegister']],
            ['POST', '/register', [AuthController::class, 'register']],
            ['POST', '/logout', [AuthController::class, 'logout']],
            ['GET', '/dashboard', [ProfileController::class, 'dashboard']],
            ['GET', '/profile', [ProfileController::class, 'showProfile']],
            ['POST', '/profile', [ProfileController::class, 'updateProfile']],
            ['GET', '/settings', [ProfileController::class, 'showSettings']],
            ['POST', '/settings/password', [ProfileController::class, 'changePassword']],
            ['POST', '/settings/deactivate', [ProfileController::class, 'deactivateAccount']],
        ];

        foreach ($routes as [$routeMethod, $routeUri, $handler]) {
            if ($routeMethod === $method && $routeUri === $uri) {
                [$controllerName, $action] = $handler;
                $controller = new $controllerName();
                $controller->$action();
                return;
            }
        }

        http_response_code(404);
        echo '<h1>404 Not Found</h1>';
    }
}
