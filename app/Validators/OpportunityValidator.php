<?php

namespace App\Validators;

class OpportunityValidator
{
    public static function validateOpportunity(array $data): array
    {
        $errors = [];

        $title = trim((string) ($data['title'] ?? ''));
        if ($title === '' || strlen($title) < 3 || strlen($title) > 180) {
            $errors['title'] = 'Title must be between 3 and 180 characters.';
        }

        $description = trim((string) ($data['description'] ?? ''));
        if ($description === '' || strlen($description) < 10 || strlen($description) > 3000) {
            $errors['description'] = 'Description must be between 10 and 3000 characters.';
        }

        $category = trim((string) ($data['category'] ?? ''));
        if (!in_array($category, ['Career', 'Academic', 'Research', 'Funding', 'Community', 'Leadership'], true)) {
            $errors['category'] = 'Please choose a valid category.';
        }

        $opportunityType = trim((string) ($data['opportunity_type'] ?? ''));
        if (!in_array($opportunityType, ['Internship', 'Scholarship', 'Research', 'Competition', 'Job', 'Mentorship'], true)) {
            $errors['opportunity_type'] = 'Please choose a valid opportunity type.';
        }

        $status = trim((string) ($data['status'] ?? ''));
        if (!in_array($status, ['Open', 'Closed', 'Reviewing'], true)) {
            $errors['status'] = 'Please choose a valid status.';
        }

        return $errors;
    }
}
