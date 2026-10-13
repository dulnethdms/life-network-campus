<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;
use PDOException;

class CommunityRepository
{
    public function listForUser(int $userId): array
    {
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare(
                'SELECT DISTINCT c.* FROM communities c
                 LEFT JOIN community_members cm ON cm.community_id = c.id
                 WHERE c.creator_id = :userId OR cm.user_id = :userId
                 ORDER BY c.created_at DESC'
            );
            $stmt->execute(['userId' => $userId]);
            $communities = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($communities as &$community) {
                $community['member_count'] = $this->countMembers((int) $community['id']);
            }

            return $communities;
        } catch (PDOException $e) {
            return [];
        }
    }

    public function findById(int $communityId, int $userId): ?array
    {
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare(
                'SELECT c.* FROM communities c
                 LEFT JOIN community_members cm ON cm.community_id = c.id
                 WHERE c.id = :communityId AND (c.creator_id = :userId OR cm.user_id = :userId)
                 LIMIT 1'
            );
            $stmt->execute(['communityId' => $communityId, 'userId' => $userId]);
            $community = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$community) {
                return null;
            }

            $community['member_count'] = $this->countMembers((int) $community['id']);
            return $community;
        } catch (PDOException $e) {
            return null;
        }
    }

    public function create(int $userId, array $data): ?array
    {
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare(
                'INSERT INTO communities (creator_id, name, description, category, created_at, updated_at)
                 VALUES (:creator_id, :name, :description, :category, NOW(), NOW())'
            );

            $stmt->execute([
                'creator_id' => $userId,
                'name' => trim((string) ($data['name'] ?? '')),
                'description' => trim((string) ($data['description'] ?? '')),
                'category' => $data['category'] ?? 'Academic',
            ]);

            $communityId = (int) $pdo->lastInsertId();
            $this->addMember($communityId, $userId, 'admin');
            return $this->findById($communityId, $userId);
        } catch (PDOException $e) {
            return null;
        }
    }

    public function update(int $communityId, int $userId, array $data): bool
    {
        try {
            $stmt = Database::getConnection()->prepare(
                'UPDATE communities SET name = :name, description = :description, category = :category, updated_at = NOW() WHERE id = :communityId AND creator_id = :userId'
            );

            return $stmt->execute([
                'name' => trim((string) ($data['name'] ?? '')),
                'description' => trim((string) ($data['description'] ?? '')),
                'category' => $data['category'] ?? 'Academic',
                'communityId' => $communityId,
                'userId' => $userId,
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function delete(int $communityId, int $userId): bool
    {
        try {
            $stmt = Database::getConnection()->prepare('DELETE FROM communities WHERE id = :communityId AND creator_id = :userId');
            return $stmt->execute(['communityId' => $communityId, 'userId' => $userId]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function countMembers(int $communityId): int
    {
        try {
            $stmt = Database::getConnection()->prepare('SELECT COUNT(*) FROM community_members WHERE community_id = :communityId');
            $stmt->execute(['communityId' => $communityId]);
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function addMember(int $communityId, int $userId, string $role = 'member'): bool
    {
        try {
            $stmt = Database::getConnection()->prepare(
                'INSERT INTO community_members (community_id, user_id, role, joined_at)
                 VALUES (:community_id, :user_id, :role, NOW())
                 ON DUPLICATE KEY UPDATE role = VALUES(role)'
            );

            return $stmt->execute([
                'community_id' => $communityId,
                'user_id' => $userId,
                'role' => $role,
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getDashboardCommunities(int $userId): array
    {
        $communities = $this->listForUser($userId);
        return array_slice($communities, 0, 3);
    }
}
