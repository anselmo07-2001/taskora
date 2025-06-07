<?php

namespace App\Controllers;

use App\Repository\UserRespository;
use App\Support\Validation;
use App\Support\SessionService;

class AuthController extends AbstractController {

    public function __construct(protected UserRespository $userRespository){}

    public function showLoginPage() {
        $this->render("login.view", []);
    }

    public function handleLogout() {
        SessionService::removeAllSessionData();
        header("Location: index.php" );
    }

    public function showCreateAccountPage() {
        $this->render("createAccount.view", []);
    }

    public function handleCreateAccount($request) {
        $fullName = trim(sanitize($request["post"]["fullName"])) ?? "";
        $username = trim(sanitize($request["post"]["username"])) ?? "";
        $password = trim($request["post"]["password"]) ?? "";
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

        if (empty($role)) {
            $errors["roleErr"] = "Please choose the user role";
        }

    
        if (!empty($errors)) {
            $this->render("createAccount.view", [
                "errors" => $errors
            ]);
            exit;
        }


        
        
    }

    public function handleLogin($request) {
        $username = sanitize($request["post"]["username"]) ?? "";
        $password = trim($request["post"]["password"]) ?? "";

        $errors = [];

        if (empty($username)) {
            $errors["usernameErr"] = "Please enter a username. This field is required";
        }

        if (!Validation::string($password, 3, 50)) {
             $errors["passwordErr"] = "Please password must at least 3 characters long";
        }
   
        if (!empty($errors)) {
            $this->render("login.view", [
                "errors" => $errors
            ]);
            exit;
        }

        //check if the username exist
        $user = $this->userRespository->findByUsername($username);

        if (empty($user)) {
            $errors["usernameErr"] = "No records found for the given username";
            $this->render("login.view", [
                "errors" => $errors
            ]);
            exit;
        }

        
        // check the password
        if (!password_verify($password, $user->password)) {
             $errors["passwordErr"] = "Invalid Credentials";

              $this->render("login.view", [
                "errors" => $errors
            ]);
             exit;
       }

       

       SessionService::setSessionValue("user", [
            "userId" => $user->id,
            'username' => $user->username,
            'name' => $user->name,
            'role' => $user->role,
            'status' => $user->status,
        ]);
        
        var_dump(SessionService::getSessionKey("user"));
        
        header("Location: index.php?page=home");
    }
}