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
    

    public function createTaskNote($request) {
        $taskId = (int) $request["post"]["taskId"] ?? "";
        $userId = (int) $request["post"]["userId"] ?? "";
        $content = sanitize(trim($request["post"]["content"])) ?? "";


        require_once __DIR__ . "/../views/components/modal.view.php";
        $modalHtml = renderModal([
            "id" => "editTaskNoteModal",
            "title" => "Edit task note",
            "action" => BASE_URL . "/index.php?page=editTaskNote",
            "textareaLabel" => "Edit your note",
            "textareaName" => "editTaskNoteTextArea",
            "submitText" => "Save Task",
        ]);


        $errors = [];
        if (empty($content)) {
            $errors["taskNoteFormErr"] = "Please enter your task note";
            $task = $this->taskRepository->fetchTaskByProjectId($taskId);

            $this->render("task.view", [
                "errors" => $errors,
                "currentUserSession" => $this->currentUserSession,
                "task" => $task,
                "modalHtml" => $modalHtml
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
        ]);

        header("Location: $redirectUrl");
        exit;
    }
}