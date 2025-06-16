<?php

namespace App\Controllers;

use App\Controllers\AbstractController;
use App\Support\ProjectPanelService;
use App\Support\SessionService;

class TaskController extends AbstractController{

    public function __construct(private ProjectPanelService $projectPanelService) {}
    

    public function createTask($request) {
        $projectId = (int) ($request["get"]["projectId"] ?? 0);
        $currentNavTab = $request["get"]["currentNavTab"] ?? "projectNotes";
        $currentPaginationPage = (int) ($request["get"]["currentPaginationPage"] ?? 1);

        $taskName = trim(sanitize($request["post"]["taskname"])) ?? "";
        $taskDescription = trim(sanitize($request["post"]["taskDescription"])) ?? "";
        $taskDeadline = trim(sanitize($request["post"]["taskDeadline"])) ?? "";
        $taskType = $request["post"]["taskType"] ?? "";
        $assignedMembers = $request["post"]["assignedMembers"] ?? "";
        $taskNote = trim(sanitize($request["post"]["taskNote"])) ?? "";

        $errors = [];
        // var_dump($taskType);
        // die();
       
        if (empty($taskName)) {
            $errors["taskNameErr"] = "Please enter the task name";
        }

        if (empty($taskDescription)) {
            $errors["taskDescriptionErr"] = "Please enter the task description";
        }

        if (empty($taskDeadline)) {
            $errors["taskDeadlineErr"] = "Please enter the task deadline";
        }

        if (empty($taskType)) {
            $errors["taskTypeErr"] = "Please select a task type";
        }

        if (empty($assignedMembers)) {
            $errors["assignedMembersErr"] = "Please assign a members";
        }

        if (empty($taskNote)) {
            $errors["taskNoteErr"] = "Please enter the task note";
        }

        
        if (!empty($errors)) {
            $projectPanel = $this->projectPanelService->buildProjectPanel($projectId, $currentNavTab, $currentPaginationPage);
        
            $this->render("project.view", array_merge($projectPanel, [
                 "errors" => $errors,
                 "previousInput" => $request["post"],
                 "currentUserSession" => SessionService::getSessionKey("user") ?? ""
            ]));
            exit;
        }
    }

}