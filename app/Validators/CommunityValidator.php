<?php

namespace App\Validators;

class CommunityValidator
{
    public static function validateCommunity(array $data): array
    {
        $errors = [];

        $name = trim((string) ($data['name'] ?? ''));
        if ($name === '' || strlen($name) < 3 || strlen($name) > 150) {
            $errors['name'] = 'Community name must be between 3 and 150 characters.';
        }

        $description = trim((string) ($data['description'] ?? ''));
        if ($description === '' || strlen($description) < 10 || strlen($description) > 2000) {
            $errors['description'] = 'Description must be between 10 and 2000 characters.';
        }

        $category = trim((string) ($data['category'] ?? ''));
        if (!in_array($category, ['Academic', 'Career', 'Research', 'Community', 'Creative', 'Leadership'], true)) {
            $errors['category'] = 'Please choose a valid category.';
        }

        return $errors;
    }
}
