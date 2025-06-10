<?php

namespace App\Controllers;

use App\Repository\ProjectNotesRepository;
use App\Repository\ProjectRepository;
use App\Support\SessionService;

class ProjectNotesController extends AbstractController {

    public function __construct(protected ProjectRepository $projectRepository, protected ProjectNotesRepository $projectNotesRepository) {}
    

    public function createProjectNote($request) {
        $project_id = $_GET["projectId"] ?? "";
        $currentNavTab = $_GET["currentNavTab"] ?? "projectNotes";
        $project = $this->projectRepository->fetchProject($project_id);
        $baseUrl = [
            "page" => "projectPanel",
            "projectId" => $project_id
        ];

        $currentNavTab = $_GET["currentNavTab"] ?? "projectNotes";
        $data = [];

        if ($currentNavTab === "projectNotes") {
            $data["projectNotes"] = $this->projectRepository->fetchProjectNotes($project_id);
        }

        $errors = [];

        $content = trim(sanitize($request["post"]["projectNote"])) ?? "";
        $currentUserSession = SessionService::getSessionKey("user");
        $projectNoteType = "Added a note";
        
        
        if (empty($content)) {
            $errors["projectnoteErr"] = "Please enter your project note";

            $this->render("project.view", [
                "errors" => $errors,
                "project" => $project,
                "baseUrl" => $baseUrl,
                "currentNavTab" => $currentNavTab,
                "data" => $data
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

        
        $data["projectNotes"] = $this->projectRepository->fetchProjectNotes($project_id);
        
        $this->render("project.view", [
            "project" => $project,
            "baseUrl" => $baseUrl,
            "currentNavTab" => $currentNavTab,
            "data" => $data
        ]);
    }
}