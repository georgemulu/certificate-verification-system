<?php
$pageTitle = 'Verify Certificate';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';
?>

<div class="page-content">

    <?php if(!empty($errors)): ?>
        <div class="alert alert-danger">
            <i data-lucide="circle-x"></i>
            <div>
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error) ?>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="verify-wrapper">
        <div class="verify-heading">
            <h2>Verify Certificate</h2>
            <p>Enter the serial number printed on the certificate and select the issuing institution.</p>
        </div>
    

        <?php if(isset($result)): ?>
            <?php if ($result === 'authentic'): ?>
                <div class="result-card result-authentic">
                    <div class="result-icon"><i data-lucide="shield-check"></i></div>
                    <div class="result-body">
                        <h3>Certificate Authentic</h3>
                        <p>This certificate is valid and has not been tampered with.</p>
                        <div class="cert-details">
                            <div class="cert-detail-row">
                                <span class="detail-label">Owner</span>
                                <span class="detail-value"><?= htmlspecialchars($cert['owner_name']) ?></span>
                            </div>
                            <div class="cert-detail-row">
                                <span class="detail-label">Type</span>
                                <span class="detail-value"><?= htmlspecialchars($cert['certificate_type']) ?></span>
                            </div>
                            <div class="cert-detail-row">
                                <span class="detail-label">Course</span>
                                <span class="detail-value"><?= htmlspecialchars($cert['course']) ?></span>
                            </div>
                            <div class="cert-detail-row">
                                <span class="detail-label">Issued</span>
                                <span class="detail-value"><?= htmlspecialchars(date('F j, Y', strtotime($cert['issued_at']))) ?></span>
                            </div>
                        </div>
                    </div>
                </div>

            <?php elseif ($result === 'tampered'): ?>
                <div class="result-card result-tampered">
                    <div class="result-icon"><i data-lucide="shield-x"></i></div>
                    <div class="result-body">
                        <h3>Certificate Tampered</h3>
                        <p>A record was found but the certificate data does not match. This certificate may have been altered.</p>
                    </div>
                </div>

            <?php elseif($result === 'revoked'): ?>
                <div class="result-card result-revoked">
                    <div class="result-icon"><i data-lucide="shield-off"></i></div>
                    <div class="result-body">
                        <h3>Certificate Revoked</h3>
                        <p>This certificate has been revoked by the issuing institution and is no longer valid.</p>
                    </div>
                </div>
            
            <?php elseif($result === 'invalid'): ?>
                <div class="result-card result-invalid">
                    <div class="result-icon"><i data-lucide="shield-alert"></i></div>
                    <div class="result-body">
                        <h3>Certificate Not found</h3>
                        <p>No certificate matching this serial number was found for the selected institution. </p>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="form-card">
            <form method="POST" action="<?= BASE_PATH ?>/user/verify" novalidate>
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\App\Helpers\CsrfHelper::getToken()) ?>">

                <div class="form-group">
                    <label for="institution_id">Issuing Institution</label>
                    <select id="institution_id" name="institution_id" required>
                        <option value="" disabled <?= empty($_POST['institution_id']) ? 'selected' : '' ?>>
                            Select institution
                        </option>
                        <?php foreach ($institutions as $inst): ?>
                            <option value="<?= $inst['id'] ?>"
                                <?= ((int)($_POST['institution_id'] ?? 0)) === (int)$inst['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($inst['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="serial_number">Certificate Serial Number</label>
                    <input
                        type="text"
                        id="serial_number"
                        name="serial_number"
                        value="<?= htmlspecialchars($_POST['serial_number'] ?? '') ?>"
                        required> 
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <i data-lucide="shield-check"></i>
                        Verify Certificate
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<style>
    .page-content {
        display: flex;
        justify-content: center;
        padding: 2.5rem 2rem;
    }

    .verify-wrapper {
        width: 100%;
        max-width: 520px;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .verify-heading h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .verify-heading p {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin-top: 0.3rem;
    }
    .result-card {
        display: flex;
        align-items: flex-start;
        gap: 1.25rem;
        padding: 1.5rem;
        border-radius: var(--radius);
        margin-bottom: 1.75rem;
        border: 1px solid;
        animation: fadeUp  0.3s ease both;
    }

    .result-icon {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .result-icon svg {width: 22px; height: 22px;}

    .result-icon h3 {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .result-body p {
        font-size: 0.875rem;
        margin: 0;
    }

    .result-authentic {
        background: var(--success-light);
        border-color: #9ae6b4;
        color: var(--success);
    }

    .result-authentic .result-icon { background: #c6f6d5;}

    .result-tampered, .result-revoked {
        background: var(--danger-light);
        border: #feb2b2;
        color: var(--danger);
    }

    .result-tampered .result-icon,
    .result-revoked .result-icon {background: #fed7d7;}

    .result-invalid {
        background: var(--warning-light);
        border-color: #f6d860;
        color: var(--warning);
    }

    .result-invalid .result-icon {background: #fefcbf;}

    .cert-details {
        margin-top: 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .cert-detail-row {
        display: flex;
        gap: 1rem;
        font-size: 0.875rem;
    }

    .detail-label {
        font-weight: 600;
        min-width: 60px;
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

    .alert-danger {
        background: var(--danger-light);
        border: 1px solid #feb2b2;
        color: var(--danger);
    }

    .alert p {margin: 0;}

    .form-card {
        background: var(--card-bg);
        border-radius: var(--radius);
        box-shadow: var(--card-shadow);
        border: 1px solid var(--border);
        padding: 2rem;
        max-width: 500px;
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

    .form-group input, .form-group select {
        width: 80%;
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
</style>

<?php require_once __DIR__  . '/../layouts/footer.php'; ?>