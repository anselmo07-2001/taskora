<?php

namespace App\Controllers;

use App\Repository\TaskNotesRepository;
use App\Repository\TaskRepository;
use App\Support\SessionService;

class TaskNotesController extends AbstractController {
    protected $taskNotesRepository;
    protected $taskRepository;
    protected $currentUserSession;

    public function __construct(TaskNotesRepository $taskNotesRepository, TaskRepository $taskRepository){
         $this->taskNotesRepository = $taskNotesRepository;
         $this->taskRepository = $taskRepository;
         $this->currentUserSession = SessionService::getSessionKey("user");
    }

    public function deleteTaskNote($request) {
        $taskId = (int) $request["post"]["taskId"] ?? "";
        $taskNoteId = (int) $request["post"]["taskNoteId"] ?? "";
        $success = $this->taskNotesRepository->handleDeleteTaskNote($taskNoteId);

        if ($success) {
             SessionService::setAlertMessage("success_message", "Deleted task note sucessully");
        }
        else {
             SessionService::setAlertMessage("error_message", "Failed to deleted task note");
        }

        $redirectUrl = BASE_URL . "/index.php?" . http_build_query([
            "page" => "taskPanel",
            "taskId" => $taskId,
            "currentUserSession" => $this->currentUserSession,
        ]);

        header("Location: $redirectUrl");
        exit;
    }

    public function editTaskNote(array $request) {
        $newContent = sanitize(trim($request["post"]["editTaskNoteTextArea"] ?? "")); 
        $taskNoteId = (int) $request["post"]["taskNoteId"] ?? "";
        $taskId = (int) $request["post"]["taskId"];
        $currentPaginationPage = (int) $request["get"]["currentPaginationPage"] ?? 1;
        $taskStatusChangeLog = $request["post"]["taskStatusChangeLog"];
        $newContent = $taskStatusChangeLog . " " . $newContent;
        
        $success = $this->taskNotesRepository->handleEditTaskNote($newContent, $taskNoteId);
        
        if ($success) {
             SessionService::setAlertMessage("success_message", "Edited task note sucessully");
        }
        else {
             SessionService::setAlertMessage("error_message", "Failed to edit task note");
        }

        $redirectUrl = BASE_URL . "/index.php?" . http_build_query([
            "page" => "taskPanel",
            "taskId" => $taskId,
            "currentUserSession" => $this->currentUserSession,
            "currentPaginationPage" => $currentPaginationPage
        ]);

        header("Location: $redirectUrl");
        exit;
    }
    

    public function createTaskNote($request) {
        $taskId = (int) $request["post"]["taskId"] ?? "";
        $userId = (int) $request["post"]["userId"] ?? "";
        $content = sanitize(trim($request["post"]["content"])) ?? "";
        $currentPaginationPage = (int) $request["get"]["currentPaginationPage"] ?? 1;

        $errors = [];
        if (empty($content)) {
            $errors["taskNoteFormErr"] = "Please enter your task note";
            $task = $this->taskRepository->fetchTaskByProjectId($taskId);

            $this->render("task.view", [
                "errors" => $errors,
                "currentUserSession" => $this->currentUserSession,
                "task" => $task,
                "currentPaginationPage" => $currentPaginationPage
            ]);
            exit;
        }

        $formData = [
            "taskId" => $taskId,
            "userId" => $userId,
            "content" => $content,
            "taskNoteType" => "Added a note",
        ];

        $success = $this->taskNotesRepository->handleCreateTaskNote($formData);

        if ($success) {
             SessionService::setAlertMessage("success_message", "Created task note sucessully");
        }
        else {
             SessionService::setAlertMessage("error_message", "Failed to create task note");
        }

        $redirectUrl = BASE_URL . "/index.php?" . http_build_query([
            "page" => "taskPanel",
            "taskId" => $taskId,
            "currentPaginationPage" => 1 //after creating the task note, set the pagination page to 1
        ]);

        header("Location: $redirectUrl");
        exit;
    }
}