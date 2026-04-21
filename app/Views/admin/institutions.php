<?php
$pageTitle = 'Institution Management';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';
?>

<div class="page-content">
    <div class="page-header">
        <h2>Institutions</h2>
        <p>Manage the institutions registered in the system.</p>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <i data-lucide="circle-check"></i>
            <div>
                <?php if ($_GET['success'] === 'created'): ?>
                    <strong>Institution added successfully.</strong>
                <?php elseif ($_GET['success'] === 'deleted'): ?>
                    <strong>Institution deleted successfully.</strong>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">
            <i data-lucide="circle-x"></i>
            <div>
                <?php if ($_GET['error'] === 'invalid' || $_GET['error'] === 'notfound'): ?>
                    <strong>Institution not found. </strong>
                <?php elseif ($_GET['error'] === 'failed'): ?>
                    <strong>Could not delete institution. It may have users ot certificates linked to it.</strong>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <i data-lucide="circle-x"></i>
            <div>
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="institutions-layout">

        <div class="form-card">
            <h3>Add Institution</h3>
            <form method="POST" action="<?= BASE_PATH ?>/admin/institutions/create" novalidate>
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\App\Helpers\CsrfHelper::getToken()) ?>">

                <div class="form-group">
                    <label for="name">Institution Name</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                        required>
                </div>

                <div class="form-group">
                    <label for="email">Institution Email <span class="optional">(optional)</span></label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <i data-lucide="plus"></i>
                        Add Institution
                    </button>
                </div>
            </form>
        </div>

        <div class="table-card">
            <div class="table-card-header">
                <h3>All Institutions</h3>
                <span class="count-badge"><?= count($institutions) ?></span>
            </div>

            <?php if (empty($institutions)): ?>
                <div class="empty-state">
                    <i data-lucide="building"></i>
                    <p>No institutions registered yet.</p>
                </div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Registered</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($institutions as $inst): ?>
                            <tr>
                                <td><?= htmlspecialchars($inst['name']) ?></td>
                                <td><?= htmlspecialchars($inst['email']) ?? '-' ?></td>
                                <td><?= date('M j, Y', strtotime($inst['created_at'])) ?></td>
                                <td>
                                    <form method="POST" action="<?= BASE_PATH ?>/admin/institutions/delete"
                                        onsubmit="return confirm('Delete this institution? This will also remove all users linked to it.')">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\App\Helpers\CsrfHelper::getToken()) ?>">
                                        <input type="hidden" name="institution_id" value="<?= $inst['id'] ?>">
                                        <button type="submit" class="btn-delete">
                                            <i data-lucide="trash-2"></i>
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

    </div>
</div>

<style>
    .institutions-layout {
        display: flex;
        flex-direction: column;
        gap: 1.75rem;
    }

    .form-card {
        background: var(--card-bg);
        border-radius: var(--radius);
        box-shadow: var(--card-shadow);
        border: 1px solid var(--border);
        padding: 1.75rem;
        max-width: 520px;
        animation: fadeUp 0.4s ease both;
    }

    .form-card h3 {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 1.25rem;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-group label {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.4rem;
    }

    .form-group input {
        width: 100%;
        padding: 0.65rem 0.85rem;
        border: 1px solid var(--border);
        border-radius: 6px;
        font-size: 0.9rem;
        color: var(--text-primary);
        font-family: var(--font-body);
        background: #fff;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-group input:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(45, 106, 159, 0.12);
    }

    .optional {
        font-weight: 400;
        color: var(--text-secondary);
        font-size: 0.8rem;
    }

    .form-actions {
        margin-top: 1.5rem;
        padding-top: 1.25rem;
        border-top: 1px solid var(--border);
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.7rem 1.5rem;
        background: var(--accent);
        color: #fff;
        border: none;
        border-radius: 6px;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        font-family: var(--font-body);
        transition: background 0.2s;
        text-decoration: none;
    }

    .btn-primary:hover {
        background: #245a8a;
    }

    .btn-primary svg {
        width: 16px;
        height: 16px;
    }

    .btn-delete {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.4rem 0.85rem;
        background: var(--danger-light);
        color: var(--danger);
        border: 1px solid #feb2b2;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        font-family: var(--font-body);
        transition: background 0.2s;
    }

    .btn-delete:hover {
        background: #fed7d7;
    }

    .btn-delete svg {
        width: 14px;
        height: 14px;
    }

    .table-card {
        background: var(--card-bg);
        border-radius: var(--radius);
        box-shadow: var(--card-shadow);
        border: 1px solid var(--border);
        overflow: hidden;
        animation: fadeUp 0.4s ease 0.1s both;
        width: 100%;
    }

    .table-card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .table-card-header h3 {
        font-size: 1 rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .count-badge {
        background: var(--accent-light);
        color: var(--accent);
        font-size: 0.75rem;
        font-weight: 700;
        padding: 0.2rem 0.6rem;
        border-radius: 999px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.875rem;
    }

    thead th {
        background: #f8fafc;
        padding: 0.75rem 1.5rem;
        text-align: left;
        font-size: 0.72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        color: var(--text-secondary);
        border-bottom: 1px solid var(--border);
    }

    tbody tr {
        border-bottom: 1px solid var(--border);
        transition: background 0.15s;
    }

    tbody tr:last-child {
        border-bottom: none;
    }

    tbody tr:hover {
        background: #f8fafc;
    }

    tbody td {
        padding: 0.85rem 1.5rem;
        color: var(--text-primary);
        vertical-align: middle;
    }

    .alert {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 1rem 1.25rem;
        border-radius: var(--radius);
        margin-bottom: 1.5rem;
        font-size: 0.875rem;
    }

    .alert svg {
        width: 20px;
        height: 20px;
        flex-shrink: 0;
        margin-top: 0.1rem;
    }

    .alert-success {
        background: var(--success-light);
        border: 1px solid #9ae6b4;
        color: var(--success);
    }

    .alert-danger {
        background: var(--danger-light);
        border: 1px solid #feb2b2;
        color: var(--danger);
    }

    .alert strong {
        display: block;
    }

    .alert p {
        margin: 0;
    }

    .empty-state {
        padding: 3rem;
        text-align: center;
        color: var(--text-secondary);
        font-size: 0.875rem;
    }

    .empty-state svg {
        width: 32px;
        height: 32px;
        margin-bottom: 0.75rem;
        opacity: 0.4;
    }
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>