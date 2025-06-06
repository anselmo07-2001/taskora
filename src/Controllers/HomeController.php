<?php

namespace App\Controllers;

use App\Support\SessionService;

class HomeController extends AbstractController {

    public function showHomePage() {
        $user = SessionService::getSessionKey("user") ?? "";
        $this->render("home.view", [
            "user" => $user,
        ]);
    }
}