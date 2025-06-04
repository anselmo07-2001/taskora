<?php

namespace App\Controllers;

class LoginController extends AbstractController {
    
    public function showLoginPage() {
        $this->render("login.view", []);
    }

    public function handleLogin($request) {
        $username = $request["post"]["username"];
        $password = $request["post"]["password"];

        echo $username;
        echo $password;
    }
}