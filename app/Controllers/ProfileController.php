<?php

namespace App\Controllers;

use App\Middleware\AuthMiddleware;
use App\Repositories\CommunityRepository;
use App\Repositories\GoalRepository;
use App\Repositories\OpportunityRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\UserRepository;
use App\Validators\UserValidator;

class ProfileController
{
    private UserRepository $userRepository;
    private GoalRepository $goalRepository;
    private ProjectRepository $projectRepository;
    private CommunityRepository $communityRepository;
    private OpportunityRepository $opportunityRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->goalRepository = new GoalRepository();
        $this->projectRepository = new ProjectRepository();
        $this->communityRepository = new CommunityRepository();
        $this->opportunityRepository = new OpportunityRepository();
    }

    public function dashboard(): void
    {
        AuthMiddleware::requireLogin();

        $user = $this->userRepository->findById((int) $_SESSION['user_id']);
        $goals = $this->goalRepository->listForUser((int) $_SESSION['user_id']);
        $projects = $this->projectRepository->listForUser((int) $_SESSION['user_id']);
        $communities = $this->communityRepository->listForUser((int) $_SESSION['user_id']);
        $opportunities = $this->opportunityRepository->listForUser((int) $_SESSION['user_id']);
        $completedGoals = 0;
        $activeGoals = 0;

        foreach ($goals as $goal) {
            if (($goal['status'] ?? 'Not Started') === 'Completed') {
                $completedGoals++;
            }
            if (in_array(($goal['status'] ?? 'Not Started'), ['Not Started', 'In Progress', 'Paused'], true)) {
                $activeGoals++;
            }
        }

        renderView('dashboard', [
            'title' => 'Dashboard',
            'user' => $user,
            'goals' => $goals,
            'notifications' => 0,
            'projects' => $projects,
            'communities' => $communities,
            'opportunities' => $opportunities,
            'activeGoals' => $activeGoals,
            'completedGoals' => $completedGoals,
        ]);
    }

    public function showProfile(): void
    {
        AuthMiddleware::requireLogin();

        $user = $this->userRepository->findById((int) $_SESSION['user_id']);
        renderView('profile/edit', ['title' => 'My profile', 'user' => $user, 'errors' => []]);
    }

    public function updateProfile(): void
    {
        AuthMiddleware::requireLogin();

        if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            $user = $this->userRepository->findById((int) $_SESSION['user_id']);
            renderView('profile/edit', ['title' => 'My profile', 'user' => $user, 'errors' => ['csrf' => 'Invalid security token.']]);
            return;
        }

        $data = $_POST;
        $errors = UserValidator::validateProfile($data);

        if ($errors) {
            $user = $this->userRepository->findById((int) $_SESSION['user_id']);
            renderView('profile/edit', ['title' => 'My profile', 'user' => $user, 'errors' => $errors]);
            return;
        }

        $payload = [
            'name' => trim((string) ($data['name'] ?? '')),
            'email' => strtolower(trim((string) ($data['email'] ?? ''))),
            'university' => trim((string) ($data['university'] ?? '')) !== '' ? trim((string) $data['university']) : null,
            'field_of_study' => trim((string) ($data['field_of_study'] ?? '')) !== '' ? trim((string) $data['field_of_study']) : null,
            'year_of_study' => trim((string) ($data['year_of_study'] ?? '')) !== '' ? (int) $data['year_of_study'] : null,
            'bio' => trim((string) ($data['bio'] ?? '')) !== '' ? trim((string) $data['bio']) : null,
            'interests' => trim((string) ($data['interests'] ?? '')) !== '' ? trim((string) $data['interests']) : null,
            'skills' => trim((string) ($data['skills'] ?? '')) !== '' ? trim((string) $data['skills']) : null,
            'career_interests' => trim((string) ($data['career_interests'] ?? '')) !== '' ? trim((string) $data['career_interests']) : null,
        ];

        $this->userRepository->update((int) $_SESSION['user_id'], $payload);
        redirect('/profile');
    }

    public function showSettings(): void
    {
        AuthMiddleware::requireLogin();
        $user = $this->userRepository->findById((int) $_SESSION['user_id']);
        renderView('settings', ['title' => 'Account settings', 'user' => $user, 'errors' => [], 'success' => false]);
    }

    public function changePassword(): void
    {
        AuthMiddleware::requireLogin();

        $user = $this->userRepository->findById((int) $_SESSION['user_id']);
        $errors = UserValidator::validatePasswordChange($_POST);

        if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            renderView('settings', ['title' => 'Account settings', 'user' => $user, 'errors' => ['csrf' => 'Invalid security token.'], 'success' => false]);
            return;
        }

        if ($errors) {
            renderView('settings', ['title' => 'Account settings', 'user' => $user, 'errors' => $errors, 'success' => false]);
            return;
        }

        if (!password_verify((string) ($_POST['current_password'] ?? ''), $user['password_hash'])) {
            renderView('settings', ['title' => 'Account settings', 'user' => $user, 'errors' => ['current_password' => 'Current password is incorrect.'], 'success' => false]);
            return;
        }

        $this->userRepository->updatePassword((int) $_SESSION['user_id'], password_hash((string) ($_POST['new_password'] ?? ''), PASSWORD_DEFAULT));
        renderView('settings', ['title' => 'Account settings', 'user' => $user, 'errors' => [], 'success' => 'Password updated successfully.']);
    }

    public function deactivateAccount(): void
    {
        AuthMiddleware::requireLogin();

        if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            redirect('/settings');
        }

        $this->userRepository->deactivate((int) $_SESSION['user_id']);
        session_unset();
        session_destroy();
        redirect('/login');
    }
}
