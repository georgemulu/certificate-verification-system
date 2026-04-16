<?php

use App\Helpers\SessionHelper;

$userName = SessionHelper::get('user_name');
$userRole = SessionHelper::get('user_role');

$nameParts = explode(' ', trim($userName));
$initials = strtoupper(
    (substr($nameParts[0] ?? '', 0, 1)) .
        (substr($nameParts[1] ?? '', 0, 1))
);

$rolePillClass = match ($userRole) {
    'Admin' => 'admin',
    'Verifier' => 'verifier',
    default => 'user',
};
?>

<aside class="sidebar">
    <div class="sidebar-brand">
        <h2>CertVerify</h2>
        <span>Verification System</span>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Main</div>

        <?php if ($userRole === 'Admin'): ?>
            <a href="<?= BASE_PATH ?>/admin/dashboard"
                class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], 'dashboard') ? 'active' : '' ?>">
                <i data-lucide="layout-dashboard"></i> Dashboard
            </a>
            <a href="<?= BASE_PATH ?>/admin/institutions"
                class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], 'institutions') ? 'active' : '' ?>">
                <i data-lucide="building"></i> Institutions
            </a>
            <a href="<?= BASE_PATH ?>/admin/users"
                class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], 'users') ? 'active' : '' ?>">
                <i data-lucide="users"></i> Users
            </a>
            <a href="<?= BASE_PATH ?>/admin/certificates"
                class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], 'certificates') ? 'active' : '' ?>">
                <i data-lucide="file-text"></i> Certificates
            </a>
            <a href="<?= BASE_PATH ?>/admin/logs"
                class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], 'logs') ? 'active' : '' ?>">
                <i data-lucide="clipboard-list"></i> Logs
            </a>

        <?php elseif ($userRole === 'Verifier'): ?>
            <a href="<?= BASE_PATH ?>/verifier/dashboard"
                class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], 'dashboard') ? 'active' : '' ?>">
                <i data-lucide="layout-dashboard"></i> Dashboard
            </a>
            <a href="<?= BASE_PATH ?>/verifier/certificates"
                class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], 'certificates') ? 'active' : '' ?>">
                <i data-lucide="file-text"></i> Certificates
            </a>
            <a href="<?= BASE_PATH ?>/verifier/upload"
                class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], 'upload') ? 'active' : '' ?>">
                <i data-lucide="file-up"></i> Upload Certificate
            </a>
        <?php else: ?>
            <a href="<?= BASE_PATH ?>/user/dashboard"
                class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], 'dashboard') ? 'active' : '' ?>">
                <i data-lucide="layout-dashboard"></i> Dashboard
            </a>
            <a href="<?= BASE_PATH ?>/user/verify"
                class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], 'verify') ? 'active' : '' ?>">
                <i data-lucide="shield-check"></i> Verify Certificate
            </a>
            <a href="<?= BASE_PATH ?>/user/logs"
                class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], 'logs') ? 'active' : '' ?>">
                <i data-lucide="clipboard-list"></i> My Logs
            </a>
        <?php endif; ?>
    </nav>

    <div class="sidebar-footer">
        <a href="<?= BASE_PATH ?>/logout" class="logout-btn">
            <i data-lucide="power"></i> Logout
        </a>
    </div>
</aside>

<div class="main-wrapper">
    <nav class="navbar">
        <div class="navbar-left">
            <h1><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?></h1>
        </div>
        <div class="navbar-right">
            <span class="role-pill <?= $rolePillClass ?>">
                <?= htmlspecialchars($userRole) ?>
            </span>
            <div class="user-badge">
                <div class="avatar"><?= htmlspecialchars($initials) ?></div>
                <span class="name"><?= htmlspecialchars($userName) ?></span>
            </div>
        </div>
    </nav>