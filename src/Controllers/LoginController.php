<?php

namespace App\Controllers;

use App\Support\Validation;

class LoginController extends AbstractController {
    
    public function showLoginPage() {
        $this->render("login.view", []);
    }

    public function handleLogin($request) {
        $username = sanitize($request["post"]["username"]);
        $password = $request["post"]["password"];

        $errors = [];

        if (empty($username)) {
            $errors["usernameErr"] = "Please enter a username. This field is required";
        }

        if (!Validation::string($password, 3, 50)) {
             $errors["passwordErr"] = "Please password must at least 3 characters long";
        }
   
        if (!empty($errors)) {
            $this->render("login.view", $errors);
            exit;
        }

        echo "login";
    }
}