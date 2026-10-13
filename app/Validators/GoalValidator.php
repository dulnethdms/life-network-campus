<?php

namespace App\Validators;

class GoalValidator
{
    public static function validateGoal(array $data): array
    {
        $errors = [];

        $title = trim((string) ($data['title'] ?? ''));
        if ($title === '' || strlen($title) < 3 || strlen($title) > 150) {
            $errors['title'] = 'Goal title must be between 3 and 150 characters.';
        }

        $description = trim((string) ($data['description'] ?? ''));
        if ($description === '' || strlen($description) < 10 || strlen($description) > 2000) {
            $errors['description'] = 'Description must be between 10 and 2000 characters.';
        }

        $category = trim((string) ($data['category'] ?? ''));
        if (!in_array($category, ['Personal Growth', 'Relationships', 'Career', 'Learning', 'Contribution'], true)) {
            $errors['category'] = 'Please choose a valid category.';
        }

        $status = trim((string) ($data['status'] ?? ''));
        if (!in_array($status, ['Not Started', 'In Progress', 'Completed', 'Paused'], true)) {
            $errors['status'] = 'Please choose a valid goal status.';
        }

        return $errors;
    }
}
