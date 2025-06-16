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

        if (empty($taskType)) {
            $errors["taskTypeErr"] = "Please select a task type";
        }

        if (empty($assignedMembers)) {
            $errors["assignedMembersErr"] = "Please assign a members";
        }

        if (empty($taskNote)) {
            $errors["taskNoteErr"] = "Please enter the task note";
        }

        if ($taskType === "soloTask" && count($assignedMembers) > 1 ) {
            $errors["taskTypeErr"] = "Solo Task can only be assigned to one member";
        } 

        if ($taskType === "groupTask" && count($assignedMembers) < 2) {
            $errors["taskTypeErr"] = "Group Task must be assigned to at least 2 members";
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