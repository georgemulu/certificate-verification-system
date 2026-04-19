<?php

declare(strict_types=1);

$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
define('BASE_PATH', rtrim(dirname($scriptName),'/'));

// --- Load .env file ---
$envFile = __DIR__ . '/../.env';

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Skip comments
        if (str_starts_with(trim($line), '#')) {
            continue;
        }

        if (str_contains($line, '=')) {
            [$key, $value] = explode('=', $line, 2);

            $key   = trim($key);
            $value = trim($value);

            if (
                (str_starts_with($value, '"') && str_ends_with($value, '"')) ||
                (str_starts_with($value, "'") && str_ends_with($value, "'"))
            ) {
                $value = substr($value, 1, -1);
            }

            $_ENV[$key] = $value;
        }
    }
}

//--- Autoloader ---
spl_autoload_register(function(string $class): void {
    
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../app/';

    if(!str_starts_with($class, $prefix)) {
        return;
    }

    $relative = substr($class, strlen($prefix));
    $file     = $baseDir . str_replace('\\','/', $relative) . '.php';

    if(file_exists($file)) {
        require_once $file;
    }
});

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => false, //Set to true before production to deployment
    'httponly' => true,
    'samesite' => 'Strict',
]);

session_start();

if(isset($_SESSION['user_id'])) {
    $expectedAgent = $_SESSION['_ua'] ?? null;
    $currentAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

    if($expectedAgent === null) {
        $_SESSION['_ua'] = $currentAgent;
    } elseif (!hash_equals($expectedAgent, $currentAgent)) {
        
        session_unset();
        session_destroy();
        header('Location: '. BASE_PATH . '/login');
        exit;
    }

    $timeout = 30 * 60;
    if (isset($_SESSION['_last_active']) && (time() - $_SESSION['_last_active']) > $timeout) {
        session_unset();
        session_destroy();
        header('Location:' . BASE_PATH . '/login?timeout=1');
        exit;
    }

    $_SESSION['_last_active'] = time();
}