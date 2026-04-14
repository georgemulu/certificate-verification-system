<?php

use App\Helpers\SessionHelper;

$pageTitle = 'Admin Dashboard';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';
?>

<div class="page-content">
    <div class="page-header">
        <h2> Welcome back, <?= htmlspecialchars(SessionHelper::get('user_name')) ?></h2>
        <p> System-wide overview of the certificate verification system</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i data-lucide="users"></i>
            </div>
            <div class="stat-label">Total Users</div>
            <div class="stat-value">
                <?= (int)($stats['total_users'] ?? 0) ?>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon warning">
                <i data-lucide="building"></i>
            </div>
            <div class="stat-label">Institutions</div>
            <div class="stat-value">
                <?= (int)($stats['total_institutions'] ?? 0) ?>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon success">
                <i data-lucide="file-text"></i>
            </div>
            <div class="stat-label">Certificates</div>
            <div class="stat-value">
                <?= (int)($stats['total_certificates'] ?? 0) ?>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i data-lucide="scan-search"></i>
            </div>
            <div class="stat-label">Verifications</div>
            <div class="stat-value">
                <?= (int)($stats['total_verifications'] ?? 0) ?>
            </div>
        </div>
    </div>

    <div class="table-card">
        <div class="table-card-header">
            <h3> Recent Admin Activity</h3>
        </div>
        <?php if (empty($recent)): ?>
            <div class="empty-state">
                No admin activity recorded yet.
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Admin</th>
                        <th>Action</th>
                        <th>Target User</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent as $log): ?>
                        <tr>
                            <td><?= htmlspecialchars($cert['admin_name']) ?></td>
                            <td><?= htmlspecialchars($cert['action']) ?></td>
                            <td><?= htmlspecialchars($cert['target_name'] ?? '-') ?></td>
                            <td><?= date('M d, Y H:i', strtotime($cert['action_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>