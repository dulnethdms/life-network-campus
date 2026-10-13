<?php

namespace App\Controllers;

use App\Middleware\AuthMiddleware;
use App\Repositories\OpportunityRepository;
use App\Validators\OpportunityValidator;

class OpportunityController
{
    private OpportunityRepository $opportunityRepository;

    public function __construct()
    {
        $this->opportunityRepository = new OpportunityRepository();
    }

    public function index(): void
    {
        AuthMiddleware::requireLogin();
        $opportunities = $this->opportunityRepository->listForUser((int) $_SESSION['user_id']);
        renderView('opportunities/index', ['title' => 'My opportunities', 'opportunities' => $opportunities, 'error' => null]);
    }

    public function discover(): void
    {
        AuthMiddleware::requireLogin();
        $opportunities = $this->opportunityRepository->listDiscoverableForUser((int) $_SESSION['user_id']);
        renderView('opportunities/discover', ['title' => 'Discover opportunities', 'opportunities' => $opportunities]);
    }

    public function save(int $opportunityId): void
    {
        AuthMiddleware::requireLogin();
        if (verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            $this->opportunityRepository->save($opportunityId, (int) $_SESSION['user_id']);
        }
        redirect('/opportunities/discover');
    }

    public function unsave(int $opportunityId): void
    {
        AuthMiddleware::requireLogin();
        if (verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            $this->opportunityRepository->unsave($opportunityId, (int) $_SESSION['user_id']);
        }
        redirect('/opportunities/discover');
    }

    public function apply(int $opportunityId): void
    {
        AuthMiddleware::requireLogin();
        if (verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            $this->opportunityRepository->apply($opportunityId, (int) $_SESSION['user_id'], trim((string) ($_POST['message'] ?? '')));
        }
        redirect('/opportunities/discover');
    }

    public function withdraw(int $opportunityId): void
    {
        AuthMiddleware::requireLogin();
        if (verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            $this->opportunityRepository->withdrawApplication($opportunityId, (int) $_SESSION['user_id']);
        }
        redirect('/opportunities/discover');
    }

    public function create(): void
    {
        AuthMiddleware::requireLogin();
        renderView('opportunities/form', ['title' => 'Create opportunity', 'opportunity' => null, 'errors' => []]);
    }

    public function store(): void
    {
        AuthMiddleware::requireLogin();

        if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            renderView('opportunities/form', ['title' => 'Create opportunity', 'opportunity' => $_POST, 'errors' => ['csrf' => 'Invalid security token.']]);
            return;
        }

        $errors = OpportunityValidator::validateOpportunity($_POST);
        if ($errors) {
            renderView('opportunities/form', ['title' => 'Create opportunity', 'opportunity' => $_POST, 'errors' => $errors]);
            return;
        }

        $opportunity = $this->opportunityRepository->create((int) $_SESSION['user_id'], $_POST);
        if (!$opportunity) {
            renderView('opportunities/form', ['title' => 'Create opportunity', 'opportunity' => $_POST, 'errors' => ['database' => 'Unable to save this opportunity right now.']]);
            return;
        }

        redirect('/opportunities');
    }

    public function edit(int $opportunityId): void
    {
        AuthMiddleware::requireLogin();

        $opportunity = $this->opportunityRepository->findById($opportunityId, (int) $_SESSION['user_id']);
        if (!$opportunity) {
            redirect('/opportunities');
        }

        renderView('opportunities/form', ['title' => 'Edit opportunity', 'opportunity' => $opportunity, 'errors' => []]);
    }

    public function update(int $opportunityId): void
    {
        AuthMiddleware::requireLogin();

        if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            redirect('/opportunities/' . $opportunityId . '/edit');
        }

        $errors = OpportunityValidator::validateOpportunity($_POST);
        if ($errors) {
            $opportunity = $this->opportunityRepository->findById($opportunityId, (int) $_SESSION['user_id']);
            renderView('opportunities/form', ['title' => 'Edit opportunity', 'opportunity' => $opportunity ?: $_POST, 'errors' => $errors]);
            return;
        }

        $updated = $this->opportunityRepository->update($opportunityId, (int) $_SESSION['user_id'], $_POST);
        if (!$updated) {
            redirect('/opportunities');
        }

        redirect('/opportunities');
    }

    public function delete(int $opportunityId): void
    {
        AuthMiddleware::requireLogin();

        if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            redirect('/opportunities');
        }

        $this->opportunityRepository->delete($opportunityId, (int) $_SESSION['user_id']);
        redirect('/opportunities');
    }
}
