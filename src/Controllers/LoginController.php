<?php

namespace App\Controllers;

use App\Repository\UserRespository;
use App\Support\Validation;

class LoginController extends AbstractController {

    public function __construct(protected UserRespository $userRespository){}

    public function showLoginPage() {
        $this->render("login.view", []);
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

       echo "login";
    }
}