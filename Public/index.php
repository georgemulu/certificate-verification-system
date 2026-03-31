<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/bootstrap.php';

$scriptDir = dirname($_SERVER['SCRIPT_NAME']);
$fullUri   = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Strip the base path from the URI
$uri = '/' . ltrim(
    str_starts_with($fullUri, $scriptDir)
        ? substr($fullUri, strlen($scriptDir))
        : $fullUri,
    '/'
);

// Normalize to just / if empty
if ($uri === '') {
    $uri = '/';
}

$method = $_SERVER['REQUEST_METHOD'];

if($uri === '/register' && $method === 'GET') {
    $controller = new \App\Controllers\AuthController();
    $controller->showRegisterForm();

} elseif($uri ==='/register' && $method === 'POST') {
    $controller = new \App\Controllers\AuthController();
    $controller->handleRegister();

}elseif ($uri === '/' || $uri === '/login') {
    $controller = new \App\Controllers\AuthController();
    $controller-> showLoginForm();

}else {
    // 404 - No matching route
    http_response_code(404);
    echo "404 - Page not found";
}