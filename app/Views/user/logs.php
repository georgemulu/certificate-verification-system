<?php
$pageTitle = 'My Verification Logs';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';
?>

<div class="page-content">
    <div class="page-header">
        <h2>My Verification Logs</h2>
        <p>A history of all certificate verifications you have performed.</p>
    </div>

    <div class="table-card">
        <div class="table-card-header">
            <h3>Verification History</h3>
            <div class="header-right">
                <span class="count-badge"><?= count($logs) ?></span>
                <a href="<?= BASE_PATH ?>/user/verify" class="btn-primary">
                    <i data-lucide="shield-check"></i>
                    Verify Certificate
                </a>
            </div>
        </div>

        <?php if (empty($logs)): ?>
            <div class="empty-state">
                <i data-lucide="clipboard-list"></i>
                <p>You have not performed any verifications yet.</p>
                <a href="<?= BASE_PATH ?>/user/verify" class="btn-primary" style="margin-top:1rem;">
                    <i data-lucide="shield-check"></i>
                    Verify a Certificate
                </a>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Serial Number</th>
                        <th>Owner</th>
                        <th>Type</th>
                        <th>Course</th>
                        <th>Institution</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td><code><?= htmlspecialchars($log['serial_number']) ?></code></td>
                            <td><?= htmlspecialchars($log['owner_name']) ?></td>
                            <td><?= htmlspecialchars($log['certificate_type']) ?></td>
                            <td><?= htmlspecialchars($log['course']) ?></td>
                            <td><?= htmlspecialchars($log['institution_name']) ?></td>
                            <td><?= date('M j, Y H:i', strtotime($log['verified_at'])) ?></td>
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

<style>
    .table-card {
        background: var(--card-bg);
        border-radius: var(--radius);
        box-shadow: var(--card-shadow);
        border: 1px solid var(--border);
        overflow: hidden;
        animation: fadeUp 0.4s ease both;
        width: 100%;
    }

    .table-card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .table-card-header h3 {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .header-right {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .count-badge {
        background: var(--accent-light);
        color: var(--accent);
        font-size: 0.75rem;
        font-weight: 700;
        padding: 0.2rem 0.6rem;
        border-radius: 999px;
    }

    table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
    thead th {
        background: #f8fafc; padding: 0.75rem 1.5rem;
        text-align: left; font-size: 0.72rem; font-weight: 600;
        text-transform: uppercase; letter-spacing: 0.07em;
        color: var(--text-secondary); border-bottom: 1px solid var(--border);
    }
    tbody tr { border-bottom: 1px solid var(--border); transition: background 0.15s; }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: #f8fafc; }
    tbody td { padding: 0.85rem 1.5rem; color: var(--text-primary); vertical-align: middle; }

    code {
        background: var(--accent-light);
        color: var(--accent);
        padding: 0.15rem 0.45rem;
        border-radius: 4px;
        font-size: 0.82rem;
        font-weight: 600;
    }

    .badge {
        display: inline-flex; align-items: center;
        padding: 0.2rem 0.65rem; border-radius: 999px;
        font-size: 0.72rem; font-weight: 600;
    }
    .badge-success { background: var(--success-light); color: var(--success); }
    .badge-danger  { background: var(--danger-light);  color: var(--danger); }

    .btn-primary {
        display: inline-flex; align-items: center; gap: 0.5rem;
        padding: 0.6rem 1.25rem; background: var(--accent); color: #fff;
        border: none; border-radius: 6px; font-size: 0.85rem; font-weight: 600;
        cursor: pointer; font-family: var(--font-body);
        text-decoration: none; transition: background 0.2s;
    }
    .btn-primary:hover { background: #245a8a; }
    .btn-primary svg { width: 15px; height: 15px; }

    .empty-state {
        padding: 3rem; text-align: center;
        color: var(--text-secondary); font-size: 0.875rem;
        display: flex; flex-direction: column; align-items: center;
    }
    .empty-state svg { width: 32px; height: 32px; margin-bottom: 0.75rem; opacity: 0.4; }
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>