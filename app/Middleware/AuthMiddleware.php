<?php

namespace App\Middleware;

use App\Repositories\UserRepository;

class AuthMiddleware
{
    public static function requireLogin(): void
    {
        if (!self::isAuthenticated()) {
            header('Location: /life-network-campus/public/login');
            exit;
        }
    }

    public static function isAuthenticated(): bool
    {
        return isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id']);
    }

    public static function currentUser(): ?array
    {
        if (!self::isAuthenticated()) {
            return null;
        }

        $userRepository = new UserRepository();
        return $userRepository->findById((int) $_SESSION['user_id']);
    }

    public static function requireCsrf(): void
    {
        $token = $_POST['csrf_token'] ?? null;
        if (!verifyCsrfToken($token)) {
            http_response_code(419);
            echo 'Invalid or missing CSRF token.';
            exit;
        }
    }
}
