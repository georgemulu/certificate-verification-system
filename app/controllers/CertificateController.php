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
        \App\Helpers\CsrfHelper::validate();
        SessionHelper::requireRole('Verifier');

        $ownerName = trim($_POST['owner_name'] ?? '');
        $certificateType = trim($_POST['certificate_type'] ?? '');
        $course = trim($_POST['course'] ?? '');
        $serialNumber = trim($_POST['serial_number'] ?? '');
        $issuedAt = trim($_POST['issued_at'] ?? '');

        $uploadedBy = (int) SessionHelper::get('user_id');
        $institutionId = (int) SessionHelper::get('institution_id');

        $errors = [];

        if(empty($ownerName)) {
            $errors[] = "Certificate owner name is required.";
        }

        if(empty($certificateType)) {
            $errors[] ="Certificate type is required.";
        }

        $allowedTypes = ['Certificate', 'Diploma', 'Degree', 'Masters', 'PhD'];
        if(!in_array($certificateType, $allowedTypes, true)) {
            $errors[] = "Invalid certificate type selected.";
        }

        if(empty($course)) {
            $errors[] = "Course name is required.";
        }

        if(empty($serialNumber)) {
            $errors[] = "Certificate serial number is required.";
        }

        if(empty($issuedAt)) {
            $errors[] = "Issue date is required.";
        }

        if(!empty($issuedAt) && strtotime($issuedAt) > time()) {
            $errors[] = "Issue date cannot be in the future.";
        }

        if(!empty($issuedAt)) {
            $dateParts = explode('-',$issuedAt);
            if (count($dateParts) !==3 || !checkdate((int)$dateParts[1], (int)$dateParts[2], (int)$dateParts[0])) {
                $errors[] = "Issue date is not a valid date";
            }
        }

        if($institutionId <= 0) {
            $errors[] = "Your account is not linked to an institution. Contact the admin.";
        }

        if(
            empty($errors) &&
            $this->certificateModel->serialExistsforInstitution($serialNumber,$institutionId)
        ) {
            $errors[] = "A certificate with this serial number already exists for your institution.";
        }

        if(!empty($errors)) {
            require_once __DIR__ . '/../Views/verifier/upload.php';
            return;
        }

        $certificateCode = $this->certificateModel->generateUniqueCode();

        $created = $this->certificateModel->create(
            $ownerName,
            $certificateType,
            $course,
            $serialNumber,
            $certificateCode,
            $issuedAt,
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
