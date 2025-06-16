<?php

namespace App\Support;

use App\Controllers\ProjectNotesController;
use App\Repository\ProjectRepository;

class ProjectPanelService {
    public function __construct(
        private ProjectRepository $projectRepository,
        private ProjectNotesController $projectNotesController
    ) {}

    public function buildProjectPanel($projectId, $currentNavTab, $currentPaginationPage): array {
        $project = $this->projectRepository->fetchProject($projectId);

        $baseUrl = [
            "page" => "projectPanel",
            "projectId" => $projectId,
            "currentPaginationPage" => $currentPaginationPage
        ];

        $tabData = [];

        if ($currentNavTab === "projectNotes") {
            $paginationPayload = $this->projectNotesController->fetchProjectNotes($projectId);
            $tabData["projectNotes"] = $paginationPayload["projectNotes"];
            $tabData["paginationMeta"] = $paginationPayload["paginationMeta"];
        }

        if ($currentNavTab === "createTask") {
            $tabData["projectMembers"] = $this->projectRepository->fetchMembersInProject($projectId);
        }

        return [
            "project" => $project,
            "baseUrl" => $baseUrl,
            "currentNavTab" => $currentNavTab,
            "tabData" => $tabData
        ];
    }
}