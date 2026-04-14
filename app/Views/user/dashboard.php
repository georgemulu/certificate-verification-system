<?php

use App\Helpers\SessionHelper;

$pageTitle = 'User Dashboard';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';
?>

<div class="page-content">
    <div class="page-header">
        <h2> Welcome back, <?= htmlspecialchars(SessionHelper::get('user_name')) ?></h2>
        <p> Your certificate verification activity at a glance.</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i data-lucide="shield-check"></i>
            </div>
            <div class="stat-label">Total Verifications</div>
            <div class="stat-value">
                <?= (int)($stats['total_verifications'] ?? 0) ?>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon success">
                <i data-lucide="circle-check"></i>
            </div>
            <div class="stat-label">Successful</div>
            <div class="stat-value">
                <?= (int)($stats['total_success'] ?? 0) ?>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon danger">
                <i data-lucide="circle-x"></i>
            </div>
            <div class="stat-label">Failed</div>
            <div class="stat-value">
                <?= (int)($stats['total_failed'] ?? 0) ?>
            </div>
        </div>
    </div>

    <div class="table-card">
        <div class="table-card-header">
            <h3> Recent Verifications</h3>
        </div>
        <?php if (empty($recent)): ?>
            <div class="empty-state">
                No Verifications performed yet.
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Certificate Code</th>
                        <th>Owner Name</th>
                        <th>Type</th>
                        <th>Verified At</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent as $cert): ?>
                        <tr>
                            <td><?= htmlspecialchars($cert['certificate_code']) ?></td>
                            <td><?= htmlspecialchars($cert['owner_name']) ?></td>
                            <td><?= htmlspecialchars($cert['certificate_type']) ?></td>
                            <td><?= date('M d, Y H:i', strtotime($cert['issued_at'])) ?></td>
                            <td>
                                <span class="badge <?= $log['status'] === 'success' ? 'badge-success' : 'badge-danger' ?>">
                                    <?= ucfirst($log['status']) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>