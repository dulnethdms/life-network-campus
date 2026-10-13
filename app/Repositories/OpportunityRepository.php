<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;
use PDOException;

class OpportunityRepository
{
    public function listDiscoverableForUser(int $userId): array
    {
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare(
                'SELECT o.*, u.name AS creator_name,
                        saved.id IS NOT NULL AS is_saved,
                        applications.id IS NOT NULL AS has_applied
                 FROM opportunities o
                 INNER JOIN users u ON u.id = o.created_by
                 LEFT JOIN saved_opportunities saved ON saved.opportunity_id = o.id AND saved.user_id = :savedUserId
                 LEFT JOIN opportunity_applications applications ON applications.opportunity_id = o.id AND applications.user_id = :applicationUserId
                 WHERE o.status <> :closedStatus
                 ORDER BY o.deadline IS NULL, o.deadline ASC, o.created_at DESC'
            );
            $stmt->execute([
                'savedUserId' => $userId,
                'applicationUserId' => $userId,
                'closedStatus' => 'Closed',
            ]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function listForUser(int $userId): array
    {
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare('SELECT * FROM opportunities WHERE created_by = :userId ORDER BY created_at DESC');
            $stmt->execute(['userId' => $userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function findById(int $opportunityId, int $userId): ?array
    {
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare('SELECT * FROM opportunities WHERE id = :opportunityId AND created_by = :userId LIMIT 1');
            $stmt->execute(['opportunityId' => $opportunityId, 'userId' => $userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            return null;
        }
    }

    public function create(int $userId, array $data): ?array
    {
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare(
                'INSERT INTO opportunities (created_by, title, description, category, opportunity_type, status, deadline, skills_required, organization_name, application_instructions, created_at, updated_at)
                 VALUES (:created_by, :title, :description, :category, :opportunity_type, :status, :deadline, :skills_required, :organization_name, :application_instructions, NOW(), NOW())'
            );

            $stmt->execute([
                'created_by' => $userId,
                'title' => trim((string) ($data['title'] ?? '')),
                'description' => trim((string) ($data['description'] ?? '')),
                'category' => $data['category'] ?? 'Career',
                'opportunity_type' => $data['opportunity_type'] ?? 'Internship',
                'status' => $data['status'] ?? 'Open',
                'deadline' => $data['deadline'] ?? null,
                'skills_required' => trim((string) ($data['skills_required'] ?? '')) !== '' ? trim((string) $data['skills_required']) : null,
                'organization_name' => trim((string) ($data['organization_name'] ?? '')) !== '' ? trim((string) $data['organization_name']) : null,
                'application_instructions' => trim((string) ($data['application_instructions'] ?? '')) !== '' ? trim((string) $data['application_instructions']) : null,
            ]);

            $id = (int) $pdo->lastInsertId();
            return $this->findById($id, $userId);
        } catch (PDOException $e) {
            return null;
        }
    }

    public function update(int $opportunityId, int $userId, array $data): bool
    {
        try {
            $stmt = Database::getConnection()->prepare(
                'UPDATE opportunities SET title = :title, description = :description, category = :category, opportunity_type = :opportunity_type, status = :status, deadline = :deadline, skills_required = :skills_required, organization_name = :organization_name, application_instructions = :application_instructions, updated_at = NOW()
                 WHERE id = :opportunityId AND created_by = :userId'
            );

            return $stmt->execute([
                'title' => trim((string) ($data['title'] ?? '')),
                'description' => trim((string) ($data['description'] ?? '')),
                'category' => $data['category'] ?? 'Career',
                'opportunity_type' => $data['opportunity_type'] ?? 'Internship',
                'status' => $data['status'] ?? 'Open',
                'deadline' => $data['deadline'] ?? null,
                'skills_required' => trim((string) ($data['skills_required'] ?? '')) !== '' ? trim((string) $data['skills_required']) : null,
                'organization_name' => trim((string) ($data['organization_name'] ?? '')) !== '' ? trim((string) $data['organization_name']) : null,
                'application_instructions' => trim((string) ($data['application_instructions'] ?? '')) !== '' ? trim((string) $data['application_instructions']) : null,
                'opportunityId' => $opportunityId,
                'userId' => $userId,
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function delete(int $opportunityId, int $userId): bool
    {
        try {
            $stmt = Database::getConnection()->prepare('DELETE FROM opportunities WHERE id = :opportunityId AND created_by = :userId');
            return $stmt->execute(['opportunityId' => $opportunityId, 'userId' => $userId]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function save(int $opportunityId, int $userId): bool
    {
        try {
            $stmt = Database::getConnection()->prepare(
                'INSERT IGNORE INTO saved_opportunities (user_id, opportunity_id) VALUES (:userId, :opportunityId)'
            );
            return $stmt->execute(['userId' => $userId, 'opportunityId' => $opportunityId]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function unsave(int $opportunityId, int $userId): bool
    {
        try {
            $stmt = Database::getConnection()->prepare(
                'DELETE FROM saved_opportunities WHERE user_id = :userId AND opportunity_id = :opportunityId'
            );
            return $stmt->execute(['userId' => $userId, 'opportunityId' => $opportunityId]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function apply(int $opportunityId, int $userId, string $message): bool
    {
        try {
            $stmt = Database::getConnection()->prepare(
                'INSERT INTO opportunity_applications (opportunity_id, user_id, message)
                 SELECT :opportunityId, :userId, :message
                 FROM opportunities
                 WHERE id = :opportunityIdCheck AND status <> :closedStatus
                   AND created_by <> :userIdCheck
                 ON DUPLICATE KEY UPDATE message = VALUES(message), status = :submittedStatus, updated_at = NOW()'
            );
            return $stmt->execute([
                'opportunityId' => $opportunityId,
                'userId' => $userId,
                'message' => $message !== '' ? $message : null,
                'opportunityIdCheck' => $opportunityId,
                'closedStatus' => 'Closed',
                'userIdCheck' => $userId,
                'submittedStatus' => 'submitted',
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function withdrawApplication(int $opportunityId, int $userId): bool
    {
        try {
            $stmt = Database::getConnection()->prepare(
                'DELETE FROM opportunity_applications WHERE opportunity_id = :opportunityId AND user_id = :userId'
            );
            return $stmt->execute(['opportunityId' => $opportunityId, 'userId' => $userId]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getDashboardOpportunities(int $userId): array
    {
        $opportunities = $this->listForUser($userId);
        return array_slice($opportunities, 0, 3);
    }
}
