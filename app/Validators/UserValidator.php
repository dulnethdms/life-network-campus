<?php

namespace App\Validators;

use App\Repositories\UserRepository;

class UserValidator
{
    public static function validateRegistration(array $data, UserRepository $repository): array
    {
        $errors = [];

        $name = trim((string) ($data['name'] ?? ''));
        if ($name === '' || strlen($name) < 2 || strlen($name) > 120) {
            $errors['name'] = 'Name must be between 2 and 120 characters.';
        }

        $email = strtolower(trim((string) ($data['email'] ?? '')));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address.';
        } elseif ($repository->findByEmail($email)) {
            $errors['email'] = 'This email is already registered.';
        }

        $password = (string) ($data['password'] ?? '');
        if (strlen($password) < 8) {
            $errors['password'] = 'Password must be at least 8 characters long.';
        }

        $year = $data['year_of_study'] ?? null;
        if ($year !== null && (!is_numeric($year) || (int) $year < 1 || (int) $year > 8)) {
            $errors['year_of_study'] = 'Year of study must be between 1 and 8.';
        }

        $bio = trim((string) ($data['bio'] ?? ''));
        if ($bio !== '' && strlen($bio) > 1500) {
            $errors['bio'] = 'Biography should not exceed 1500 characters.';
        }

        return $errors;
    }

    public static function validateProfile(array $data): array
    {
        $errors = [];

        if (isset($data['name']) && (trim((string) $data['name']) === '' || strlen(trim((string) $data['name'])) < 2)) {
            $errors['name'] = 'Name must be at least 2 characters.';
        }

        $email = strtolower(trim((string) ($data['email'] ?? '')));
        if (isset($data['email']) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address.';
        }

        $year = $data['year_of_study'] ?? null;
        if ($year !== null && $year !== '' && (!is_numeric($year) || (int) $year < 1 || (int) $year > 8)) {
            $errors['year_of_study'] = 'Year of study must be between 1 and 8.';
        }

        return $errors;
    }

    public static function validatePasswordChange(array $data): array
    {
        $errors = [];
        $current = (string) ($data['current_password'] ?? '');
        $newPassword = (string) ($data['new_password'] ?? '');
        $confirm = (string) ($data['confirm_password'] ?? '');

        if ($current === '') {
            $errors['current_password'] = 'Current password is required.';
        }

        if (strlen($newPassword) < 8) {
            $errors['new_password'] = 'New password must be at least 8 characters long.';
        }

        if ($newPassword !== $confirm) {
            $errors['confirm_password'] = 'Passwords do not match.';
        }

        return $errors;
    }
}
