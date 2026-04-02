<?php

declare(strict_types=1);

//Temporary:enable full error reporting during development
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
//To be removed before going to production fede

require_once __DIR__ . '/../config/bootstrap.php';

$fullUri   = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Strip the base path from the URI
$uri = '/' . ltrim(
    str_starts_with($fullUri, BASE_PATH)
        ? substr($fullUri, strlen(BASE_PATH))
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

}elseif (($uri === '/' || $uri === '/login') && $method === 'GET') {
    $controller = new \App\Controllers\AuthController();
    $controller-> showLoginForm();

}elseif($uri === '/login' && $method ==='POST') {
    $controller = new \App\Controllers\AuthController();
    $controller->handleLogin();

}elseif($uri === '/admin/dashboard') {
    echo "Admin dashboard coming soon.";

}elseif($uri === '/verifier/dashboard') {
    echo "Verifier dashboard coming soon.";

}elseif($uri === '/user/dashboard') {
    echo "User dashboard coming soon.";

} elseif ($uri === '/logout') {
    session_destroy();
    header("Location: ". BASE_PATH ."/login");
    exit;

}else {
    http_response_code(404);
    echo "404 - Page not found";
}