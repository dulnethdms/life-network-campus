<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;
use PDOException;

class GoalRepository
{
    public function listForUser(int $userId): array
    {
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare('SELECT * FROM user_goals WHERE user_id = :userId ORDER BY created_at DESC');
            $stmt->execute(['userId' => $userId]);
            $goals = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($goals as &$goal) {
                $goal['steps'] = $this->stepsForGoal((int) $goal['id']);
                $goal['progress'] = $this->calculateProgress((int) $goal['id']);
            }

            return $goals;
        } catch (PDOException $e) {
            return [];
        }
    }

    public function findById(int $goalId, int $userId): ?array
    {
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare('SELECT * FROM user_goals WHERE id = :goalId AND user_id = :userId LIMIT 1');
            $stmt->execute(['goalId' => $goalId, 'userId' => $userId]);
            $goal = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$goal) {
                return null;
            }

            $goal['steps'] = $this->stepsForGoal($goalId);
            $goal['progress'] = $this->calculateProgress($goalId);
            return $goal;
        } catch (PDOException $e) {
            return null;
        }
    }

    public function create(int $userId, array $data): ?array
    {
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare(
                'INSERT INTO user_goals (user_id, title, description, category, status, target_date, created_at, updated_at)
                 VALUES (:user_id, :title, :description, :category, :status, :target_date, NOW(), NOW())'
            );

            $stmt->execute([
                'user_id' => $userId,
                'title' => trim((string) ($data['title'] ?? '')),
                'description' => trim((string) ($data['description'] ?? '')),
                'category' => $data['category'] ?? 'Learning',
                'status' => $data['status'] ?? 'Not Started',
                'target_date' => $data['target_date'] ?? null,
            ]);

            $goalId = (int) $pdo->lastInsertId();
            return $this->findById($goalId, $userId);
        } catch (PDOException $e) {
            return null;
        }
    }

    public function update(int $goalId, int $userId, array $data): bool
    {
        try {
            $stmt = Database::getConnection()->prepare(
                'UPDATE user_goals SET title = :title, description = :description, category = :category, status = :status, target_date = :target_date, updated_at = NOW()
                 WHERE id = :goalId AND user_id = :userId'
            );

            return $stmt->execute([
                'title' => trim((string) ($data['title'] ?? '')),
                'description' => trim((string) ($data['description'] ?? '')),
                'category' => $data['category'] ?? 'Learning',
                'status' => $data['status'] ?? 'Not Started',
                'target_date' => $data['target_date'] ?? null,
                'goalId' => $goalId,
                'userId' => $userId,
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function delete(int $goalId, int $userId): bool
    {
        try {
            $stmt = Database::getConnection()->prepare('DELETE FROM user_goals WHERE id = :goalId AND user_id = :userId');
            return $stmt->execute(['goalId' => $goalId, 'userId' => $userId]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function addStep(int $goalId, int $userId, string $title): bool
    {
        try {
            $goal = $this->findById($goalId, $userId);
            if (!$goal) {
                return false;
            }

            $stmt = Database::getConnection()->prepare('INSERT INTO goal_steps (goal_id, title, is_completed, created_at, updated_at) VALUES (:goal_id, :title, 0, NOW(), NOW())');
            return $stmt->execute(['goal_id' => $goalId, 'title' => trim($title)]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function toggleStep(int $goalId, int $stepId, int $userId): bool
    {
        try {
            $goal = $this->findById($goalId, $userId);
            if (!$goal) {
                return false;
            }

            $stmt = Database::getConnection()->prepare('UPDATE goal_steps SET is_completed = 1 - is_completed, updated_at = NOW() WHERE id = :stepId AND goal_id = :goalId');
            return $stmt->execute(['stepId' => $stepId, 'goalId' => $goalId]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function stepsForGoal(int $goalId): array
    {
        try {
            $stmt = Database::getConnection()->prepare('SELECT * FROM goal_steps WHERE goal_id = :goalId ORDER BY created_at ASC');
            $stmt->execute(['goalId' => $goalId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function calculateProgress(int $goalId): array
    {
        $steps = $this->stepsForGoal($goalId);
        $total = count($steps);
        $completed = 0;

        foreach ($steps as $step) {
            if ((int) $step['is_completed'] === 1) {
                $completed++;
            }
        }

        $percent = $total > 0 ? (int) round(($completed / $total) * 100) : 0;

        return [
            'total' => $total,
            'completed' => $completed,
            'percent' => $percent,
        ];
    }

    public function getDashboardGoals(int $userId): array
    {
        $goals = $this->listForUser($userId);
        $active = [];

        foreach ($goals as $goal) {
            if (in_array($goal['status'], ['Not Started', 'In Progress', 'Paused'], true)) {
                $active[] = $goal;
            }
        }

        return array_slice($active, 0, 3);
    }
}
