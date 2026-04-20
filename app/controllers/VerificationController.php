<?php

namespace App\Controllers;

use App\Models\CertificateModel;
use App\Models\InstitutionModel;
use App\Models\VerificationModel;
use App\Helpers\SessionHelper;
use App\Helpers\CsrfHelper;
use App\Helpers\CertificateHelper;

class VerificationController
{
    private CertificateModel $certificateModel;
    private InstitutionModel $institutionModel;
    private VerificationModel $verificationModel;

    public function __construct()
    {
        $this->certificateModel = new CertificateModel();
        $this->institutionModel = new InstitutionModel();
        $this->verificationModel = new VerificationModel();
    }

    public function showVerifyForm(): void
    {
        SessionHelper::requireRole('User');
        $institutions = $this->institutionModel->getAll();
        require_once __DIR__ . '/../Views/user/verify.php';
    }

    public function handleVerify(): void
    {
        SessionHelper::requireRole('User');
        CsrfHelper::validate();

        $serialNumber = trim($_POST['serial_number'] ?? '');
        $institutionId = (int)($_POST['institution_id'] ?? 0);
        $verifiedBy = SessionHelper::get('user_email');
        $institutions = $this->institutionModel->getAll();

        $errors = [];

        if(empty($serialNumber)) $errors[] = "Serial number is required.";
        if($institutionId <= 0) $errors[] = "Please select an institution.";

        if(!empty($errors)) {
            require_once __DIR__ . '/../Views/user/verify.php';
            return;
        }

        $cert = $this->certificateModel->findBySerialAndInstitution($serialNumber,$institutionId);

        if(!$cert) {
            $result = 'invalid';
            require_once __DIR__ . '/../Views/user/verify.php';
            return;
        }

        if($cert['is_revoked']) {
            $this->verificationModel->log($cert['id'], $verifiedBy, 'failed');
            $result = 'revoked';
            require_once __DIR__ . '/../Views/verify.php';
            return;
        }

        $recomputed = CertificateHelper::computeHash(
            $cert['serial_number'],
            $cert['owner_name'],
            $cert['course'],
            $cert['certificate_type'],
            $cert['issued_at'],
            (int) $cert['institution_id']
        );

        if(hash_equals($recomputed, $cert['certificate_code'])) {
            $this->verificationModel->log($cert['id'], $verifiedBy, 'success');
            $result = 'authentic';
        } else {
            $this->verificationModel->log($cert['id'], $verifiedBy, 'failed');
            $result = 'tampered';
        }

        require_once __DIR__ . '/../Views/user/verify.php';
    }
}