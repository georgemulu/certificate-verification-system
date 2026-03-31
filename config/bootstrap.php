<?php

declare(strict_types=1);

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

            // Strip surrounding quotes if present: "value" or 'value'
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

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Base path constant
define('BASE_PATH', dirname($_SERVER['SCRIPT_NAME'] ?? ''));