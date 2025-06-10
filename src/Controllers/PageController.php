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

    public function showProject() {
        $this->render("project.view", []);
    }


    public function showProjects() {
        $filter = $_GET["filter"] ?? "";
        $search = $_GET["search"] ?? "";
        $today = date("Y-m-d");

        $whereClauses = [];
        $params = [];

        if ($filter === 'due_today') {
            $whereClauses[] = "projects.deadline = :today";
            $params['today'] = $today;
        } elseif ($filter === 'overdue') {
            $whereClauses[] = "projects.deadline < :today";
            $params['today'] = $today;
        } elseif ($filter === 'upcoming') {
            $whereClauses[] = "projects.deadline > :today";
            $params['today'] = $today;
        }

        if (!empty($search)) {
            $whereClauses[] = "projects.name LIKE :search";
            $params['search'] = '%' . $search . '%';
        }
    
        $whereSQL = '';
        if (count($whereClauses) > 0) {
            $whereSQL = 'WHERE ' . implode(' AND ', $whereClauses);
        }

        $projects = $this->projectRepository->fetchAllProjects($whereSQL, $params);
 
        $this->render("projects.view",[
            "projects" => $projects,
            "filter" => $filter
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