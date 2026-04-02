<?php 

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\InstitutionModel;
use App\Helpers\SessionHelper;

class AuthController
{
    private UserModel $userModel;
    private InstitutionModel $institutionModel;

    public function __construct()
    {
        $this->userModel        =  new UserModel();
        $this->institutionModel =  new InstitutionModel();
    }

    public function showRegisterForm(): void
    {
        SessionHelper::redirectIfLoggedIn();
        $institutions = $this->institutionModel->getAll();
        require_once __DIR__ . '/../views/auth/register.php';
    }

    public function handleRegister(): void
    {
        $fullName       = trim($_POST['full_name']      ??'');
        $email          = trim($_POST['email']          ??'');
        $password       =       $_POST['password']      ??'';
        $confirmPassword =      $_POST['confirm_password']??'';
        $institutionId  = (int)($_POST['institution_id'] ?? 0);

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

        if($institutionId <=0){
            $errors[] = "Please select a valid institution";
        }

        if($this->userModel->emailExists($email)){
            $errors[] = "An account with this email already exists.";
        }

        //re-render form--
        if(!empty($errors)){
            $institutions = $this->institutionModel->getAll();
            require_once __DIR__ . '/../views/auth/register.php';
            return;
        }

        //---Insert user ---
        $created = $this->userModel->create($fullName, $email, $password, $institutionId);

        if($created){
            header("Location: ". BASE_PATH . "/login?registered=1");
            exit;
        }

        //Fallback: unexpected DB failure
        $errors[] = "Registration failed. Please try again.";
        $institutions = $this->institutionModel->getAll();
        require_once __DIR__ . '/../views/auth/register.php';
    }

    public function showLoginForm(): void
    {
        SessionHelper::redirectIfLoggedIn();
        require_once __DIR__ . '/../Views/auth/login.php';
    }

    public function handleLogin(): void
    {
        
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