<?php 

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\InstitutionModel;
use App\Helpers\SessionHelper;
use App\Helpers\CsrfHelper;

class AuthController
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel        =  new UserModel();
    }

    public function showRegisterForm(): void
    {
        SessionHelper::redirectIfLoggedIn();
        require_once __DIR__ . '/../views/auth/register.php';
    }

    public function handleRegister(): void
    {
        CsrfHelper::validate();

        $fullName       = trim($_POST['full_name']      ??'');
        $email          = trim($_POST['email']          ??'');
        $password       =       $_POST['password']      ??'';
        $confirmPassword =      $_POST['confirm_password']??'';

        //Validation
        $errors = [];

        if(empty($fullName)){
            $errors[] = "Full name is required.";
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errors[] = "A valid email address is required";
        }

        if(strlen($password) < 8){
            $errors[] = "Password must be at least 8 characters.";
        }

        if($password !== $confirmPassword){
            $errors[] = "Passwords do not match.";
        }

        if($this->userModel->emailExists($email)){
            $errors[] = "An account with this email already exists.";
        }

        //---Insert user ---
        $created = $this->userModel->create($fullName, $email, $password);

        if($created){
            header("Location: ". BASE_PATH . "/login?registered=1");
            exit;
        }

        //Fallback: unexpected DB failure
        $errors[] = "Registration failed. Please try again.";
        require_once __DIR__ . '/../views/auth/register.php';
    }

    public function showLoginForm(): void
    {
        SessionHelper::redirectIfLoggedIn();
        require_once __DIR__ . '/../Views/auth/login.php';
    }

    public function handleLogin(): void
    {
        CsrfHelper::validate();
        $email    = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '');
        $password = trim(filter_input(INPUT_POST, 'password', FILTER_DEFAULT)     ?? '');

        $errors = [];

        if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "A valid email address is required.";
        }

        if(empty($password)) {
            $errors[] = "Password is required.";
        }

        if(!empty($errors)) {
            require_once __DIR__ . '/../Views/auth/login.php';
            return;
        }

        $user = $this->userModel->findByEmail($email);

        if(!$user || !password_verify($password, $user['password'])) {
            $errors[] = "Invalid email or password.";
            require_once __DIR__ . '/../Views/auth/login.php';
            return;
        }

        //Regenerate session ID to prevent session fixation
        session_regenerate_id(true);

        //Store user data in session
        $_SESSION['user_id']        = $user['id'];
        $_SESSION['user_name']      = $user['full_name'];
        $_SESSION['user_email']     = $user['email'];
        $_SESSION['user_role']      = $user['role'];
        $_SESSION['institution_id'] = $user['institution_id'];

        SessionHelper::redirectToDashboard();
    }
}