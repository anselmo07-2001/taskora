<?php

namespace App\Repository;

use PDOException;
use Exception;
use PDO;

class TaskRepository {
    public function __construct(private PDO $pdo) {}

    public function fetchTaskByProjectId(int $taskId): ?array {
        try {
            // Query 1: Task info
            $stmt = $this->pdo->prepare("SELECT 
                            projects.name AS project_name,
                            tasks.taskname AS task_name,
                            tasks.task_description,
                            tasks.tasktype,
                            tasks.id AS task_id,
                            tasks.deadline AS task_deadline,
                            (SELECT COUNT(*) FROM task_assignments WHERE task_assignments.task_id = tasks.id) AS total_assigned_members,
                            CASE 
                                WHEN DATE(tasks.deadline) = CURDATE() THEN 'Due Today'
                                WHEN DATE(tasks.deadline) > CURDATE() THEN CONCAT(DATEDIFF(tasks.deadline, CURDATE()), ' days away from deadline')
                                ELSE CONCAT(DATEDIFF(CURDATE(), tasks.deadline), ' days overdue')
                            END AS task_due_status,
                            assigner.fullname AS assigned_by,
                            CASE 
                                WHEN tasks.tasktype = 'solo' THEN (
                                    SELECT users.fullname
                                    FROM task_assignments
                                    JOIN users ON task_assignments.user_id = users.id
                                    WHERE task_assignments.task_id = tasks.id
                                    LIMIT 1
                                )
                                ELSE NULL
                            END AS assigned_to,
                            tasks.status AS current_task_status,
                            tasks.approval_status
                        FROM 
                            tasks
                        JOIN projects ON tasks.project_id = projects.id
                        JOIN users AS assigner ON projects.assigned_manager = assigner.id
                        WHERE tasks.id = :taskId"); 

            $stmt->execute([":taskId" => $taskId]);
            $task = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$task) return null;

            // Query 2: Task notes
            $stmtNotes = $this->pdo->prepare("SELECT 
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
                                    task_notes.created_at DESC");
                                    
                                    
            $stmtNotes->execute([":taskId" => $taskId]);
            $notes = $stmtNotes->fetchAll(PDO::FETCH_ASSOC);

            // Combine
            $task["task_notes"] = $notes;

            return $task;

        }
        catch(PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }



    public function fetchProjectGroupTask(int $projectId, array $taskFilters) {
        try {
           $sql = "SELECT
                        tasks.id,
                        tasks.taskname,
                        COUNT(task_assignments.user_id) AS assigned_members,
                        MIN(task_assignments.assigned_date) AS assigned_date,
                        tasks.deadline,
                        CONCAT(DATEDIFF(CURDATE(), MIN(task_assignments.assigned_date)), ' Days') AS milestone,
                        tasks.status,
                        tasks.approval_status
                    FROM
                        tasks
                    JOIN
                        task_assignments ON tasks.id = task_assignments.task_id
                    JOIN
                        users ON task_assignments.user_id = users.id
                    WHERE
                        tasks.tasktype = 'group'
                        AND tasks.project_id = :projectId
                        AND users.role = 'member'";

            $params = [":projectId" => $projectId];

            if (!empty($taskFilters['filter'])) {
                switch ($taskFilters['filter']) {
                    case 'due_today':
                        $sql .= " AND DATE(tasks.deadline) = CURDATE()";
                        break;
                    case 'overdue':
                        $sql .= " AND DATE(tasks.deadline) < CURDATE()";
                        break;
                    case 'upcoming':
                        $sql .= " AND DATE(tasks.deadline) > CURDATE()";
                        break;
                    // No need for 'allGroupTask' — it's the default
                }
            }


            if (!empty($taskFilters['search'])) {
                $sql .= " AND tasks.taskname LIKE :search";
                $params[':search'] = '%' . $taskFilters['search'] . '%';
            }


            $sql .= " GROUP BY tasks.id, tasks.taskname, tasks.deadline, tasks.status, tasks.approval_status";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }



    public function fetchProjectSoloTasks(int $projectId, array $taskFilters) {
        try {
           $sql = "SELECT
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
                        AND users.role = 'member'";
                        
            $params = [":projectId" => $projectId];

            if (!empty($taskFilters['filter'])) {
                switch ($taskFilters['filter']) {
                    case 'due_today':
                        $sql .= " AND DATE(tasks.deadline) = CURDATE()";
                        break;
                    case 'overdue':
                        $sql .= " AND DATE(tasks.deadline) < CURDATE()";
                        break;
                    case 'upcoming':
                        $sql .= " AND DATE(tasks.deadline) > CURDATE()";
                        break;
                    // No need for 'allSoloTask' — it's the default
                }
            }


            // Search condition
            if (!empty($taskFilters['search'])) {
                $sql .= " AND (tasks.taskname LIKE :search OR users.fullname LIKE :search)";
                $params[':search'] = '%' . $taskFilters['search'] . '%';
            }

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);

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