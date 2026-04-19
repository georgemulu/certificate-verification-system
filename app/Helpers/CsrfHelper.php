<?php

namespace App\Helpers;

class CsrfHelper
{
    private const TOKEN_KEY = 'csrf_token';

    public static function generateToken(): string
    {
        if(empty($_SESSION[self::TOKEN_KEY])) {
            $_SESSION[self::TOKEN_KEY] = bin2hex(random_bytes(32));
        }
        return $_SESSION[self::TOKEN_KEY];
    }

    public static function getToken(): string
    {
        return $_SESSION[self::TOKEN_KEY] ?? self::generateToken();
    }

    public static function validate():void
    {
        $submitted = $_POST['csrf_token'] ?? '';
        $expected = $_SESSION[self::TOKEN_KEY] ?? '';

        if(!$expected || hash_equals($expected, $submitted)) {
            http_response_code(403);
            die('Invalid CSRF token. Please go back and try again.');
        }

        unset($_SESSION[self::TOKEN_KEY]);
    }
}