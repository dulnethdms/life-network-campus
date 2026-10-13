<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;
use PDOException;

class PortfolioRepository
{
    public function listForUser(int $userId): array
    {
        try {
            $stmt = Database::getConnection()->prepare('SELECT * FROM portfolio_items WHERE user_id = :userId ORDER BY created_at DESC');
            $stmt->execute(['userId' => $userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function findById(int $itemId, int $userId): ?array
    {
        try {
            $stmt = Database::getConnection()->prepare('SELECT * FROM portfolio_items WHERE id = :itemId AND user_id = :userId LIMIT 1');
            $stmt->execute(['itemId' => $itemId, 'userId' => $userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            return null;
        }
    }

    public function create(int $userId, array $data): ?array
    {
        try {
            $stmt = Database::getConnection()->prepare(
                'INSERT INTO portfolio_items (user_id, title, description, item_type, visibility) VALUES (:userId, :title, :description, :itemType, :visibility)'
            );
            $stmt->execute([
                'userId' => $userId,
                'title' => trim((string) ($data['title'] ?? '')),
                'description' => trim((string) ($data['description'] ?? '')),
                'itemType' => $data['item_type'] ?? 'Project',
                'visibility' => $data['visibility'] ?? 'public',
            ]);
            return $this->findById((int) Database::getConnection()->lastInsertId(), $userId);
        } catch (PDOException $e) {
            return null;
        }
    }

    public function update(int $itemId, int $userId, array $data): bool
    {
        try {
            $stmt = Database::getConnection()->prepare(
                'UPDATE portfolio_items SET title = :title, description = :description, item_type = :itemType, visibility = :visibility, updated_at = NOW()
                 WHERE id = :itemId AND user_id = :userId'
            );
            return $stmt->execute([
                'title' => trim((string) ($data['title'] ?? '')),
                'description' => trim((string) ($data['description'] ?? '')),
                'itemType' => $data['item_type'] ?? 'Project',
                'visibility' => $data['visibility'] ?? 'public',
                'itemId' => $itemId,
                'userId' => $userId,
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function delete(int $itemId, int $userId): bool
    {
        try {
            $stmt = Database::getConnection()->prepare('DELETE FROM portfolio_items WHERE id = :itemId AND user_id = :userId');
            return $stmt->execute(['itemId' => $itemId, 'userId' => $userId]);
        } catch (PDOException $e) {
            return false;
        }
    }
}
