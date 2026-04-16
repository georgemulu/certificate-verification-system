<?php

namespace App\controllers;

use App\Models\CertificateModel;
use App\Helpers\SessionHelper;

class CertificateController
{
    private CertificateModel $certificateModel;

    public function __construct()
    {
        $this->certificateModel = new CertificateModel();
    }

    public function showUploadForm(): void
    {
        SessionHelper::requireRole('Verifier');
        require_once __DIR__ . '/../views/verifier/upload.php';
    }

    public function handleUpload(): void
    {
        SessionHelper::requireRole('Verifier');

        $ownerName = trim($_POST['owner_name'] ?? '');
        $certificateType = trim($_POST['certificate_type'] ?? '');

        $uploadedBy = (int) SessionHelper::get('user_id');
        $institutionId = (int) SessionHelper::get('institution_id');

        $errors = [];

        if(empty($ownerName)) {
            $errors[] = "Certificate owner name is required.";
        }

        if(empty($certificateType)) {
            $errors[] ="Certificate type is required.";
        }

        if($institutionId <= 0) {
            $errors[] = "Your account is not linked to an institution. Contact the admin.";
        }

        if(!empty($errors)) {
            require_once __DIR__ . '/../Views/verifier/upload.php';
            return;
        }

        $certificateCode = $this->certificateModel->generateUniqueCode();

        $created = $this->certificateModel->create(
            $ownerName,
            $certificateType,
            $certificateCode,
            $uploadedBy,
            $institutionId
        );

        if($created) {
            header("Location: " . BASE_PATH . "/verifier/upload?success=1&code=" .urlencode($certificateCode));
            exit;
        }

        $errors[] = "Upload failed. Please try again.";
        require_once __DIR__ . '/../Views/verifier/upload.php';
    }
}
