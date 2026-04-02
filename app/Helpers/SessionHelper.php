<?php
namespace App\Helpers;

//I have implemented session guard logic here
class SessionHelper
{
    //To check if a user is logged in, if not redirected to login page.

    public static function requireLogin(): void 
    {
        if(empty($_SESSION['user_id'])) {
            header("Location: " . BASE_PATH . "/login");
            exit;
        }
    }

    /**
     * Check if the logged in user has the required role
     * If not, redirect to their appropriate dashboard
     */

    public static function requireRole(string ...$roles): void
    {
        self::requireLogin();

        if(!in_array($_SESSION['user_role'], $roles, true)) {
            self::redirectToDashboard();
        }
    }

    //Redirect user to their appropriate role dashboard
    public static function redirectToDashboard(): void 
    {
        $role = $_SESSION['user_role'] ?? '';

        if($role === 'Admin') {
            header("Location: " . BASE_PATH . "/admin/dashboard");
        } elseif($role === 'Verifier') {
            header("Location: " . BASE_PATH . "/verifier/dashboard");
        } else {
            header("Location: ". BASE_PATH ."/user/dashboard");
        }
        exit;
    }

    //To make sure that logged in users are not showed login page
    public static function redirectIfLoggedIn(): void
    {
        if(!empty($_SESSION['user_id'])) {
            self::redirectToDashboard();
        }
    }

    //Get a value from the session safely
    public static function get(string $key): mixed
    {
        return $_SESSION[$key] ?? null;
    }

    //Check if the current user has a specific role
    public static function hasRole(string $role):bool 
    {
        return ($_SESSION['user_role'] ?? '') === $role;
    }

    //Destroy session and log the user out
    public static function destroy(): void 
    {
        $_SESSION = [];

        if(ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();
    }
}