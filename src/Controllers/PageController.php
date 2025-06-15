<?php

namespace App\Controllers;

use App\Controllers\ProjectNotesController;
use App\Models\ProjectNotes;
use App\Repository\ProjectNotesRepository;
use App\Repository\ProjectRepository;
use App\Support\SessionService;
use App\Repository\UserRepository;

class PageController extends AbstractController {
    protected UserRepository $userRepository;
    protected ProjectRepository $projectRepository;
    protected ProjectNotesRepository $projectNotesRepository;
    protected ProjectNotesController $projectNotesController;
    protected array|null $currentUserSession;

    public function __construct(UserRepository $userRepository, ProjectRepository $projectRepository, 
                                ProjectNotesRepository $projectNotesRepository, ProjectNotesController $projectNotesController){
         $this->userRepository = $userRepository;
         $this->projectRepository = $projectRepository;
         $this->projectNotesRepository = $projectNotesRepository;
         $this->currentUserSession = SessionService::getSessionKey("user") ?? null;
         $this->projectNotesController = $projectNotesController;
    }   

    public function showProject() {
        $project_id = $_GET["projectId"] ?? "";
        $project = $this->projectRepository->fetchProject($project_id);
        $currentNavTab = $_GET["currentNavTab"] ?? "projectNotes";
        $currentPaginationPage = $_GET["currentPaginationPage"] ?? 1;

        //use for the navbar
        $baseUrl = [
            "page" => "projectPanel",
            "projectId" => $project_id,
            "currentPaginationPage" => $currentPaginationPage
        ];
     
      
        $tabData = [];
        if ($currentNavTab === "projectNotes") {
            $paginationPayload = $this->projectNotesController->fetchProjectNotes($project_id);
            $tabData["projectNotes"] = $paginationPayload["projectNotes"];
            $tabData["paginationMeta"] = $paginationPayload["paginationMeta"];
        }
     
          
        $this->render("project.view", [
            "project" => $project,
            "baseUrl" => $baseUrl,
            "currentNavTab" => $currentNavTab,
            "tabData" => $tabData,
            "currentUserSession" => $this->currentUserSession
        ]);
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

        $projects = [];
        if ($this->currentUserSession["role"] === "admin") {
             $projects = $this->projectRepository->fetchProjects(null, $whereSQL, $params);
        }

        if ($this->currentUserSession["role"] === "project_manager") {
             $projects = $this->projectRepository->fetchProjects($this->currentUserSession["userId"], $whereSQL, $params);
        }

        if ($this->currentUserSession["role"] === "member") {
            $projects = $this->projectRepository->fetchProjectsForMember($this->currentUserSession["userId"], $whereSQL, $params);
        }
 
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
            "currentUserSession" => $this->currentUserSession
        ]);
    }

    public function showCreateAccountPage() {
        $this->render("createAccount.view", []);
    }

    public function showLoginPage() {
        $this->render("login.view", []);
    }
}