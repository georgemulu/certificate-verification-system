<?php

use App\Helpers\SessionHelper;

$pageTitle = 'User Management';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';
?>

<div class="page-content">
    <div class="page-header">
        <h2>Users</h2>
        <p>Manage user roles and institution assignments</p>
    </div>

    <?php if(isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <i data-lucide="circle-check"></i>
            <div>
                <?php if ($_GET['success'] === 'role'): ?>
                    <strong> User role updated successfully.</strong>
                <?php elseif ($_GET['success'] === 'institution'): ?>
                    <strong>User institution updated successfully.</strong>
                <?php elseif ($_GET['success'] === 'deleted'): ?>
                    <strong>User account deleted successfully. </strong>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if(isset($_GET['error'])): ?>
        <div class="alert alert-danger">
            <i data-lucide="circle-x"></i>
            <div>
                <?php if($_GET['error'] === 'selfmod'): ?>
                    <strong> You cannot change your own role.</strong>
                <?php elseif ($_GET['error'] === 'invalid'): ?>
                    <strong> Invalid request. </strong>
                <?php elseif ($_GET['error'] === 'selfdelete'): ?>
                    <strong> You cannot delete your own account. </strong>
                <?php else: ?>
                    <strong> Update failed. Please try again. </strong>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="info-banner">
        <i data-lucide="info"></i>
        <p>When assigning the <strong>Verifier</strong> role to a user, ensure they are also assigned an institution, otherwise they will not be able to upload certificates</p>
    </div>

    <div class="table-card">
        <div class="table-card-header">
            <h3>All Users</h3>
            <span class="count-badge"><?= count($users) ?></span>
        </div>

        <?php if(empty($users)): ?>
            <div class="empty-state">
                <i data-lucide="users"></i>
                <p>No users found.</p>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Institution</th>
                        <th>Registered</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($users as $user): ?>
                        <?php $isSelf = (int)$user['id'] === (int)SessionHelper::get('user_id'); ?>
                        <tr>
                            <td>
                                <?= htmlspecialchars($user['full_name']) ?>
                                <?php if($isSelf): ?>
                                    <span class="you-badge">You</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td>
                                <span class="role-pill <?= strtolower($user['role']) ?>">
                                    <?= htmlspecialchars($user['role']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($user['institution_name'] ?? '-') ?></td>
                            <td><?= date('M j, Y', strtotime($user['created_at'])) ?></td>
                            <td>
                                <?php if(!$isSelf): ?>
                                    <div class="action-forms">

                                        <form method="POST" action="<?= BASE_PATH ?>/admin/users/role">
                                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\App\Helpers\CsrfHelper::getToken()) ?>">
                                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                            <div class="inline-form">
                                                <select name="role">
                                                    <?php foreach(['User', 'Verifier', 'Admin'] as $r): ?>
                                                        <option value="<?= $r ?>"<?= $user['role'] === $r ? 'selected' : ''?>>
                                                            <?= $r ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <button type="submit" class="btn-action">
                                                    <i data-lucide="check"></i>
                                                </button>
                                            </div>
                                        </form>

                                        <form method="POST" action="<?= BASE_PATH ?>/admin/users/institution">
                                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\App\Helpers\CsrfHelper::getToken()) ?>">
                                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                            <div class="inline-form">
                                                <select name="institution_id">
                                                    <option value="">- No institution -</option>
                                                    <?php foreach ($institutions as $inst): ?>
                                                        <option value="<?= $inst['id'] ?>"
                                                            <?= ($user['institution_name'] === $inst['name']) ? 'selected' : '' ?>>
                                                            <?= htmlspecialchars($inst['name']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <button type="submit" class="btn-action">
                                                    <i data-lucide="check"></i>
                                                </button>
                                            </div>
                                        </form>

                                        <form method="POST" action="<?= BASE_PATH ?>/admin/users/delete"
                                            onsubmit="return confirm('Permanently delete this user account? This action cannot be undone.')">
                                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\App\Helpers\CsrfHelper::getToken()) ?>">
                                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                            <button type="submit" class="btn-delete">
                                                <i data-lucide="trash-2"></i>
                                                Delete
                                            </button>
                                        </form>

                                    </div>
                                <?php else: ?>
                                    <span class="no-action">-</span>
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
        gap: 0.75rem;
    }

    .table-card-header h3 {
        font-size: 1rem;
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

    .role-pill {
        font-size: 0.72rem; font-weight: 600;
        padding: 0.2rem 0.65rem; border-radius: 999px;
        text-transform: uppercase; letter-spacing: 0.06em;
        display: inline-block;
    }
    .role-pill.admin    { background: #fef3dc; color: #b7791f; }
    .role-pill.user     { background: var(--accent-light); color: var(--accent); }
    .role-pill.verifier { background: var(--success-light); color: var(--success); }

    .you-badge {
        font-size: 0.68rem; font-weight: 700;
        background: var(--accent-light); color: var(--accent);
        padding: 0.15rem 0.5rem; border-radius: 999px;
        margin-left: 0.4rem; vertical-align: middle;
    }

    .action-forms {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .inline-form {
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .inline-form select {
        padding: 0.4rem 0.6rem;
        border: 1px solid var(--border);
        border-radius: 6px;
        font-size: 0.8rem;
        font-family: var(--font-body);
        color: var(--text-primary);
        background: #fff;
        cursor: pointer;
    }

    .inline-form select:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(45,106,159,0.12);
    }

    .btn-action {
        display: inline-flex; align-items: center; justify-content: center;
        width: 30px; height: 30px;
        background: var(--accent); color: #fff;
        border: none; border-radius: 6px;
        cursor: pointer; transition: background 0.2s;
        flex-shrink: 0;
    }
    .btn-action:hover { background: #245a8a; }
    .btn-action svg { width: 14px; height: 14px; }

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
    }
    .empty-state svg { width: 32px; height: 32px; margin-bottom: 0.75rem; opacity: 0.4; }
    .info-banner {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 0.85rem 1.5rem;
    background: #fffbeb;
    border-bottom: 1px solid #f6d860;
    color: var(--warning);
    font-size: 0.825rem;
    }

    .info-banner svg {
        width: 16px;
        height: 16px;
        flex-shrink: 0;
        margin-top: 0.15rem;
    }

    .info-banner p { margin: 0; }

    .btn-delete {
        display: inline-flex; align-items: center; gap: 0.4rem;
        padding: 0.4rem 0.75rem;
        background: var(--danger-light); color: var(--danger);
        border: 1px solid #feb2b2; border-radius: 6px;
        font-size: 0.8rem; font-weight: 600;
        cursor: pointer; font-family: var(--font-body);
        transition: background 0.2s;
        width: fit-content;
    }
    .btn-delete:hover { background: #fed7d7; }
    .btn-delete svg { width: 14px; height: 14px; }
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
