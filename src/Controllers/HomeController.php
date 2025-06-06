<?php

namespace App\Controllers;

use App\Support\SessionService;

class HomeController extends AbstractController {

    public function showHomePage() {
        $user = SessionService::getSessionKey("user") ?? "";
        $user["role"] =  ucwords(str_replace('_', ' ', $user["role"]));

        $this->render("home.view", [
            "user" => $user,
        ]);
    }
}