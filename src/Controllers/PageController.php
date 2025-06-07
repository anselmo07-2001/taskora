<?php

namespace App\Controllers;

use App\Support\SessionService;

class PageController extends AbstractController {

    public function showHomePage() {
        $user = SessionService::getSessionKey("user") ?? "";
        $this->render("home.view", [
            "user" => $user,
        ]);
    }

    public function showProjectFormPage() {
        $this->render("createProject.view", []);
    }
}