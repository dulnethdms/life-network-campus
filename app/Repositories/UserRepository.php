<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class UserRepository
{
    public function findByEmail(string $email): ?array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => strtolower(trim($email))]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    public function findById(int $id): ?array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    public function create(array $data): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'INSERT INTO users (name, email, password_hash, university, field_of_study, year_of_study, bio, interests, skills, career_interests, is_active, created_at, updated_at)
             VALUES (:name, :email, :password_hash, :university, :field_of_study, :year_of_study, :bio, :interests, :skills, :career_interests, :is_active, NOW(), NOW())'
        );

        $stmt->execute([
            'name' => trim($data['name']),
            'email' => strtolower(trim($data['email'])),
            'password_hash' => $data['password_hash'],
            'university' => $data['university'] ?? null,
            'field_of_study' => $data['field_of_study'] ?? null,
            'year_of_study' => $data['year_of_study'] ?? null,
            'bio' => $data['bio'] ?? null,
            'interests' => $data['interests'] ?? null,
            'skills' => $data['skills'] ?? null,
            'career_interests' => $data['career_interests'] ?? null,
            'is_active' => 1,
        ]);

        return $this->findById((int) $pdo->lastInsertId());
    }

    public function update(int $id, array $data): bool
    {
        $fields = [];
        $params = ['id' => $id];

        foreach ($data as $key => $value) {
            if ($key === 'id') {
                continue;
            }
            $fields[] = $key . ' = :' . $key;
            $params[$key] = $value;
        }

        if ($fields === []) {
            return false;
        }

        $fields[] = 'updated_at = NOW()';
        $sql = 'UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = :id';

        $stmt = Database::getConnection()->prepare($sql);
        return $stmt->execute($params);
    }

    public function updatePassword(int $id, string $newHash): bool
    {
        $stmt = Database::getConnection()->prepare('UPDATE users SET password_hash = :password_hash, updated_at = NOW() WHERE id = :id');
        return $stmt->execute([
            'password_hash' => $newHash,
            'id' => $id,
        ]);
    }

    public function deactivate(int $id): bool
    {
        $stmt = Database::getConnection()->prepare('UPDATE users SET is_active = 0, updated_at = NOW() WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
