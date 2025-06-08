<?php

namespace App\Controllers;

use App\Repository\ProjectRepository;
use App\Repository\UserRepository;
use App\Support\SessionService;
use App\Support\Validation;

use DateTime;

class AdminController extends AbstractController {

    public function __construct(protected UserRepository $userRepository, protected ProjectRepository $projectRepository){}

    public function handleCreateProject($request) {
        $listOfProjectManagers = $this->userRepository->fetchAllActiveUser("project_manager");
        $listOfMembers = $this->userRepository->fetchAllActiveUser("member");

        $projectName = trim(sanitize($request["post"]["projectName"])) ?? "";
        $projectDescription = trim(sanitize($request["post"]["projectDescription"])) ?? "";
        $projectDeadline = $request["post"]["projectDeadline"] ?? "";
        $assignedProjectManager = $request["post"]["assignedProjectManager"] ?? "";
        $assignedMembers = $request["post"]["assignedMembers"] ?? "";
        $projectNote = trim(sanitize($request["post"]["projectNote"])) ?? "";

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

        if (empty($assignedProjectManager)) {
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
            ]);
            exit;
        }

        $formData = [

        ];

        $this->projectRepository->handleCreateProject();
    }

    public function handleCreateAccount($request) {
        $fullName = trim(sanitize($request["post"]["fullName"])) ?? "";
        $username = trim(sanitize($request["post"]["username"])) ?? "";
        $password = trim($request["post"]["password"]) ?? "";
        $confirmPassword = trim($request["post"]["confirmPassword"]) ?? "";
        $role = $request["post"]["role"] ?? "";

        $errors = [];
        
        if (empty($fullName)) {
            $errors["fullNameErr"] = "Please enter the full name";
        }

        if (empty($username)) {
            $errors["usernameErr"] = "Please enter the username";
        }

        if (!Validation::string($password, 6, 50)) {
            $errors["passwordErr"] = "Please password must at least 6 character long";
        }

        if (!Validation::string($confirmPassword, 6, 50)) {
            $errors["confirmPasswordErr"] = "Please confirm password must at least 6 character long";
        }

        if (!Validation::match($password, $confirmPassword)) {
            $errors["passwordErr"] = "Password not match";
            $errors["confirmPasswordErr"] = "Password Confirm not match";
        }

        if (empty($role)) {
            $errors["roleErr"] = "Please choose the user role";
        }

    
        if (!empty($errors)) {
            $this->render("createAccount.view", [
                "errors" => $errors
            ]);
            exit;
        }

        $user = $this->userRepository->findByUsername($username);

        if (!empty($user)) {
            $errors["usernameErr"] = "Username is already taken";
            $this->render("createAccount.view", [
                "errors" => $errors
            ]);
            exit;
        }

        $formData = [
            "fullName" => $fullName,
            "username" => $username,
            "role" => $role,
            "password" => password_hash($password, PASSWORD_DEFAULT),
            "status" => "active"
        ];
        
        
        $success = $this->userRepository->createAccount($formData);

        if ($success) {
             SessionService::setAlertMessage("success_message", "Created account sucessully");
        }
        else {
             SessionService::setAlertMessage("success_message", "Account creation failed");
        }


         header("Location: index.php?page=home");
         
    }
}