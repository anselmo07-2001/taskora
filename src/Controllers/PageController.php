<?php

namespace App\Controllers;

use App\Support\SessionService;
use App\Repository\UserRepository;

class PageController extends AbstractController {

    public function __construct(protected UserRepository $userRepository){}   

    public function showHomePage() {
        $user = SessionService::getSessionKey("user") ?? "";
        $this->render("home.view", [
            "user" => $user,
        ]);
    }

    public function showProjectFormPage() {
        $listOfProjectManagers = $this->userRepository->fetchAllActiveUser("project_manager");
        $listOfMembers = $this->userRepository->fetchAllActiveUser("member");

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