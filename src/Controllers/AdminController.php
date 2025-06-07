<?php

namespace App\Controllers;

use App\Repository\UserRespository;
use App\Support\SessionService;
use App\Support\Validation;

class AdminController extends AbstractController {

    public function __construct(protected UserRespository $userRespository){}

    public function handleCreateProject() {
        echo "creating a project";
    }

    public function handleCreateAccount($request) {
        $fullName = trim(sanitize($request["post"]["fullName"])) ?? "";
        $username = trim(sanitize($request["post"]["username"])) ?? "";
        $password = trim($request["post"]["password"]) ?? "";
        $confirmPassword = trim($request["post"]["confirmPassword"]) ?? "";
        $role = $request["post"]["role"] ?? "";

        $errors = [];
        
        if (empty($fullName)) {
            $errors["fullNameErr"] = "Please enter the full name";
        }

        if (empty($username)) {
            $errors["usernameErr"] = "Please enter the username";
        }

        if (!Validation::string($password, 6, 50)) {
            $errors["passwordErr"] = "Please password must at least 6 character long";
        }

        if (!Validation::string($confirmPassword, 6, 50)) {
            $errors["confirmPasswordErr"] = "Please confirm password must at least 6 character long";
        }

        if (!Validation::match($password, $confirmPassword)) {
            $errors["passwordErr"] = "Password not match";
            $errors["confirmPasswordErr"] = "Password Confirm not match";
        }

        if (empty($role)) {
            $errors["roleErr"] = "Please choose the user role";
        }

    
        if (!empty($errors)) {
            $this->render("createAccount.view", [
                "errors" => $errors
            ]);
            exit;
        }

        $user = $this->userRespository->findByUsername($username);

        if (!empty($user)) {
            $errors["usernameErr"] = "Username is already taken";
            $this->render("createAccount.view", [
                "errors" => $errors
            ]);
            exit;
        }

        $formData = [
            "fullName" => $fullName,
            "username" => $username,
            "role" => $role,
            "password" => password_hash($password, PASSWORD_DEFAULT),
            "status" => "active"
        ];
        
        
        $success = $this->userRespository->createAccount($formData);

        if ($success) {
             SessionService::setAlertMessage("success_message", "Created account sucessully");
        }
        else {
             SessionService::setAlertMessage("success_message", "Account creation failed");
        }


         header("Location: index.php?page=home");
         
    }
}