<?php

namespace App\Controllers;

use App\Models\InstitutionModel;
use App\Helpers\SessionHelper;
use App\Helpers\CsrfHelper;

class InstitutionController
{
    private InstitutionModel $institutionModel;

    public function __construct()
    {
        $this->institutionModel = new InstitutionModel();
    }

    public function showInstitutions(): void
    {
        SessionHelper::requireRole('Admin');
        $institutions = $this->institutionModel->getAll();
        require_once __DIR__ . '/../Views/admin/institutions.php';
    }

    public function handleCreate(): void
    {
        SessionHelper::requireRole('Admin');
        CsrfHelper::validate();

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');

        $errors = [];

        if (empty($name)) $errors[] = "Institution name is required.";
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Please enter a valid email address";
        }

        if (!empty($errors)) {
            $institutions = $this->institutionModel->getAll();
            require_once __DIR__ . '/../Views/admin/institutions.php';
            return;
        }

        $created = $this->institutionModel->create($name, $email);

        if ($created) {
            header("Location: " . BASE_PATH . "/admin/institutions?success=created");
            exit;
        }
    }

    public function handleDelete(): void
    {
        SessionHelper::requireRole('Admin');
        CsrfHelper::validate();

        $id = (int) ($_POST['institution_id'] ?? 0);

        if ($id <= 0) {
            header("Location: " . BASE_PATH . "/admin/institutions?error=invalid");
            exit;
        }

        $institution = $this->institutionModel->findById($id);
        if (!$institution) {
            header("Location: " . BASE_PATH . "admin/institutions?error=notfound");
            exit;
        }

        $deleted = $this->institutionModel->findById($id);
        if ($deleted) {
            header("Location: " . BASE_PATH . "/admin/institutions?success=deleted");
        }

        header("Location: " . BASE_PATH . "/admin/institutions?error=failed");
        exit;
    }
}
