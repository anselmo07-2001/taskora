<?php

namespace App\Controllers;

use App\Repository\ProjectNotesRepository;
use App\Repository\ProjectRepository;
use App\Support\PaginateService;
use App\Support\SessionService;

class ProjectNotesController extends AbstractController {

    public function __construct(protected ProjectRepository $projectRepository, protected ProjectNotesRepository $projectNotesRepository) {}
    
    public function fetchProjectNotes($projectId) {
        $projectId = (int) $projectId;
        $currentPaginationPage = isset($_GET['currentPaginationPage']) ? (int) $_GET['currentPaginationPage'] : 1;
        $totalItems = $this->projectNotesRepository->countAllProjectNotes($projectId);
        $paginationMeta = PaginateService::paginate($totalItems, $currentPaginationPage);
        
        $projectNotes = $this->projectNotesRepository->fetchProjectNotes($projectId, $paginationMeta["limit"], $paginationMeta["offset"]);

        return [
            "projectNotes" => $projectNotes,
            "paginationMeta" => $paginationMeta,
        ];    
    }



    public function deleteProjectNote($request) {
        $project_id = $request["get"]["projectId"] ?? "";
        $currentNavTab = $request["get"]["currentNavTab"] ?? "projectNotes";
        $projectNoteId = $request["post"]["projectNoteId"];
    
        $success = $this->projectNotesRepository->handleDeleteProjectNote([
           "id" => $projectNoteId
        ]);

        if ($success) {
             SessionService::setAlertMessage("success_message", "Deleted project note sucessully");
        }
        else {
             SessionService::setAlertMessage("error_message", "Failed to deleted project note");
        }

        $redirectUrl = BASE_URL . "/index.php?" . http_build_query([
            "page" => "projectPanel",
            "projectId" => $project_id,
            "currentNavTab" => $currentNavTab
        ]);

        header("Location: $redirectUrl");
        exit;
    }

    public function updateProjectNote($request) {
        $project_id = $request["get"]["projectId"] ?? "";
        $currentNavTab = $request["get"]["currentNavTab"] ?? "projectNotes";
        $newContent = $request["post"]["updateValueNote"] ?? "";
        $projectNoteId = (int) $request["post"]["projectNoteId"] ?? "";
        $addedProjectNoteStatus = $request["post"]["addedProjectNoteStatus"] ?? "";
        
        $currentPaginationPage = (int) $request["get"]["currentPaginationPage"] ?? 1;

        $projectNote = $this->projectNotesRepository->fetchProjectNote($projectNoteId);

      
        if ($projectNote->projectnote_type === "Update project status") {
            $newContent = $addedProjectNoteStatus . " " . $newContent;
        }

        
        $success = $this->projectNotesRepository->handleUpdateProjectNote([
            "content" => $newContent,
            "id" => $projectNoteId
        ]);

        if ($success) {
             SessionService::setAlertMessage("success_message", "Updated project note sucessully");
        }
        else {
             SessionService::setAlertMessage("error_message", "Failed to update project note");
        }

        $redirectUrl = BASE_URL . "/index.php?" . http_build_query([
            "page" => "projectPanel",
            "projectId" => $project_id,
            "currentNavTab" => $currentNavTab,
            "currentPaginationPage" => $currentPaginationPage
        ]);

        header("Location: $redirectUrl");
        exit;
    }

    public function createProjectNote($request) {
        $project_id = $_GET["projectId"] ?? "";
        $currentNavTab = $_GET["currentNavTab"] ?? "projectNotes";
        $project = $this->projectRepository->fetchProject($project_id);
        $currentNavTab = $_GET["currentNavTab"] ?? "projectNotes"; 
        $currentPaginationPage = isset($_GET['currentPaginationPage']) ? (int) $_GET['currentPaginationPage'] : 1;
        

        $baseUrl = [
            "page" => "projectPanel",
            "projectId" => $project_id,
            "currentPaginationPage" => $currentPaginationPage,
        ];
   
     
        $content = trim(sanitize($request["post"]["projectNote"])) ?? "";
        $currentUserSession = SessionService::getSessionKey("user");
        $projectNoteType = "Added a note";

        //For Pagination
        $totalItems = $this->projectNotesRepository->countAllProjectNotes($project_id);
        $tabData = [];
        $tabData["paginationMeta"] = PaginateService::paginate($totalItems, $currentPaginationPage);
        $tabData["projectNotes"] = $this->projectNotesRepository->fetchProjectNotes($project_id, $tabData["paginationMeta"]["limit"],  $tabData["paginationMeta"]["offset"]);
        
    
        $errors = [];   
        if (empty($content)) {
            $errors["projectnoteErr"] = "Please enter your project note";

            $this->render("project.view", [
                "errors" => $errors,
                "project" => $project,
                "baseUrl" => $baseUrl,
                "currentNavTab" => $currentNavTab,      
                "tabData" => $tabData,
                "currentUserSession" => $currentUserSession,
            ]);
            exit;
        }

        $success =$this->projectNotesRepository->handleCreateProjectNote([
            "project_id" => $project_id,
            "user_id" => $currentUserSession["userId"],
            "content" => $content,
            "projectnote_type" => $projectNoteType
        ]);


        if ($success) {
             SessionService::setAlertMessage("success_message", "Created project note sucessully");
        }
        else {
             SessionService::setAlertMessage("error_message", "Failed to create project note");
        }

        
        $redirectUrl = BASE_URL . "/index.php?" . http_build_query([
            "page" => "projectPanel",
            "projectId" => $project_id,
            "currentNavTab" => $currentNavTab
        ]);

        header("Location: $redirectUrl");
        exit;
    }

}