<?php

namespace App\Controllers;

use App\Repository\UserRepository;
use App\Support\Validation;
use App\Support\SessionService;

class AuthController extends AbstractController {

    public function __construct(protected UserRepository $userRepository){}   

    public function handleLogout() {
        SessionService::removeAllSessionData();
        header("Location: index.php" );
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
        $user = $this->userRepository->findByUsername($username);

        if (empty($user)) {
            $errors["usernameErr"] = "No records found for the given username";
            $this->render("login.view", [
                "errors" => $errors
            ]);
            exit;
        }

        if ($user->status === "suspended" || $user->status === "suspended") {
            $errors["userAccountErr"] = "This account is suspended or no longer available. Please contact your admin for support.";
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
            'fullname' => $user->fullname,
            'role' => $user->role,
            'status' => $user->status,
        ]);
        
        var_dump(SessionService::getSessionKey("user"));
        
        header("Location: index.php?page=home");
    }
}