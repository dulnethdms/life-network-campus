<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;
use PDOException;

class ProjectRepository
{
    public function listForUser(int $userId): array
    {
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare('SELECT * FROM projects WHERE owner_id = :userId ORDER BY created_at DESC');
            $stmt->execute(['userId' => $userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function findById(int $projectId, int $userId): ?array
    {
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare('SELECT * FROM projects WHERE id = :projectId AND owner_id = :userId LIMIT 1');
            $stmt->execute(['projectId' => $projectId, 'userId' => $userId]);
            $project = $stmt->fetch(PDO::FETCH_ASSOC);
            return $project ?: null;
        } catch (PDOException $e) {
            return null;
        }
    }

    public function create(int $userId, array $data): ?array
    {
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare(
                'INSERT INTO projects (owner_id, title, description, category, status, deadline, created_at, updated_at)
                 VALUES (:owner_id, :title, :description, :category, :status, :deadline, NOW(), NOW())'
            );

            $stmt->execute([
                'owner_id' => $userId,
                'title' => trim((string) ($data['title'] ?? '')),
                'description' => trim((string) ($data['description'] ?? '')),
                'category' => $data['category'] ?? 'Academic',
                'status' => $data['status'] ?? 'Open',
                'deadline' => $data['deadline'] ?? null,
            ]);

            $id = (int) $pdo->lastInsertId();
            return $this->findById($id, $userId);
        } catch (PDOException $e) {
            return null;
        }
    }

    public function update(int $projectId, int $userId, array $data): bool
    {
        try {
            $stmt = Database::getConnection()->prepare(
                'UPDATE projects SET title = :title, description = :description, category = :category, status = :status, deadline = :deadline, updated_at = NOW() WHERE id = :projectId AND owner_id = :userId'
            );

            return $stmt->execute([
                'title' => trim((string) ($data['title'] ?? '')),
                'description' => trim((string) ($data['description'] ?? '')),
                'category' => $data['category'] ?? 'Academic',
                'status' => $data['status'] ?? 'Open',
                'deadline' => $data['deadline'] ?? null,
                'projectId' => $projectId,
                'userId' => $userId,
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function delete(int $projectId, int $userId): bool
    {
        try {
            $stmt = Database::getConnection()->prepare('DELETE FROM projects WHERE id = :projectId AND owner_id = :userId');
            return $stmt->execute(['projectId' => $projectId, 'userId' => $userId]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getDashboardProjects(int $userId): array
    {
        $projects = $this->listForUser($userId);
        return array_slice($projects, 0, 3);
    }
}
