<?php

namespace App\Controllers;

use App\Repository\ProjectRepository;
use App\Support\SessionService;
use App\Repository\UserRepository;

class PageController extends AbstractController {
    protected UserRepository $userRepository;
    protected ProjectRepository $projectRepository;
    protected array|null $currentUserSession;

    public function __construct(UserRepository $userRepository, ProjectRepository $projectRepository){
         $this->userRepository = $userRepository;
         $this->projectRepository = $projectRepository;
         $this->currentUserSession = SessionService::getSessionKey("user") ?? null;
    }   

    public function showProjects() {
        $projects = $this->projectRepository->fetchAllProjects();
        $this->render("projects.view",[
            "projects" => $projects
        ]);
    }

    public function showHomePage() {
        $this->render("home.view", [
            "user" => $this->currentUserSession,
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