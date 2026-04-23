<?php
$pageTitle = 'My Certificates';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';
?>

<div class="page-content">
    <div class="page-header">
        <h2>My Certificates</h2>
        <p>All certificates you have uploaded on behalf of your institution.</p>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <i data-lucide="circle-check"></i>
            <div>
                <?php if ($_GET['success'] === 'revoked'): ?>
                    <strong>Certificate revoked successfully.</strong>
                    <p>This certificate will now show as revoked during verification.</p>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">
            <i data-lucide="circle-x"></i>
            <div>
                <?php if ($_GET['error'] === 'invalid'): ?>
                    <strong>Invalid request.</strong>
                <?php else: ?>
                    <strong>Action failed. Please try again.</strong>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="table-card">
        <div class="table-card-header">
            <h3>Uploaded Certificates</h3>
            <div class="header-right">
                <span class="count-badge"><?= count($certificates) ?></span>
                <a href="<?= BASE_PATH ?>/verifier/upload" class="btn-primary">
                    <i data-lucide="file-up"></i>
                    Upload New
                </a>
            </div>
        </div>

        <?php if (empty($certificates)): ?>
            <div class="empty-state">
                <i data-lucide="file-text"></i>
                <p>You have not uploaded any certificates yet.</p>
                <a href="<?= BASE_PATH ?>/verifier/upload" class="btn-primary" style="margin-top:1rem;">
                    <i data-lucide="file-up"></i>
                    Upload Certificate
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
                        <th>Issued</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($certificates as $cert): ?>
                        <tr>
                            <td><code><?= htmlspecialchars($cert['serial_number']) ?></code></td>
                            <td><?= htmlspecialchars($cert['owner_name']) ?></td>
                            <td><?= htmlspecialchars($cert['certificate_type']) ?></td>
                            <td><?= htmlspecialchars($cert['course']) ?></td>
                            <td><?= date('M j, Y', strtotime($cert['issued_at'])) ?></td>
                            <td>
                                <?php if ($cert['is_revoked']): ?>
                                    <span class="badge badge-danger">Revoked</span>
                                <?php else: ?>
                                    <span class="badge badge-success">Active</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!$cert['is_revoked']): ?>
                                    <form method="POST" action="<?= BASE_PATH ?>/verifier/certificates/revoke"
                                        onsubmit="return confirm('Revoke this certificate? This cannot be undone.')">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\App\Helpers\CsrfHelper::getToken()) ?>">
                                        <input type="hidden" name="certificate_id" value="<?= $cert['id'] ?>">
                                        <button type="submit" class="btn-revoke">
                                            <i data-lucide="shield-off"></i>
                                            Revoke
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span class="no-action">—</span>
                                <?php endif; ?>
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

    .btn-revoke {
        display: inline-flex; align-items: center; gap: 0.4rem;
        padding: 0.4rem 0.85rem;
        background: var(--danger-light); color: var(--danger);
        border: 1px solid #feb2b2; border-radius: 6px;
        font-size: 0.8rem; font-weight: 600;
        cursor: pointer; font-family: var(--font-body);
        transition: background 0.2s;
    }
    .btn-revoke:hover { background: #fed7d7; }
    .btn-revoke svg { width: 14px; height: 14px; }

    .no-action { color: var(--text-secondary); font-size: 0.875rem; }

    .alert {
        display: flex; align-items: flex-start; gap: 0.75rem;
        padding: 1rem 1.25rem; border-radius: var(--radius);
        margin-bottom: 1.5rem; font-size: 0.875rem;
    }
    .alert svg { width: 20px; height: 20px; flex-shrink: 0; margin-top: 0.1rem; }
    .alert-success { background: var(--success-light); border: 1px solid #9ae6b4; color: var(--success); }
    .alert-danger  { background: var(--danger-light);  border: 1px solid #feb2b2; color: var(--danger); }
    .alert strong { display: block; }
    .alert p { margin: 0; }

    .empty-state {
        padding: 3rem; text-align: center;
        color: var(--text-secondary); font-size: 0.875rem;
        display: flex; flex-direction: column; align-items: center;
    }
    .empty-state svg { width: 32px; height: 32px; margin-bottom: 0.75rem; opacity: 0.4; }
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>