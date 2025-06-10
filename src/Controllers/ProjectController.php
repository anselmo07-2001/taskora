<?php

namespace App\Controllers;

use App\Repository\ProjectRepository;
use App\Repository\UserRepository;
use App\Support\SessionService;

use DateTime;

class ProjectController extends AbstractController {
    public function __construct(protected UserRepository $userRepository, protected ProjectRepository $projectRepository){}

    public function createProject($request) {
        // this lists use for the dropdown 
        $listOfProjectManagers = $this->userRepository->fetchAllActiveUser("project_manager");
        $listOfMembers = $this->userRepository->fetchAllActiveUser("member");
       
        $projectName = trim(sanitize($request["post"]["projectName"])) ?? "";
        $projectDescription = trim(sanitize($request["post"]["projectDescription"])) ?? "";
        $projectDeadline = $request["post"]["projectDeadline"] ?? "";
        $assignedProjectManager = $request["post"]["assignedProjectManager"] ?? "";
        $assignedMembers = $request["post"]["assignedMembers"] ?? "";
        $projectNote = trim(sanitize($request["post"]["projectNote"])) ?? "";

        $currentUserSession = SessionService::getSessionKey("user");

        $errors = [];

        if (empty($projectName)) {
            $errors["projectNameErr"] = "Please enter the Project name";
        }

        if (empty($projectDescription)) {
            $errors["projectDescriptionErr"] = "Please enter the Project description";
        }

        if (empty($projectDeadline)) {
            $errors["projectDeadlineErr"] = "Please enter the Project deadline";
        }

        if (!empty($projectDeadline)) {
            $deadlineDate = new DateTime($projectDeadline); 
            $today = new DateTime();                        
            
            $deadlineStr = $deadlineDate->format('Y-m-d');
            $todayStr = $today->format('Y-m-d');

            if ($deadlineStr <= $todayStr) {
                $errors["projectDeadlineErr"] = "Date cannot be set on the previous day or today.";
            }
        }

        if (empty($assignedProjectManager) && $currentUserSession["role"] === "admin") {
            $errors["assignedProjectManagerErr"] = "Please select Project manager";
        }

        if (empty($assignedMembers)) {
            $errors["assignedMembersErr"] = "Please select members";
        }

        if (empty($projectNote)) {
            $errors["projectNoteErr"] = "Please enter the Project deadline";
        }

        
        if (!empty($errors)) {
            $this->render("createProject.view", [
                "errors" => $errors,
                "projectManagers" => $listOfProjectManagers,
                "members" => $listOfMembers,
                "currentUserSession" => $currentUserSession,
            ]);
            exit;
        }

        $formData = [
            "projectName" => $projectName,
            "projectDescription" => $projectDescription,
            "projectDeadline" => $projectDeadline,
            "projectStatus" => "pending",
            "isSuspended" => 0,
            "assignedProjectManager" => $assignedProjectManager,
            "assignedMembers" => $assignedMembers,
            "projectNote" => $projectNote,
            "projectNoteType" => "Created a project"
        ];

        $success = $this->projectRepository->handleCreateProject($formData);

        if ($success) {
             SessionService::setAlertMessage("success_message", "Created project sucessully");
        }
        else {
             SessionService::setAlertMessage("success_message", "Project creation failed");
        }


        header("Location: index.php?page=home");   
    }
}