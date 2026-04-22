<?php

namespace App\Controllers;

use App\Models\LogModel;
use App\Helpers\SessionHelper;

class LogController
{
    private LogModel $logModel;

    public function __construct()
    {
        $this->logModel = new LogModel();
    }

    public function showLogs(): void
    {
        SessionHelper::requireRole('Admin');
        $verificationLogs = $this->logModel->getVerificationLogs();
        $adminLogs        = $this->logModel->getAdminLogs();
        require_once __DIR__ . '/../Views/admin/logs.php';
    }
}