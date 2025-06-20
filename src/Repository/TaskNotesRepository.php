<?php

namespace App\Repository;

use PDO;
use PDOException;
use Exception;

class TaskNotesRepository {

    public function __construct(private PDO $pdo){}
 

    public function fetchTaskNote(int $taskId, int $limit = 5, int $offset = 0) {
        try {
            $stmt = $this->pdo->prepare("SELECT 
                        task_notes.id AS note_id,
                        users.fullname AS note_author,
                        users.role AS role,
                        users.id AS creator_id, 
                        task_notes.content AS note_content,
                        task_notes.created_at AS note_created_at,
                        task_notes.edited_at AS note_edited_at,
                        task_notes.tasknote_type
                    FROM 
                        task_notes
                    JOIN users ON task_notes.user_id = users.id
                    WHERE 
                        task_notes.task_id = :taskId
                    ORDER BY 
                        task_notes.created_at DESC
                    LIMIT :limit OFFSET :offset");

            $stmt->bindValue(':taskId', $taskId, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

            $stmt->execute();
            return $result = $stmt->fetchAll(PDO::FETCH_ASSOC);         

        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }  
    }


    public function countAllTaskNote(int $taskId) {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) AS total_task_notes FROM task_notes WHERE task_id = :taskId");
            $stmt->execute([':taskId' => $taskId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int) $result["total_task_notes"];
        }
        catch (PDOException $e) {
            throw new Exception($e->getMessage());
        } 
    }


    public function handleDeleteTaskNote(int $taskNoteId) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM task_notes WHERE id = :id");
            $stmt->bindValue(":id", $taskNoteId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        }
        catch(PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

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