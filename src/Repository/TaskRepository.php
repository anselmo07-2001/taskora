<?php

namespace App\Repository;

use PDOException;
use Exception;
use PDO;

class TaskRepository {
    public function __construct(private PDO $pdo) {}

    public function fetchProjectSoloTasks(int $projectId) {
        try {
           $stmt = $this->pdo->prepare("SELECT
                        tasks.id,
                        tasks.taskname,
                        users.fullname,
                        task_assignments.assigned_date,
                        tasks.deadline,
                        CONCAT(DATEDIFF(CURDATE(), task_assignments.assigned_date), ' Days') as milestone,
                        tasks.status,
                        tasks.approval_status
                    FROM
                        tasks
                    JOIN task_assignments ON tasks.id = task_assignments.task_id
                    JOIN users ON task_assignments.user_id = users.id
                    WHERE
                        tasks.tasktype = 'solo'
                        AND tasks.project_id = :projectId
                        AND users.role = 'member'");
                        
            $stmt->execute([
                ":projectId" => $projectId
            ]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function handleCreateTask(array $taskData) {
        try {
            $this->pdo->beginTransaction(); 
            
            // Insert into tasks
            $stmtTask = $this->pdo->prepare("
                            INSERT INTO tasks (project_id, taskname, task_description, tasktype, is_submitted, status, deadline, approval_status, created_at, updated_at)
                            VALUES (:project_id, :taskname, :task_description, :tasktype, :is_submitted, :status, :deadline, :approval_status, NOW(), NOW())
                        ");

            $stmtTask->execute([
                ":project_id" => $taskData["projectId"],
                ":taskname" => $taskData["taskName"],
                ":task_description" => $taskData["taskDescription"],
                ":tasktype" => $taskData["taskType"],
                ":is_submitted" => $taskData["isSubmitted"],
                ":status" => $taskData["status"],
                ":deadline" => $taskData["taskDeadline"],
                ":approval_status" => $taskData["approvalStatus"]
            ]);

            $taskId = $this->pdo->lastInsertId();

            // Insert into task_assignments
            $stmtAssignment = $this->pdo->prepare("
                                    INSERT INTO task_assignments (task_id, user_id, assigned_date)
                                    VALUES (:task_id, :user_id, NOW())
                                ");
            
            foreach ($taskData["assignedMembers"] as $userId) {
                 $stmtAssignment->execute([
                    ":task_id" => $taskId,
                    ":user_id" => $userId
                 ]);
            }


            // Insert into task_notes
            $stmtNote = $this->pdo->prepare("
                            INSERT INTO task_notes (task_id, user_id, content, created_at, tasknote_type, edited_at)
                            VALUES (:task_id, :user_id, :content, NOW(), :tasknote_type, NOW())
                        ");
            
            $stmtNote->execute([
                ":task_id" => $taskId,
                ":user_id" => $taskData["taskCreatorId"],
                ":content" => $taskData["taskNote"],
                ":tasknote_type" => $taskData["taskNoteType"]
            ]);


            $this->pdo->commit();
            return true;
        }
        catch(PDOException $e) {
            $this->pdo->rollBack();
            throw new Exception($e->getMessage());
         }
    }
}