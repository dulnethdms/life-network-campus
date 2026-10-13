<?php

namespace App\Controllers;

use App\Middleware\AuthMiddleware;
use App\Repositories\UserRepository;
use App\Validators\UserValidator;

class AuthController
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function home(): void
    {
        if (AuthMiddleware::isAuthenticated()) {
            redirect('/dashboard');
        }

        renderView('auth/landing', ['title' => 'Welcome']);
    }

    public function showLogin(): void
    {
        if (AuthMiddleware::isAuthenticated()) {
            redirect('/dashboard');
        }

        renderView('auth/login', ['title' => 'Log in', 'errors' => [], 'values' => []]);
    }

    public function login(): void
    {
        if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            renderView('auth/login', ['title' => 'Log in', 'errors' => ['csrf' => 'Invalid security token.'], 'values' => $_POST]);
            return;
        }

        $email = strtolower(trim((string) ($_POST['email'] ?? '')));
        $password = (string) ($_POST['password'] ?? '');

        if ($email === '' || $password === '') {
            renderView('auth/login', ['title' => 'Log in', 'errors' => ['credentials' => 'Email and password are required.'], 'values' => ['email' => $email]]);
            return;
        }

        $user = $this->userRepository->findByEmail($email);
        if (!$user || !password_verify($password, $user['password_hash'])) {
            renderView('auth/login', ['title' => 'Log in', 'errors' => ['credentials' => 'Incorrect email or password.'], 'values' => ['email' => $email]]);
            return;
        }

        if ((int) $user['is_active'] !== 1) {
            renderView('auth/login', ['title' => 'Log in', 'errors' => ['credentials' => 'This account has been deactivated.'], 'values' => ['email' => $email]]);
            return;
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['user_name'] = $user['name'];

        redirect('/dashboard');
    }

    public function showRegister(): void
    {
        if (AuthMiddleware::isAuthenticated()) {
            redirect('/dashboard');
        }

        renderView('auth/register', ['title' => 'Create account', 'errors' => [], 'values' => []]);
    }

    public function register(): void
    {
        if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            renderView('auth/register', ['title' => 'Create account', 'errors' => ['csrf' => 'Invalid security token.'], 'values' => $_POST]);
            return;
        }

        $data = $_POST;
        $errors = UserValidator::validateRegistration($data, $this->userRepository);

        if ($errors) {
            renderView('auth/register', ['title' => 'Create account', 'errors' => $errors, 'values' => $data]);
            return;
        }

        $user = $this->userRepository->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
            'university' => trim((string) ($data['university'] ?? '')) !== '' ? trim((string) $data['university']) : null,
            'field_of_study' => trim((string) ($data['field_of_study'] ?? '')) !== '' ? trim((string) $data['field_of_study']) : null,
            'year_of_study' => isset($data['year_of_study']) && $data['year_of_study'] !== '' ? (int) $data['year_of_study'] : null,
            'bio' => trim((string) ($data['bio'] ?? '')) !== '' ? trim((string) $data['bio']) : null,
            'interests' => trim((string) ($data['interests'] ?? '')) !== '' ? trim((string) $data['interests']) : null,
            'skills' => trim((string) ($data['skills'] ?? '')) !== '' ? trim((string) $data['skills']) : null,
            'career_interests' => trim((string) ($data['career_interests'] ?? '')) !== '' ? trim((string) $data['career_interests']) : null,
        ]);

        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['user_name'] = $user['name'];

        redirect('/dashboard');
    }

    public function logout(): void
    {
        if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            redirect('/login');
        }

        session_unset();
        session_destroy();
        redirect('/login');
    }
}
