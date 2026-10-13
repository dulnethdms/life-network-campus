<?php

namespace App\Core;

use App\Controllers\AuthController;
use App\Controllers\CommunityController;
use App\Controllers\GoalController;
use App\Controllers\OpportunityController;
use App\Controllers\PortfolioController;
use App\Controllers\ProfileController;
use App\Controllers\ProjectController;

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
            ['GET', '/goals', [GoalController::class, 'index']],
            ['GET', '/goals/new', [GoalController::class, 'create']],
            ['POST', '/goals/store', [GoalController::class, 'store']],
            ['GET', '/goals/{id}/edit', [GoalController::class, 'edit']],
            ['POST', '/goals/{id}/update', [GoalController::class, 'update']],
            ['POST', '/goals/{id}/delete', [GoalController::class, 'delete']],
            ['POST', '/goals/{id}/steps/add', [GoalController::class, 'addStep']],
            ['POST', '/goals/{id}/steps/{stepId}/toggle', [GoalController::class, 'toggleStep']],
            ['GET', '/projects', [ProjectController::class, 'index']],
            ['GET', '/projects/new', [ProjectController::class, 'create']],
            ['POST', '/projects/store', [ProjectController::class, 'store']],
            ['GET', '/projects/{id}/edit', [ProjectController::class, 'edit']],
            ['POST', '/projects/{id}/update', [ProjectController::class, 'update']],
            ['POST', '/projects/{id}/delete', [ProjectController::class, 'delete']],
            ['GET', '/communities', [CommunityController::class, 'index']],
            ['GET', '/communities/new', [CommunityController::class, 'create']],
            ['POST', '/communities/store', [CommunityController::class, 'store']],
            ['GET', '/communities/{id}/edit', [CommunityController::class, 'edit']],
            ['POST', '/communities/{id}/update', [CommunityController::class, 'update']],
            ['POST', '/communities/{id}/delete', [CommunityController::class, 'delete']],
            ['GET', '/opportunities', [OpportunityController::class, 'index']],
            ['GET', '/opportunities/new', [OpportunityController::class, 'create']],
            ['POST', '/opportunities/store', [OpportunityController::class, 'store']],
            ['GET', '/opportunities/{id}/edit', [OpportunityController::class, 'edit']],
            ['POST', '/opportunities/{id}/update', [OpportunityController::class, 'update']],
            ['POST', '/opportunities/{id}/delete', [OpportunityController::class, 'delete']],
            ['GET', '/opportunities/discover', [OpportunityController::class, 'discover']],
            ['POST', '/opportunities/{id}/save', [OpportunityController::class, 'save']],
            ['POST', '/opportunities/{id}/unsave', [OpportunityController::class, 'unsave']],
            ['POST', '/opportunities/{id}/apply', [OpportunityController::class, 'apply']],
            ['POST', '/opportunities/{id}/withdraw', [OpportunityController::class, 'withdraw']],
            ['GET', '/portfolio', [PortfolioController::class, 'index']],
            ['GET', '/portfolio/new', [PortfolioController::class, 'create']],
            ['POST', '/portfolio/store', [PortfolioController::class, 'store']],
            ['GET', '/portfolio/{id}/edit', [PortfolioController::class, 'edit']],
            ['POST', '/portfolio/{id}/update', [PortfolioController::class, 'update']],
            ['POST', '/portfolio/{id}/delete', [PortfolioController::class, 'delete']],
        ];

        foreach ($routes as [$routeMethod, $routeUri, $handler]) {
            if ($routeMethod !== $method) {
                continue;
            }

            if ($routeUri === $uri) {
                $this->callHandler($handler);
                return;
            }

            if ($this->routeMatches($routeUri, $uri)) {
                $this->callHandler($handler, $this->extractParams($routeUri, $uri));
                return;
            }
        }

        http_response_code(404);
        echo '<h1>404 Not Found</h1>';
    }

    private function routeMatches(string $routeUri, string $uri): bool
    {
        if (str_contains($routeUri, '{') === false) {
            return false;
        }

        $pattern = preg_quote($routeUri, '#');
        $pattern = preg_replace('/\\{([a-zA-Z_][a-zA-Z0-9_]*)\\}/', '([^/]+)', $pattern);

        return (bool) preg_match('#^' . $pattern . '$#', $uri);
    }

    private function extractParams(string $routeUri, string $uri): array
    {
        preg_match_all('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', $routeUri, $matches, PREG_SET_ORDER);
        $pattern = preg_quote($routeUri, '#');
        $pattern = preg_replace('/\\{([a-zA-Z_][a-zA-Z0-9_]*)\\}/', '([^/]+)', $pattern);
        preg_match('#^' . $pattern . '$#', $uri, $routeMatches);

        $params = [];
        foreach ($matches as $index => $match) {
            $key = $match[1];
            $params[$key] = $routeMatches[$index + 1] ?? null;
        }

        return $params;
    }

    private function callHandler(array $handler, array $params = []): void
    {
        [$controllerName, $action] = $handler;
        $controller = new $controllerName();

        if ($params === []) {
            $controller->$action();
            return;
        }

        $values = array_values($params);
        $controller->$action(...$values);
    }
}
