<?php

namespace App\Controllers;

use App\Controllers\AbstractController;
use App\Repository\TaskNotesRepository;
use App\Repository\TaskRepository;
use App\Support\ProjectPanelService;
use App\Support\SessionService;

use DateTime;

class TaskController extends AbstractController{

    public function __construct(private ProjectPanelService $projectPanelService, protected TaskRepository $taskRepository, 
    protected TaskNotesRepository $taskNotesRepository) {}

    public function editTask($request) {
        $task = json_decode($request["post"]["task"] ?? "", true);
        $newTaskName = trim(sanitize($request["post"]["taskName"])) ?? "";
        $newTaskDescription = trim(sanitize($request["post"]["taskDescription"])) ?? "";
        $newTaskDeadline = $request["post"]["taskDeadline"] ?? "";

        $errors = [];
        if (empty($newTaskName)) {
            $errors["taskNameErr"] = "Please enter the task name";
        }

        if (empty($newTaskDescription)) {
            $errors["taskDescriptionErr"] = "Please enter the task description";
        }

        if (empty($newTaskDeadline)) {
            $errors["taskDeadlineErr"] = "Please enter the task deadline";
        }

        if (!empty($errors)) {
           $this->render("editTaskForm.view", [
                "task" => $task,
                "errors" => $errors,
                "newTaskName" => $newTaskName,
                "newTaskDescription" => $newTaskDescription,
                "newTaskDeadline" => $newTaskDeadline
            ]);
            exit;
        }

        $hasChanged = false;
        $fieldsToUpdate = [];
        $params = [];

        if ($newTaskName !== $task["taskname"]) {
            $hasChanged = true;
            $fieldsToUpdate[] = "taskname = :taskname";
            $params[":taskname"] = $newTaskName; 
        }
        if ($newTaskDescription !== $task["task_description"]) {
            $hasChanged = true;
            $fieldsToUpdate[] = "task_description = :task_description";
            $params[":task_description"] = $newTaskDescription;
        }
        if ($newTaskDeadline !== $task["deadline"]) {
            $hasChanged = true;
            $fieldsToUpdate[] = "deadline = :deadline";
            $params[":deadline"] =  $newTaskDeadline;
        }

        if ($hasChanged && empty($errors)) {
            $sql = "UPDATE tasks SET " . implode(", ", $fieldsToUpdate) . " WHERE id = :id";
            $params[":id"] = $task["id"];
            $success = $this->taskRepository->handleUpdateTaskInfo($sql, $params);

            if ($success) {
                SessionService::setAlertMessage("success_message", "Edited task successfully");
            }
            else {
                SessionService::setAlertMessage("error_message", "Edited task failed");
            }

            header("Location: index.php?page=tasks");
        }
        else {
            SessionService::setAlertMessage("error_message", "The task information is already up to date");
              $this->render("editTaskForm.view", [
                "task" => $task,
                "errors" => $errors,
                "newTaskName" => $newTaskName,
                "newTaskDescription" => $newTaskDescription,
                "newTaskDeadline" => $newTaskDeadline
            ]);
            exit;
        }
    }


    public function deleteTask($request) {
        $taskId = (int) $request["post"]["taskId"] ?? "";
        
        $success = $this->taskRepository->handleDeleteTask($taskId);

        if ($success) {
             SessionService::setAlertMessage("success_message", "Deleted task sucessully");
        }
        else {
             SessionService::setAlertMessage("error_message", "Failed to delete task");
        }     

        header("Location: index.php?page=tasks");
        exit;
    }

    public function approveTask($request) {
        $approveTaskNote = trim(sanitize($request["post"]["approvedTaskNote"] ?? ""));
        $taskId = $request["post"]["taskId"];

        $projectId = $request["get"]["projectId"];
        $currentPaginationPage = $request["get"]["currentPaginationPage"];
        $currentNavTab = $request["get"]["currentNavTab"];

        $success = $this->taskRepository->handleUpdateApprovalStatus([
            "approvalAction" => "approved",
            "taskId" => $taskId,
        ]);

        if ($success) {
            $this->taskNotesRepository->handleCreateTaskNote([
                "taskId" => $taskId,
                "userId" => SessionService::getSessionKey("user")["userId"],
                "content" => $approveTaskNote,
                "taskNoteType" => "Approved the task"
            ]);
        }

        if ($success) {
             SessionService::setAlertMessage("success_message", "Approved task sucessully");
        }
        else {
             SessionService::setAlertMessage("error_message", "Failed to approved task");
        }     

        $redirectUrl = BASE_URL . "/index.php?" . http_build_query([
            "page" => "projectPanel",
            "projectId" => $projectId,
            "currentNavTab" => $currentNavTab,
            "currentPaginationPage" => $currentPaginationPage
        ]);

        header("Location: $redirectUrl");
        exit;
    }

    public function rejectTask($request) {
        $rejectTaskNote = $request["post"]["rejectTaskNote"];
        $taskId = $request["post"]["taskId"];

        $projectId = $request["get"]["projectId"];
        $currentPaginationPage = $request["get"]["currentPaginationPage"];
        $currentNavTab = $request["get"]["currentNavTab"];

        $success = $this->taskRepository->handleUpdateApprovalStatus([
            "approvalAction" => "rejected",
            "taskId" => $taskId,
        ]);

        if ($success) {
            $this->taskNotesRepository->handleCreateTaskNote([
                "taskId" => $taskId,
                "userId" => SessionService::getSessionKey("user")["userId"],
                "content" => $rejectTaskNote,
                "taskNoteType" => "Rejected the task"
            ]);
        }

        if ($success) {
             SessionService::setAlertMessage("success_message", "Rejected task sucessully");
        }
        else {
             SessionService::setAlertMessage("error_message", "Failed to rejected task");
        }     

        $redirectUrl = BASE_URL . "/index.php?" . http_build_query([
            "page" => "projectPanel",
            "projectId" => $projectId,
            "currentNavTab" => $currentNavTab,
            "currentPaginationPage" => $currentPaginationPage
        ]);

        header("Location: $redirectUrl");
        exit;
    }




    public function editTaskStatus($request) {
        $taskId = (int) $request["post"]["taskId"] ?? "";
        $newTaskStatus = $request["post"]["newTaskStatus"] ?? "";
        $previousTaskStatus = $request["post"]["previousTaskStatus"] ?? "";
        $tasknote = sanitize(trim($request["post"]["taskStatusNote"] ?? ""));
        $currentPaginationPage = (int) ($request["get"]["currentPaginationPage"] ?? 1);

        $success = $this->taskRepository->handleEditTaskStatus($taskId, $newTaskStatus);

        if ($success) {
            $formData = [
                "taskId" => $taskId,
                "userId" => SessionService::getSessionKey("user")["userId"],
                "content" => "[ Change status from $previousTaskStatus to $newTaskStatus ] " . $tasknote,
                "taskNoteType" => "Update task status",
            ];

            $this->taskNotesRepository->handleCreateTaskNote($formData);
        }
        
        $redirectUrl = BASE_URL . "/index.php?" . http_build_query([
            "page" => "taskPanel",
            "taskId" => $taskId,
            "currentPaginationPage" => $currentPaginationPage
        ]);

        header("Location: $redirectUrl");
        exit;
    }


    public function createTask($request) {
        $projectId = (int) ($request["get"]["projectId"] ?? 0);
        $currentNavTab = $request["get"]["currentNavTab"] ?? "projectNotes";
        $currentPaginationPage = (int) ($request["get"]["currentPaginationPage"] ?? 1);

        $taskName = trim(sanitize($request["post"]["taskName"])) ?? "";
        $taskDescription = trim(sanitize($request["post"]["taskDescription"])) ?? "";
        $taskDeadline = trim(sanitize($request["post"]["taskDeadline"])) ?? "";
        $taskType = $request["post"]["taskType"] ?? "";
        $assignedMembers = $request["post"]["assignedMembers"] ?? [];
        $taskNote = trim(sanitize($request["post"]["taskNote"])) ?? "";

        $errors = [];
       
        if (empty($taskName)) {
            $errors["taskNameErr"] = "Please enter the task name";
        }

        if (empty($taskDescription)) {
            $errors["taskDescriptionErr"] = "Please enter the task description";
        }

        if (empty($taskDeadline)) {
            $errors["taskDeadlineErr"] = "Please enter the task deadline";
        }

        //temporary disabled for checking
        // if (!empty($taskDeadline)) {
        //     $deadlineDate = new DateTime($taskDeadline); 
        //     $today = new DateTime();                        
            
        //     $deadlineStr = $deadlineDate->format('Y-m-d');
        //     $todayStr = $today->format('Y-m-d');

        //     if ($deadlineStr <= $todayStr) {
        //         $errors["taskDeadlineErr"] = "Date cannot be set on the previous day or today.";
        //     }
        // }

        if (empty($taskType)) {
            $errors["taskTypeErr"] = "Please select a task type";
        }

        if (empty($assignedMembers)) {
            $errors["assignedMembersErr"] = "Please assign a members";
        }

        if (empty($taskNote)) {
            $errors["taskNoteErr"] = "Please enter the task note";
        }

        if ($taskType === "solo" && count($assignedMembers) > 1 ) {
            $errors["taskTypeErr"] = "Solo Task can only be assigned to one member";
        } 

        if ($taskType === "group" && count($assignedMembers) < 2) {
            $errors["taskTypeErr"] = "Group Task must be assigned to at least 2 members";
        }

  
        if (!empty($errors)) {
            $projectPanel = $this->projectPanelService->buildProjectPanel($projectId, $currentNavTab, $currentPaginationPage, $request);
        
            $this->render("project.view", array_merge($projectPanel, [
                 "errors" => $errors,
                 "previousInput" => $request["post"],
                 "currentUserSession" => SessionService::getSessionKey("user") ?? ""
            ]));
            exit;
        }

        $formData = [
            "taskName" => $taskName,
            "taskDescription" => $taskDescription,
            "taskDeadline" => $taskDeadline,
            "taskType" => $taskType,
            "assignedMembers" => $assignedMembers,
            "taskNote" => $taskNote
        ];

        $taskMeta = [
            "projectId" => $projectId,
            "isSubmitted" => 0,
            "status" => "pending",
            "approvalStatus" => NULL,
            "taskNoteType" => "Created a task",
            "taskCreatorId" => SessionService::getSessionKey("user")["userId"]
        ];

        $newTaskData = array_merge($formData, $taskMeta);


        $success = $this->taskRepository->handleCreateTask($newTaskData);
 
        if ($success) {
             SessionService::setAlertMessage("success_message", "Created task sucessully");
        }
        else {
             SessionService::setAlertMessage("error_message", "Failed to create task");
        }

        $redirectUrl = BASE_URL . "/index.php?" . http_build_query([
            "page" => "projectPanel",
            "projectId" => $projectId,
            "currentNavTab" => $currentNavTab
        ]);

        header("Location: $redirectUrl");
        exit;
    }

}