<?php 

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\InstitutionModel;

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
}