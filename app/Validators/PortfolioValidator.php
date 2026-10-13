<?php

namespace App\Validators;

class PortfolioValidator
{
    public static function validate(array $data): array
    {
        $errors = [];
        $title = trim((string) ($data['title'] ?? ''));
        $description = trim((string) ($data['description'] ?? ''));

        if ($title === '' || strlen($title) < 3 || strlen($title) > 180) {
            $errors['title'] = 'Title must be between 3 and 180 characters.';
        }
        if ($description === '' || strlen($description) < 10 || strlen($description) > 3000) {
            $errors['description'] = 'Description must be between 10 and 3000 characters.';
        }
        if (!in_array(($data['item_type'] ?? ''), ['Project', 'Achievement', 'Certificate', 'Research', 'Publication', 'Other'], true)) {
            $errors['item_type'] = 'Please choose a valid item type.';
        }
        if (!in_array(($data['visibility'] ?? ''), ['public', 'private'], true)) {
            $errors['visibility'] = 'Please choose a valid visibility setting.';
        }

        return $errors;
    }
}
