<?php

namespace App\Controllers;

class LoginController extends AbstractController {
    
    public function showLoginPage() {
        $this->render("login.view", []);
    }
}