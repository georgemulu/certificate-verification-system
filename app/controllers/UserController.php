<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\InstitutionModel;
use App\Helpers\CsrfHelper;
use App\Helpers\SessionHelper;

class UserController
{
    private UserModel $userModel;
    private InstitutionModel $institutionModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->institutionModel = new InstitutionModel();
    }

    public function showUsers(): void
    {
        SessionHelper::requireRole('Admin');
        $users = $this->userModel->getAll();
        $institutions = $this->institutionModel->getAll();
        require_once __DIR__ . '/../Views/admin/users.php';
    }

    public function handleUpdateRole(): void
    {
        SessionHelper::requireRole('Admin');
        CsrfHelper::validate();

        $userId = (int) ($_POST['user_id'] ?? 0);
        $role = trim($_POST['role'] ?? '');

        $allowedRoles = ['User', 'Verifier', 'Admin'];

        if ($userId <= 0 || !in_array($role, $allowedRoles, true)) {
            header("Location: " .  BASE_PATH . "/admin/users?error=invalid");
            exit;
        }

        if ($userId === (int) SessionHelper::get('user_id')) {
            header("Location: " . BASE_PATH . "/admin/users?error=selfmod");
            exit;
        }

        $updated = $this->userModel->updateRole($userId, $role);

        if ($updated) {
            header("Location: " . BASE_PATH . "/admin/users?success=role");
            exit;
        }

        header("Location : " . BASE_PATH . "/admin/users?error=failed");
        exit;
    }

    public function handleUpdateInstitution(): void
    {
        SessionHelper::requireRole('Admin');
        CsrfHelper::validate();

        $userId = (int) ($_POST['user_id'] ?? 0);
        $institutionId = $_POST['institution_id'] === '' ? null : (int) $_POST['institution_id'];

        if ($userId <= 0) {
            header("Location: " . BASE_PATH . "/admin/users?error=invalid");
            exit;
        }

        $updated = $this->userModel->updateInstitution($userId, $institutionId);

        if ($updated) {
            header("Location: " . BASE_PATH . "/admin/users?success=institution");
            exit;
        }

        header("Loaction: " . BASE_PATH . "/admin/users?error=failed");
        exit;
    }

    public function handleDelete(): void
    {
        SessionHelper::requireRole('Admin');
        CsrfHelper::validate();

        $userId = (int) ($_POST['user_id'] ?? 0);

        if ($userId <= 0) {
            header("Location" . BASE_PATH . "/admin/users?error=invalid");
            exit;
        }

        if ($userId === (int)  SessionHelper::get('user_id')) {
            header("Location: " . BASE_PATH . "/admin/users?error=selfdelete");
            exit;
        }

        $deleted = $this->userModel->delete($userId);

        if ($deleted) {
            header("Location: " . BASE_PATH . "/admin/users?success=deleted");
            exit;
        }

        header("Location: " . BASE_PATH . "/admin/users?error=failed");
        exit;
    }
}
