<?php

namespace App\Validators;

class ProjectValidator
{
    public static function validateProject(array $data): array
    {
        $errors = [];

        $title = trim((string) ($data['title'] ?? ''));
        if ($title === '' || strlen($title) < 3 || strlen($title) > 180) {
            $errors['title'] = 'Project title must be between 3 and 180 characters.';
        }

        $description = trim((string) ($data['description'] ?? ''));
        if ($description === '' || strlen($description) < 10 || strlen($description) > 3000) {
            $errors['description'] = 'Description must be between 10 and 3000 characters.';
        }

        $category = trim((string) ($data['category'] ?? ''));
        if (!in_array($category, ['Academic', 'Research', 'Startup', 'Community', 'Creative', 'Career'], true)) {
            $errors['category'] = 'Please choose a valid category.';
        }

        $status = trim((string) ($data['status'] ?? ''));
        if (!in_array($status, ['Open', 'In Progress', 'Completed', 'On Hold'], true)) {
            $errors['status'] = 'Please choose a valid status.';
        }

        return $errors;
    }
}
