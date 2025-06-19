<?php

namespace App\Repository;

use PDO;
use PDOException;
use Exception;

class TaskNotesRepository {

    public function __construct(private PDO $pdo){}
 

    public function handleEditTaskNote(string $newContent, int $taskNoteId) {
        try {
            $stmt = $this->pdo->prepare("UPDATE task_notes SET content = :content, edited_at = NOW() WHERE id = :id");
            $stmt->bindValue(":content", $newContent);
            $stmt->bindValue(":id", $taskNoteId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        }
        catch(PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }


    public function handleCreateTaskNote(array $formData) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO task_notes (task_id, user_id, content, created_at, tasknote_type, edited_at) 
                        VALUES(:task_id, :user_id, :content, NOW(), :tasknote_type, NOW())");

            $stmt->bindValue(":task_id", $formData["taskId"]);
            $stmt->bindValue(":user_id", $formData["userId"]);
            $stmt->bindValue(":content", $formData["content"]);
            $stmt->bindValue(":tasknote_type", $formData["taskNoteType"]);

            $stmt->execute();
            return $stmt->rowCount() > 0;
        }
        catch(PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }
}