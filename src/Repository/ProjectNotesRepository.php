<?php

namespace App\Repository;

use PDOException;
use Exception;
use PDO;

class ProjectNotesRepository {

    public function __construct(private PDO $pdo) {}

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