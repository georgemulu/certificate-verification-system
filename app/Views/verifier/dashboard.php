<?php

use App\Helpers\SessionHelper;

$pageTitle = 'Verifier Dashboard';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';
?>

<div class="page-content">
    <div class="page-header">
        <h2> Welcome back, <?= htmlspecialchars(SessionHelper::get('user_name')) ?></h2>
        <p>Your certificate management activity at a glance</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i data-lucide="file-up"></i>
            </div>
            <div class="stat-label">Total Uploaded</div>
            <div class="stat-value">
                <?= (int)($stats['total_uploaded'] ?? 0) ?>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon danger">
                <i data-lucide="file-x"></i>
            </div>
            <div class="stat-label">Revoked</div>
            <div class="stat-value">
                <?= (int)($stats['total_revoked'] ?? 0) ?>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon warning">
                <i data-lucide="clock"></i>
            </div>
            <div class="stat-label">Last Upload</div>
            <div class="stat-value" style="font-size: 1rem; padding-top: 0.25rem;">
                <?= $stats['last_upload'] ? date('M d, Y', strtotime($stats['last_upload'])) : '-' ?>
            </div>
        </div>
    </div>

    <div class="table-card">
        <div class="table-card-header">
            <h3> Recent Certificates</h3>
        </div>
        <?php if (empty($recent)): ?>
            <div class="empty-state">
                No certificates uploaded yet.
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Certificate Code</th>
                        <th>Owner Name</th>
                        <th>Type</th>
                        <th>Issued At</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent as $cert): ?>
                        <tr>
                            <td><?= htmlspecialchars($cert['certificate_code']) ?></td>
                            <td><?= htmlspecialchars($cert['owner_name']) ?></td>
                            <td><?= htmlspecialchars($cert['certificate_type']) ?></td>
                            <td><?= date('M d, Y', strtotime($cert['issued_at'])) ?></td>
                            <td>
                                <span class="badge <?= $cert['is_revoked'] ? 'badge-danger' : 'badge-success' ?>">
                                    <?= $cert['is_revoked'] ? 'Revoked' : 'Active' ?>
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