<?php

namespace App\Controllers;

use App\Controllers\ProjectNotesController;
use App\Controllers\TaskController;
use App\Models\ProjectNotes;
use App\Repository\ProjectNotesRepository;
use App\Repository\ProjectRepository;
use App\Repository\TaskNotesRepository;
use App\Repository\TaskRepository;
use App\Support\SessionService;
use App\Repository\UserRepository;
use App\Support\PaginateService;
use App\Support\ProjectPanelService;

class PageController extends AbstractController {
    protected UserRepository $userRepository;
    protected ProjectRepository $projectRepository;
    protected TaskRepository $taskRepository;
    protected ProjectNotesRepository $projectNotesRepository;
    protected ProjectNotesController $projectNotesController;
    protected TaskController $taskController;
    protected ProjectPanelService $projectPanelService;
    protected TaskNotesRepository $taskNotesRepository;

    protected array|null $currentUserSession;

    public function __construct(UserRepository $userRepository, ProjectRepository $projectRepository, 
                                ProjectNotesRepository $projectNotesRepository, ProjectNotesController $projectNotesController,
                                TaskController $taskController, ProjectPanelService $projectPanelService, TaskRepository $taskRepository,
                                TaskNotesRepository $taskNotesRepository){
         $this->userRepository = $userRepository;
         $this->projectRepository = $projectRepository;
         $this->taskRepository = $taskRepository;
         $this->projectNotesRepository = $projectNotesRepository;
         $this->currentUserSession = SessionService::getSessionKey("user") ?? null;
         $this->projectNotesController = $projectNotesController;
         $this->taskController = $taskController;
         $this->projectPanelService = $projectPanelService;
         $this->taskNotesRepository = $taskNotesRepository;
    } 

    public function showError404Page() {
        $this->render("error404.view", [
        ]);
    }

    public function showEditTaskForm($request) {
        $taskId = (int) $request["get"]["taskId"] ?? "";
        $task = $this->taskRepository->fetchTask($taskId);
        $redirectUrl = $request["get"]["redirect"] ?? "index.php?page=tasks";
       
        $this->render("editTaskForm.view", [
            "task" => $task,
            "redirectUrl" => $redirectUrl,
        ]);
    }


    public function showEditProjectForm($request) {
        $projectId = $request["get"]["projectId"] ?? "";
        $project = $this->projectRepository->fetchProjectDetail($projectId)[0];

        $this->render("projectEditForm.view", [
             "project" => $project,
        ]);
    }

    public function showUpdateAccountInfoForm($request) {
        $userId = $request["get"]["userId"] ?? "";

        $userAccount = $this->userRepository->fetchUserProfileById($userId);

        $this->render("accountUpdateForm.view", [
            "userAccount" => $userAccount
        ]);
    }

    public function showTasks($request) {
        $filter = $request["get"]["filter"] ?? 'all';
        $search = $request["get"]["search"] ?? "";
        $currentPaginationPage = (int) ($request["get"]["currentPaginationPage"] ?? 1);

        $totalTasks = $this->taskRepository->countAllTasks($filter, $search, $this->currentUserSession["role"],  $this->currentUserSession["userId"]);
        $paginationMeta = PaginateService::paginate($totalTasks, $currentPaginationPage, 10);
        $tasks = $this->taskRepository->fetchTasksWithDetails(
                        $filter, $search, $this->currentUserSession["role"],  $this->currentUserSession["userId"], 
                        $paginationMeta["limit"], $paginationMeta["offset"]);

        $this->render("tasks.view", [
            "tasks" => $tasks,
            "filter" => $filter,
            "search" => $search,
            "paginationMeta" => $paginationMeta,
            "currentPaginationPage" => $currentPaginationPage,
            "totalTasks" => $totalTasks,
        ]);
    }

    public function showMemberProjects($request) {
        $filter = $_GET["filter"] ?? "";
        $userId = (int) $request["get"]["userId"];
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


        $user = $this->userRepository->fetchUserProfileById($userId);

        $projects = [];
        // get all the projects by a member
        if ($user["role"] === "member") {
            $projects = $this->projectRepository->fetchProjectsForMember($userId, $whereSQL, $params);
        }
        if ($user["role"] === "project_manager") {
            $projects = $this->projectRepository->fetchProjects($userId, $whereSQL, $params);
        }

        //If the user is not an admin, filter only the projects managed by the project manager
        if ($this->currentUserSession["role"] !== "admin") {
            $projects = array_filter($projects, function ($project) {
                 return (int) $project['manager_id'] === (int) $this->currentUserSession["userId"];
            });
        } 

        $headerTitle = "";
        if ($this->currentUserSession["role"] === "admin" && $filter !== "project_manager" ) {
            $headerTitle = "Projects with {$user['fullname']}";
        }
        if ($this->currentUserSession["role"] === "project_manager" ) {
            $headerTitle = "Projects of {$user['fullname']}";
        }
           
        $this->render("memberProjects.view", [
            "projects" => $projects,
            "headerTitle" => $headerTitle,
            "filter" => $filter,
            "userId" => $userId,
        ]);
    }

    public function showAccounts($request) {
        $filter = $request["get"]["filter"] ?? "all";
        $search = $request["get"]["search"] ?? "";
        $currentPaginationPage = (int) ($request["get"]["currentPaginationPage"] ?? 1);

        $totalAccounts = $this->userRepository->countAllUsers($filter, $search, $this->currentUserSession["role"], $this->currentUserSession["userId"]);
        $paginationMeta =  PaginateService::paginate($totalAccounts, $currentPaginationPage, 10);

        $userTaskSummary = $this->taskRepository->fetchUsersTasks(
                     $paginationMeta["limit"], $paginationMeta["offset"], $filter, $search, 
                     $this->currentUserSession["role"], $this->currentUserSession["userId"]);
      
        $this->render("members.view", [
            "userTaskSummary" => $userTaskSummary,
            "totalAccountsByFilter" => $totalAccounts,
            "filter" => $filter,
            "search" => $search,
            "currentPaginationPage" => $currentPaginationPage,
            "paginationMeta" => $paginationMeta,
            "currentUserSession" => $this->currentUserSession,
        ]);
    }

    public function showMemberProfilePanel($request) {
        $memberId = (int) $request["get"]["memberId"] ?? "";
        $projectId = (int) $request["get"]["projectId"] ?? "";
        $filter = $request["get"]["filter"] ?? "all";
        $search = trim($request["get"]["searchTask"] ?? "");
        $memberProfile = $this->userRepository->fetchUserProfileById($memberId);
        $memberTasks = $this->taskRepository->fetchUserTasks($memberId, $filter, $projectId, $search);  

        $this->render("memberProfilePanel.view", [
            "memberProfile" => $memberProfile,
            "filter" => $filter,
            "search" => $search,
            "memberTasks" => $memberTasks,
            "projectId" => $projectId
        ]);
    }

    public function showMemberAssignedGroupTask($request) {
        $filter = $request["get"]["filter"] ?? "all";
        $search = trim(sanitize($request["get"]["search"] ?? ""));

        $tasks = $this->taskRepository->fetchMemberAssignedTasks(
             $this->currentUserSession["userId"],  "group", ["filter" => $filter, "search" => $search]
        );

        $this->render("myGroupTasks.view", [
            "tasks" => $tasks,
            "filter" => $filter 
        ]);
    }

    public function showMemberAssignedSoloTask($request) {
        $filter = $request["get"]["filter"] ?? "all";
        $search = trim(sanitize($request["get"]["search"] ?? ""));

        $tasks = $this->taskRepository->fetchMemberAssignedTasks(
             $this->currentUserSession["userId"],  "solo" , ["filter" => $filter, "search" => $search]
        );

        $this->render("mySoloTasks.view", [
            "tasks" => $tasks,
            "filter" => $filter 
        ]);
    }


    public function showTask($request) {
        $taskId = $request["get"]["taskId"] ?? "";
        $task = $this->taskRepository->fetchTaskByProjectId($taskId);
        $taskParticipants = $this->taskRepository->fetchProjectManagerAndMembersByTaskId($taskId);
        $currentPaginationPage = (int) ($request["get"]["currentPaginationPage"] ?? 1);

        $totalTaskNotes = $this->taskNotesRepository->countAllTaskNote($taskId);
        $paginationMeta = PaginateService::paginate($totalTaskNotes, $currentPaginationPage);
        $taskNotes = $this->taskNotesRepository->fetchTaskNote($taskId, $paginationMeta["limit"], $paginationMeta["offset"]);

        $task["participants"] = $taskParticipants;
        $task["task_notes"] = $taskNotes;
       
         $this->render("task.view", [
            "task" => $task,
            "currentUserSession" => $this->currentUserSession,
            "currentPaginationPage" => $currentPaginationPage,
            "paginationMeta" => $paginationMeta     
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
            "filter" => $filter,
            "currentUserSession" => $this->currentUserSession
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