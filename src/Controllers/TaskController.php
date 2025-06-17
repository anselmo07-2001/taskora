<?php

namespace App\Controllers;

use App\Controllers\AbstractController;
use App\Repository\TaskRepository;
use App\Support\ProjectPanelService;
use App\Support\SessionService;

use DateTime;

class TaskController extends AbstractController{

    public function __construct(private ProjectPanelService $projectPanelService, protected TaskRepository $taskRepository) {}
    

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