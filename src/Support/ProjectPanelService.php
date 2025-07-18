<?php

namespace App\Support;

use App\Controllers\ProjectNotesController;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;

class ProjectPanelService {
    public function __construct(
        private ProjectRepository $projectRepository,
        private ProjectNotesController $projectNotesController,
        private TaskRepository $taskRepository,
    ) {}

    public function buildProjectPanel($projectId, $currentNavTab, $currentPaginationPage, $request): array {
        $project = $this->projectRepository->fetchProject($projectId);

        $baseUrl = [
            "page" => "projectPanel",
            "projectId" => $projectId,
            "currentPaginationPage" => $currentPaginationPage
        ];

        $tabData = [];

        $filters = [
                "filter" => $request["get"]["filter"] ?? null,
                "search" => $request["get"]["search"] ?? null,
        ];

        if ($currentNavTab === "projectNotes") {
            $paginationPayload = $this->projectNotesController->fetchProjectNotes($projectId);
            $tabData["projectNotes"] = $paginationPayload["projectNotes"];
            $tabData["paginationMeta"] = $paginationPayload["paginationMeta"];
        }

        if ($currentNavTab === "createTask") {
            $tabData["projectMembers"] = $this->projectRepository->fetchMembersInProject($projectId);
        }

        if ($currentNavTab === "assignedSoloTask") {
            $tabData["soloTask"] = $this->taskRepository->fetchProjectSoloTasks($projectId, $filters);
            $tabData["filter"] = $request["get"]["filter"] ?? "allSoloTask";
        }

        if ($currentNavTab === "assignedGroupTask") {
            $tabData["groupTask"] = $this->taskRepository->fetchProjectGroupTask($projectId, $filters);
            $tabData["filter"] = $request["get"]["filter"] ?? "allGroupTask";
        }

        if ($currentNavTab === "submittedTask") {
            $tabData["submittedTask"] = $this->taskRepository->fetchSubmittedTasks($projectId, $filters);
            $tabData["filter"] = $request["get"]["filter"] ?? "all";
        }

        if ($currentNavTab === "manageMembers") {
            $tabData["memberStats"] = $this->projectRepository->fetchMemberTaskSummary($projectId, $filters);
            $tabData["filter"] = $request["get"]["filter"] ?? "all";
        }

        return [
            "project" => $project,
            "baseUrl" => $baseUrl,
            "currentNavTab" => $currentNavTab,
            "tabData" => $tabData,
            "request" => $request,
        ];
    }
}