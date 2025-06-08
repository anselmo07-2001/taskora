<?php

namespace App\Controllers;

use App\Support\SessionService;
use App\Repository\UserRespository;

class PageController extends AbstractController {

    public function __construct(protected UserRespository $userRespository){}   

    public function showHomePage() {
        $user = SessionService::getSessionKey("user") ?? "";
        $this->render("home.view", [
            "user" => $user,
        ]);
    }

    public function showProjectFormPage() {
        $listOfProjectManagers = $this->userRespository->fetchAllActiveUser("project_manager");
        $listOfMembers = $this->userRespository->fetchAllActiveUser("member");

        $this->render("createProject.view", [
            "projectManagers" => $listOfProjectManagers,
            "members" => $listOfMembers,
        ]);
    }

    public function showCreateAccountPage() {
        $this->render("createAccount.view", []);
    }

    public function showLoginPage() {
        $this->render("login.view", []);
    }
}