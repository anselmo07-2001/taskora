<?php

namespace App\Repository;

use App\Models\ProjectNotes;

use PDOException;
use Exception;
use PDO;

class ProjectNotesRepository {

    public function __construct(private PDO $pdo) {}

    public function handleUpdateProjectNotes(array $data) {
        try {
            $stmt = $this->pdo->prepare("UPDATE project_notes SET content = :content WHERE id = :id");
            $stmt->execute($data);
            return $stmt->rowCount() > 0;
        }
        catch(PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }


    public function fetchProjectNote(int $projectNoteId): ?ProjectNotes {
        try {
            $stmt = $this->pdo->prepare("SELECT 
                        project_notes.*, users.fullname, users.role
                        FROM `project_notes` JOIN users ON project_notes.user_id = users.id WHERE project_notes.id = :projectNote_id 
                        ORDER BY `created_at` DESC");

            $stmt->bindValue(":projectNote_id", $projectNoteId);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, ProjectNotes::class);
            $projectNote = $stmt->fetch();
            return $projectNote ?: null;
        }
        catch(PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }


    public function fetchProjectNotes(int $projectId): array {
        try {
            $stmt = $this->pdo->prepare("SELECT 
                        project_notes.*, users.fullname, users.role
                        FROM `project_notes` JOIN users ON project_notes.user_id = users.id WHERE `project_id` = :project_id 
                        ORDER BY `created_at` DESC");

            $stmt->bindValue(":project_id", $projectId);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, ProjectNotes::class);
            $projectNotes = $stmt->fetchAll();
            return $projectNotes;
        }
        catch(PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }


    public function handleCreateProjectNote(array $data) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO project_notes (project_id, user_id, content, created_at, edited_at, projectnote_type)
                       VALUES (:project_id, :user_id, :content, NOW(), NOW(), :projectnote_type)");

            $stmt->bindValue(':project_id', $data["project_id"], PDO::PARAM_INT);
            $stmt->bindValue(':user_id', $data["user_id"], PDO::PARAM_INT);
            $stmt->bindValue(':content', $data["content"]);
            $stmt->bindValue(':projectnote_type', $data["projectnote_type"]);

            return $stmt->execute();
        }
         catch(PDOException $e) {
            throw new Exception($e->getMessage());
         }
    }
}