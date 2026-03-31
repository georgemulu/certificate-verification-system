<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport", content="width=device-width, initial-scale=1.0">
        <title>Register - Certificate Verification System</title>
        <style>
            *,*::before,*::after{box-sizing: border-box; margin: 0; padding: 0;}

            body{
                font-family: 'Segoe UI', sans-serif;
                background: #f0f4f8;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 2rem;
            }

            .card{
                background: #fff;
                border-radius: 10px;
                box-shadow: 0 4px 20px rgba(0,0,0,0.08);
                padding: 2.5rem 2rem;
                width: 100%;
                max-width: 480px;
            }
            
            h1{
                font-size: 1.5rem;
                color: #1a202c;
                margin-bottom: 0.25rem;
            }

            .subtitle{
                font-size: 0.875rem;
                color: #718096;
                margin-bottom: 2rem;
            }

            .error-list {
                background: #fff5f5;
                border: 1px solid #feb2b2;
                border-radius: 6px;
                padding: 0.75rem 1rem;
                margin-bottom: 1.25rem;
                list-style: none;
            }

            .error-list li{
                color: #c53030;
                font-size: 0.875rem;
                padding: 0.2rem 0;
            }

            .form-group{
                margin-bottom: 1.25rem;
            }

            .label {
                display: block;
                font-size: 0.875rem;
                font-weight: 600;
                color: #2d3748;
                margin-bottom: 0.4rem;
            }

            input, select {
                width: 100%;
                padding: 0.65rem 0.85rem;
                border: 1px solid #cbd5e0;
                border-radius: 6px;
                font-size: 0.9rem;
                color: #2d3748;
                background: #fff;
                transition: border-color 0.2s;
            }

            input:focus, select:focus {
                outline: none;
                border-color: #4299e1;
                box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.15);
            }

            .btn{
                width: 100%;
                padding: 0.75rem;
                background: #2b6cb0;
                color: #fff;
                border: none;
                border-radius: 6px;
                font-size: 1rem;
                font-weight: 600;
                cursor: pointer;
                transition: background 0.2s;
                margin-top: 0.5rem;
            }

            .btn:hover {background: #2c5282;}

            .login-link {
                text-align: center;
                margin-top: 1.25rem;
                font-size: 0.875rem;
                color: #718096;
            }

            .login-link a {color: #2b6cb0; text-decoration: none; font-weight: 600;}
            .login-link a:hover {text-decoration: underline;}
        </style>
    </head>
    <body>
        <div class ="card">
            <h1> Create Account</h1>
            <p class="subtitle"> Register to access the Certificate Verification System </p>

            <?php if(!empty($errors)): ?>
                <ul class="error-list">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <form method="POST" action="<?=  BASE_PATH ?>/register" novalidate>

                <div class="form-group">
                    <label for="fullname"> Full name</label>
                    <input
                        type="text"
                        id="full_name"
                        name="full_name"
                        value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>"
                        placeholder="e.g. John Doe"
                        required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                        placeholder="john@institution.edu"
                        required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Minimum 8 characters"
                        required>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input
                        type="password"
                        id="confirm_password"
                        name="confirm_password"
                        placeholder="Repeat your password"
                        required>
                </div>

                <div class="form-group">
                    <label for ="institution_id">Institution</label>
                    <select id="institution_id" name="institution_id" required>
                        <option value="" disabled selected> Select your institution</option>
                        <?php foreach ($institutions as $inst): ?>
                            <option
                                value="<?= (int)$inst['id'] ?>"
                                <?= ((int) ($_POST['institution_id'] ?? 0) === (int)$inst['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($inst['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn">Register</button>
            </form>

            <p class="login-link">Already have an account? <a href="/login">Sign in</a></p>
        </div>
    </body>
</html>