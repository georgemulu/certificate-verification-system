<?php

use App\Helpers\SessionHelper;
$pageTitle = 'Upload Certificate';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';
?>

<div class="page-content">
    <div class="page-header">
        <h2>Upload Certificate</h2>
        <p>Fill in the details below to issue a new certificate.</p>
    </div>

    <?php if(isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <i data-lucide="circle-check"></i>
            <div>
                <strong>Certificate uploaded successfully.</strong>
                <p>Verification code: <code><?= htmlspecialchars($_GET['code'] ?? '') ?></code></p>
                <p style="margin-top:0.35rem; font-size:0.8rem;">
                    Keep this code safe - it is the unique identifier used to verify this certificate.
                </p>
            </div>
        </div>
    <?php endif; ?>

    <?php if(!empty($errors)): ?>
        <div class="alert alert-danger">
            <i data-lucide="circle-x"></i>
            <div>
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="form-card">
        <form method="POST" action="<?= BASE_PATH ?>/verifier/upload" novalidate>

                <div class="form-group">
                    <label for="owner_name">Certificate Owner Name</label>
                    <input
                        type="text"
                        id="owner_name"
                        name="owner_name"
                        value="<?= htmlspecialchars($_POST['owner_name'] ?? '') ?>"
                        required> 
                    <span class="form-hint"> Full name of the person this certificate belongs to.</span>
                </div>

                <div class="form-group">
                    <label for="certificate_type">Certificate Type</label>
                    <select id="certificate_type" name="certificate_type" required>
                        <option value="" disabled <?= empty($_POST['certificate_type']) ? 'selected' : '' ?>>
                            Select certificate type
                        </option>
                        <?php
                        $types = ['Certificate', 'Diploma', 'Degree', 'Masters' , 'PhD'];
                        foreach($types as $type):
                        ?>
                            <option value="<?= $type ?>"
                                <?= ($_POST['certificate_type'] ?? '') === $type ? 'selected' : '' ?>>
                                <?= $type ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="form-hint"> The level of the cerrtificate being registered.</span>
                </div>
            
            <div class="form-group">
                <label for="course">Course / Programme</label>
                <input
                    type="text"
                    id="course"
                    name="course"
                    value="<?= htmlspecialchars($_POST['course'] ?? '') ?>"
                    placeholder="e.g. Bachelor of Science in Computer Science"
                    required
                >
                <span class="form-hint">The full name of the course or programme of study.</span>
            </div>

            <div class="form-group">
                <label for="serial_number"> Certificate Serial Number</label>
                <input
                    type="text"
                    id="serial_number"
                    name="serial_number"
                    value="<?= htmlspecialchars($_POST['serial_number'] ?? '') ?>"
                    placeholder="e.g. CUEA/2023/00142"
                    required> 
                <span class="form-hint">
                    The serial number printed on the physical certificate.
                    Must be unique within your institution.
                </span>
            </div>

            <div class="form-group">
                <label for="issued_at">Date Issued</label>
                <input
                    type = "date"
                    id = "issued_at"
                    name = "issued_at"
                    value = "<?= htmlspecialchars($_POST['issued_at'] ?? '') ?>"
                    max="<?=  date('Y-m-d') ?>"
                    required> 
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">
                    <i data-lucide="file-up"></i>
                    Upload certificate
                </button>
                <a href="<?= BASE_PATH ?>/verifier/dashboard" class="btn-secondary">
                    Cancel
                </a>
            </div>

        </form>
    </div>
</div>

<style>
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

    .alert strong {display: block; margin-bottom: 0.25rem;}
    .alert p {margin: 0;}

    .alert code {
        background: rgba(0,0,0,0.08);
        padding: 0.15rem 0.45rem;
        border-radius: 4px;
        font-family: monospace;
        font-size: 0.9rem;
        font-weight: 700;
        letter-spacing: 0.05em;
    }

    .form-card {
        background: var(--card-bg);
        border-radius: var(--radius);
        box-shadow: var(--card-shadow);
        border: 1px solid var(--border);
        padding: 2rem;
        max-width: 640px;
        animation: fadeUp 0.4s ease both;
    }

    .form-group {
        margin-bottom: 1.5rem;
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

    .form-group input:focus, 
    .form-group select:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(45,106,159,0.12);
    }

    .form-group input:disabled {
        background: #f4f7fb;
        color: var(--text-secondary);
        cursor: not-allowed;
    }

    .form-hint {
        display: block;
        font-size: 0.78rem;
        color: var(--text-secondary);
        margin-top: 0.35rem;
    }

    .form-actions {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--border);
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.7rem  1.5rem;
        background: var(--accent);
        color: #fff;
        border: none;
        border-radius: 6px;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        font-family: var(--font-body);
        transition: background 0.2s;
    }

    .btn-primary:hover {background: #245a8a;}

    .btn-primary svg {
        width: 16px;
        height: 16px;
    }

    .btn-secondary{
        display: inline-flex;
        align-items: center;
        padding: 0.7rem 1.25rem;
        background: transparent;
        color: var(--text-secondary);
        border: 1px solid var(--border);
        border-radius: 6px;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        font-family: var(--font-body);
        transition: background 0.2s, color 0.2s;
    }

    .btn-secondary:hover {
        background: var(--border);
        color: var(--text-primary);
    }
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>