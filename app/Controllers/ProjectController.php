<?php

namespace App\Controllers;

use App\Middleware\AuthMiddleware;
use App\Repositories\ProjectRepository;
use App\Validators\ProjectValidator;

class ProjectController
{
    private ProjectRepository $projectRepository;

    public function __construct()
    {
        $this->projectRepository = new ProjectRepository();
    }

    public function index(): void
    {
        AuthMiddleware::requireLogin();

        $projects = $this->projectRepository->listForUser((int) $_SESSION['user_id']);
        renderView('projects/index', ['title' => 'My projects', 'projects' => $projects, 'error' => null]);
    }

    public function create(): void
    {
        AuthMiddleware::requireLogin();
        renderView('projects/form', ['title' => 'Create project', 'project' => null, 'errors' => []]);
    }

    public function store(): void
    {
        AuthMiddleware::requireLogin();

        if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            renderView('projects/form', ['title' => 'Create project', 'project' => $_POST, 'errors' => ['csrf' => 'Invalid security token.']]);
            return;
        }

        $errors = ProjectValidator::validateProject($_POST);
        if ($errors) {
            renderView('projects/form', ['title' => 'Create project', 'project' => $_POST, 'errors' => $errors]);
            return;
        }

        $project = $this->projectRepository->create((int) $_SESSION['user_id'], $_POST);
        if (!$project) {
            renderView('projects/form', ['title' => 'Create project', 'project' => $_POST, 'errors' => ['database' => 'Unable to save the project right now.']]);
            return;
        }

        redirect('/projects');
    }

    public function edit(int $projectId): void
    {
        AuthMiddleware::requireLogin();

        $project = $this->projectRepository->findById($projectId, (int) $_SESSION['user_id']);
        if (!$project) {
            redirect('/projects');
        }

        renderView('projects/form', ['title' => 'Edit project', 'project' => $project, 'errors' => []]);
    }

    public function update(int $projectId): void
    {
        AuthMiddleware::requireLogin();

        if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            redirect('/projects/' . $projectId . '/edit');
        }

        $errors = ProjectValidator::validateProject($_POST);
        if ($errors) {
            $project = $this->projectRepository->findById($projectId, (int) $_SESSION['user_id']);
            renderView('projects/form', ['title' => 'Edit project', 'project' => $project ?: $_POST, 'errors' => $errors]);
            return;
        }

        $updated = $this->projectRepository->update($projectId, (int) $_SESSION['user_id'], $_POST);
        if (!$updated) {
            redirect('/projects');
        }

        redirect('/projects');
    }

    public function delete(int $projectId): void
    {
        AuthMiddleware::requireLogin();

        if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            redirect('/projects');
        }

        $this->projectRepository->delete($projectId, (int) $_SESSION['user_id']);
        redirect('/projects');
    }
}
