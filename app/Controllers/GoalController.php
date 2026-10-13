<?php

namespace App\Controllers;

use App\Middleware\AuthMiddleware;
use App\Repositories\GoalRepository;
use App\Validators\GoalValidator;

class GoalController
{
    private GoalRepository $goalRepository;

    public function __construct()
    {
        $this->goalRepository = new GoalRepository();
    }

    public function index(): void
    {
        AuthMiddleware::requireLogin();
        $goals = $this->goalRepository->listForUser((int) $_SESSION['user_id']);

        renderView('goals/index', [
            'title' => 'My goals',
            'goals' => $goals,
            'error' => null,
        ]);
    }

    public function create(): void
    {
        AuthMiddleware::requireLogin();
        renderView('goals/form', [
            'title' => 'Create goal',
            'goal' => null,
            'errors' => [],
        ]);
    }

    public function store(): void
    {
        AuthMiddleware::requireLogin();

        if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            renderView('goals/form', ['title' => 'Create goal', 'goal' => $_POST, 'errors' => ['csrf' => 'Invalid security token.']]);
            return;
        }

        $errors = GoalValidator::validateGoal($_POST);
        if ($errors) {
            renderView('goals/form', ['title' => 'Create goal', 'goal' => $_POST, 'errors' => $errors]);
            return;
        }

        $goal = $this->goalRepository->create((int) $_SESSION['user_id'], $_POST);
        if (!$goal) {
            renderView('goals/form', ['title' => 'Create goal', 'goal' => $_POST, 'errors' => ['database' => 'Unable to save the goal right now.']]);
            return;
        }

        redirect('/goals');
    }

    public function edit(int $goalId): void
    {
        AuthMiddleware::requireLogin();
        $goal = $this->goalRepository->findById($goalId, (int) $_SESSION['user_id']);

        if (!$goal) {
            redirect('/goals');
        }

        renderView('goals/form', ['title' => 'Edit goal', 'goal' => $goal, 'errors' => []]);
    }

    public function update(int $goalId): void
    {
        AuthMiddleware::requireLogin();

        if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            redirect('/goals/' . $goalId . '/edit');
        }

        $errors = GoalValidator::validateGoal($_POST);
        if ($errors) {
            $goal = $this->goalRepository->findById($goalId, (int) $_SESSION['user_id']);
            renderView('goals/form', ['title' => 'Edit goal', 'goal' => $goal ?: $_POST, 'errors' => $errors]);
            return;
        }

        $updated = $this->goalRepository->update($goalId, (int) $_SESSION['user_id'], $_POST);
        if (!$updated) {
            redirect('/goals');
        }

        redirect('/goals');
    }

    public function delete(int $goalId): void
    {
        AuthMiddleware::requireLogin();

        if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            redirect('/goals');
        }

        $this->goalRepository->delete($goalId, (int) $_SESSION['user_id']);
        redirect('/goals');
    }

    public function addStep(int $goalId): void
    {
        AuthMiddleware::requireLogin();

        if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            redirect('/goals/' . $goalId . '/edit');
        }

        $title = trim((string) ($_POST['step_title'] ?? ''));
        if ($title !== '') {
            $this->goalRepository->addStep($goalId, (int) $_SESSION['user_id'], $title);
        }

        redirect('/goals/' . $goalId . '/edit');
    }

    public function toggleStep(int $goalId, int $stepId): void
    {
        AuthMiddleware::requireLogin();

        if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            redirect('/goals/' . $goalId . '/edit');
        }

        $this->goalRepository->toggleStep($goalId, $stepId, (int) $_SESSION['user_id']);
        redirect('/goals/' . $goalId . '/edit');
    }
}
