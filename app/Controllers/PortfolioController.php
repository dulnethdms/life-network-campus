<?php

namespace App\Controllers;

use App\Middleware\AuthMiddleware;
use App\Repositories\PortfolioRepository;
use App\Validators\PortfolioValidator;

class PortfolioController
{
    private PortfolioRepository $portfolioRepository;

    public function __construct()
    {
        $this->portfolioRepository = new PortfolioRepository();
    }

    public function index(): void
    {
        AuthMiddleware::requireLogin();
        renderView('portfolio/index', [
            'title' => 'My portfolio',
            'items' => $this->portfolioRepository->listForUser((int) $_SESSION['user_id']),
        ]);
    }

    public function create(): void
    {
        AuthMiddleware::requireLogin();
        renderView('portfolio/form', ['title' => 'Add portfolio item', 'item' => null, 'errors' => []]);
    }

    public function store(): void
    {
        AuthMiddleware::requireLogin();
        $errors = verifyCsrfToken($_POST['csrf_token'] ?? null) ? PortfolioValidator::validate($_POST) : ['csrf' => 'Invalid security token.'];
        if ($errors) {
            renderView('portfolio/form', ['title' => 'Add portfolio item', 'item' => $_POST, 'errors' => $errors]);
            return;
        }
        $this->portfolioRepository->create((int) $_SESSION['user_id'], $_POST);
        redirect('/portfolio');
    }

    public function edit(int $itemId): void
    {
        AuthMiddleware::requireLogin();
        $item = $this->portfolioRepository->findById($itemId, (int) $_SESSION['user_id']);
        if (!$item) {
            redirect('/portfolio');
        }
        renderView('portfolio/form', ['title' => 'Edit portfolio item', 'item' => $item, 'errors' => []]);
    }

    public function update(int $itemId): void
    {
        AuthMiddleware::requireLogin();
        $errors = verifyCsrfToken($_POST['csrf_token'] ?? null) ? PortfolioValidator::validate($_POST) : ['csrf' => 'Invalid security token.'];
        if ($errors) {
            $item = $this->portfolioRepository->findById($itemId, (int) $_SESSION['user_id']);
            renderView('portfolio/form', ['title' => 'Edit portfolio item', 'item' => $item ?: $_POST, 'errors' => $errors]);
            return;
        }
        $this->portfolioRepository->update($itemId, (int) $_SESSION['user_id'], $_POST);
        redirect('/portfolio');
    }

    public function delete(int $itemId): void
    {
        AuthMiddleware::requireLogin();
        if (verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            $this->portfolioRepository->delete($itemId, (int) $_SESSION['user_id']);
        }
        redirect('/portfolio');
    }
}
