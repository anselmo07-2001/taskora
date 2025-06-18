<?php

namespace App\Controllers;

use App\Controllers\ProjectNotesController;
use App\Controllers\TaskController;
use App\Models\ProjectNotes;
use App\Repository\ProjectNotesRepository;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use App\Support\SessionService;
use App\Repository\UserRepository;
use App\Support\ProjectPanelService;

class PageController extends AbstractController {
    protected UserRepository $userRepository;
    protected ProjectRepository $projectRepository;
    protected TaskRepository $taskRepository;
    protected ProjectNotesRepository $projectNotesRepository;
    protected ProjectNotesController $projectNotesController;
    protected TaskController $taskController;
    protected ProjectPanelService $projectPanelService;

    protected array|null $currentUserSession;

    public function __construct(UserRepository $userRepository, ProjectRepository $projectRepository, 
                                ProjectNotesRepository $projectNotesRepository, ProjectNotesController $projectNotesController,
                                TaskController $taskController, ProjectPanelService $projectPanelService, TaskRepository $taskRepository){
         $this->userRepository = $userRepository;
         $this->projectRepository = $projectRepository;
         $this->taskRepository = $taskRepository;
         $this->projectNotesRepository = $projectNotesRepository;
         $this->currentUserSession = SessionService::getSessionKey("user") ?? null;
         $this->projectNotesController = $projectNotesController;
         $this->taskController = $taskController;
         $this->projectPanelService = $projectPanelService;
    } 


    public function showTask($request) {
        $taskId = $request["get"]["taskId"];
        $task = $this->taskRepository->fetchTaskByProjectId($taskId);

        $this->render("task.view", [
            "task" => $task,
            "currentUserSession" => $this->currentUserSession
        ]); 
    }
    

    public function showProject($request) {
        $projectId = (int) ($request["get"]["projectId"] ?? 0);
        $currentNavTab = $request["get"]["currentNavTab"] ?? "projectNotes";
        $currentPaginationPage = (int) ($request["get"]["currentPaginationPage"] ?? 1);

        $projectPanel = $this->projectPanelService->buildProjectPanel($projectId, $currentNavTab, $currentPaginationPage, $request);

        $this->render("project.view", array_merge($projectPanel, [
            "currentUserSession" => $this->currentUserSession
        ]));
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