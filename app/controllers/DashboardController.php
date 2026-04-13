<?php

namespace App\Controllers;

use App\Models\DashboardModel;
use App\Helpers\SessionHelper;

class DashboardController
{
    private DashboardModel $dashboardModel;

    public function __construct()
    {
        $this->dashboardModel = new DashboardModel();
    }

    public function showUserDashboard(): void
    {
        SessionHelper::requireRole('User');

        $userEmail = SessionHelper::get('user_email');
        $stats = $this->dashboardModel->getUserStats($userEmail);
        $recent = $this->dashboardModel->getUserRecentVerifications($userEmail);

        require_once __DIR__ . '/../views/user/dashboard.php';
    }

    public function showAdminDashboard(): void
    {
        SessionHelper::requireRole('Admin');

        $stats = $this->dashboardModel->getAdminStats();
        $recent = $this->dashboardModel->getAdminRecentActivity();

        require_once __DIR__ . '/../views/admin/dashboard.php';
    }
}