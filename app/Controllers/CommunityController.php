<?php

namespace App\Controllers;

use App\Middleware\AuthMiddleware;
use App\Repositories\CommunityRepository;
use App\Validators\CommunityValidator;

class CommunityController
{
    private CommunityRepository $communityRepository;

    public function __construct()
    {
        $this->communityRepository = new CommunityRepository();
    }

    public function index(): void
    {
        AuthMiddleware::requireLogin();
        $communities = $this->communityRepository->listForUser((int) $_SESSION['user_id']);

        renderView('communities/index', ['title' => 'My communities', 'communities' => $communities, 'error' => null]);
    }

    public function create(): void
    {
        AuthMiddleware::requireLogin();
        renderView('communities/form', ['title' => 'Create community', 'community' => null, 'errors' => []]);
    }

    public function store(): void
    {
        AuthMiddleware::requireLogin();

        if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            renderView('communities/form', ['title' => 'Create community', 'community' => $_POST, 'errors' => ['csrf' => 'Invalid security token.']]);
            return;
        }

        $errors = CommunityValidator::validateCommunity($_POST);
        if ($errors) {
            renderView('communities/form', ['title' => 'Create community', 'community' => $_POST, 'errors' => $errors]);
            return;
        }

        $community = $this->communityRepository->create((int) $_SESSION['user_id'], $_POST);
        if (!$community) {
            renderView('communities/form', ['title' => 'Create community', 'community' => $_POST, 'errors' => ['database' => 'Unable to save the community right now.']]);
            return;
        }

        redirect('/communities');
    }

    public function edit(int $communityId): void
    {
        AuthMiddleware::requireLogin();

        $community = $this->communityRepository->findById($communityId, (int) $_SESSION['user_id']);
        if (!$community) {
            redirect('/communities');
        }

        renderView('communities/form', ['title' => 'Edit community', 'community' => $community, 'errors' => []]);
    }

    public function update(int $communityId): void
    {
        AuthMiddleware::requireLogin();

        if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            redirect('/communities/' . $communityId . '/edit');
        }

        $errors = CommunityValidator::validateCommunity($_POST);
        if ($errors) {
            $community = $this->communityRepository->findById($communityId, (int) $_SESSION['user_id']);
            renderView('communities/form', ['title' => 'Edit community', 'community' => $community ?: $_POST, 'errors' => $errors]);
            return;
        }

        $updated = $this->communityRepository->update($communityId, (int) $_SESSION['user_id'], $_POST);
        if (!$updated) {
            redirect('/communities');
        }

        redirect('/communities');
    }

    public function delete(int $communityId): void
    {
        AuthMiddleware::requireLogin();

        if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            redirect('/communities');
        }

        $this->communityRepository->delete($communityId, (int) $_SESSION['user_id']);
        redirect('/communities');
    }
}
