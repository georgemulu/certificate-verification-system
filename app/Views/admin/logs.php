<?php
$pageTitle = 'Logs';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';
?>

<div class="page-content">
    <div class="page-header">
        <h2>Logs</h2>
        <p>System activity across verifications and admin actions.</p>
    </div>

    <div class="tabs">
        <button class="tab-btn active" data-tab="verification">
            <i data-lucide="shield-check"></i>
            Verification Logs
            <span class="tab-count"><?= count($verificationLogs) ?></span>
        </button>
        <button class="tab-btn" data-tab="admin">
            <i data-lucide="clipboard-list"></i>
            Admin Logs
            <span class="tab-count"><?= count($adminLogs) ?></span>
        </button>
    </div>

    <!-- Verification Logs -->
    <div class="tab-panel active" id="tab-verification">
        <div class="table-card">
            <?php if (empty($verificationLogs)): ?>
                <div class="empty-state">
                    <i data-lucide="shield-check"></i>
                    <p>No verification logs yet.</p>
                </div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Verified By</th>
                            <th>Serial Number</th>
                            <th>Owner</th>
                            <th>Type</th>
                            <th>Institution</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($verificationLogs as $log): ?>
                            <tr>
                                <td><?= htmlspecialchars($log['verified_by']) ?></td>
                                <td><code><?= htmlspecialchars($log['serial_number']) ?></code></td>
                                <td><?= htmlspecialchars($log['owner_name']) ?></td>
                                <td><?= htmlspecialchars($log['certificate_type']) ?></td>
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

    <!-- Admin Logs -->
    <div class="tab-panel" id="tab-admin">
        <div class="table-card">
            <?php if (empty($adminLogs)): ?>
                <div class="empty-state">
                    <i data-lucide="clipboard-list"></i>
                    <p>No admin activity logged yet.</p>
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
                        <?php foreach ($adminLogs as $log): ?>
                            <tr>
                                <td><?= htmlspecialchars($log['admin_name']) ?></td>
                                <td><?= htmlspecialchars($log['action']) ?></td>
                                <td><?= htmlspecialchars($log['target_name'] ?? '—') ?></td>
                                <td><?= date('M j, Y H:i', strtotime($log['action_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.25rem;
    }

    .tab-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.6rem 1.25rem;
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--text-secondary);
        cursor: pointer;
        font-family: var(--font-body);
        transition: all 0.15s;
    }

    .tab-btn svg { width: 15px; height: 15px; }

    .tab-btn:hover {
        background: var(--accent-light);
        color: var(--accent);
        border-color: var(--accent);
    }

    .tab-btn.active {
        background: var(--accent);
        color: #fff;
        border-color: var(--accent);
    }

    .tab-btn.active:hover {
        background: #245a8a;
    }

    .tab-count {
        background: rgba(255,255,255,0.25);
        font-size: 0.7rem;
        font-weight: 700;
        padding: 0.15rem 0.5rem;
        border-radius: 999px;
    }

    .tab-btn:not(.active) .tab-count {
        background: var(--accent-light);
        color: var(--accent);
    }

    .tab-panel { display: none; }
    .tab-panel.active { display: block; }

    .table-card {
        background: var(--card-bg);
        border-radius: var(--radius);
        box-shadow: var(--card-shadow);
        border: 1px solid var(--border);
        overflow: hidden;
        animation: fadeUp 0.3s ease both;
        width: 100%;
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

    .empty-state {
        padding: 3rem; text-align: center;
        color: var(--text-secondary); font-size: 0.875rem;
    }
    .empty-state svg { width: 32px; height: 32px; margin-bottom: 0.75rem; opacity: 0.4; display: block; margin-inline: auto; }
</style>

<script>
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));

            btn.classList.add('active');
            document.getElementById('tab-' + btn.dataset.tab).classList.add('active');
        });
    });
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>