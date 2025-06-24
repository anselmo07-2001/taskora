<?php

namespace App\Repository;

use PDOException;
use Exception;
use PDO;

class TaskRepository {
    public function __construct(private PDO $pdo) {}

    public function fetchUserTasks(int $userId, string $taskType = 'all', ?int $projectId = null): array {
        try {
            $sql = "
                SELECT 
                    tasks.id AS id,
                    tasks.taskname AS name,

                    CONCAT(
                        UPPER(tasks.tasktype),
                        ' (',
                        member_count.count,
                        ' member',
                        IF(member_count.count > 1, 's', ''),
                        ')'
                    ) AS `taskType_members`,

                    ta.assigned_date AS `assigned date`,
                    tasks.deadline AS deadline,
                    tasks.status AS status,

                    CONCAT(DATEDIFF(CURDATE(), DATE(tasks.created_at)), ' days') AS milestone,

                    CASE
                        WHEN tasks.approval_status IS NULL AND tasks.status != 'completed' THEN 'Pending Completion'
                        WHEN tasks.approval_status IS NULL AND tasks.status = 'completed' THEN 'Awaiting Approval'
                        WHEN tasks.approval_status = 'approved' THEN 'Approved'
                        WHEN tasks.approval_status = 'rejected' THEN 'Rejected'
                        ELSE 'Unknown'
                    END AS `approval status`

                FROM tasks
                JOIN task_assignments AS ta ON tasks.id = ta.task_id
                JOIN (
                    SELECT task_id, COUNT(*) AS count
                    FROM task_assignments
                    GROUP BY task_id
                ) AS member_count ON member_count.task_id = tasks.id
            ";

            $conditions = ["ta.user_id = :userId"];
            $params = [":userId" => $userId];

            if ($taskType === 'solo' || $taskType === 'group') {
                $conditions[] = "tasks.tasktype = :taskType";
                $params[":taskType"] = $taskType;
            }

            // Optional project filter
            if ($projectId !== null) {
                $conditions[] = "tasks.project_id = :projectId";
                $params[":projectId"] = $projectId;
            }

            // Apply WHERE conditions
            if (!empty($conditions)) {
                $sql .= " WHERE " . implode(" AND ", $conditions);
            }

            $sql .= " ORDER BY ta.assigned_date DESC";

            $stmt = $this->pdo->prepare($sql);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function fetchMemberAssignedSoloTask(int $userId): ?array {
        try {
            $stmt = $this->pdo->prepare("SELECT 
                        tasks.id AS id,
                        tasks.taskname AS task,
                        projects.name AS project,
                        task_assignments.assigned_date AS assigned_date,
                        tasks.status,
                        tasks.deadline,

                        -- Milestone: days since assigned_date
                        CONCAT(DATEDIFF(CURDATE(), DATE(task_assignments.assigned_date)), ' days') AS milestone,

                        -- Approval status with computed logic
                        CASE
                            WHEN tasks.approval_status IS NULL AND tasks.status != 'completed' THEN 'Pending Completion'
                            WHEN tasks.approval_status IS NULL AND tasks.status = 'completed' THEN 'Awaiting Approval'
                            WHEN tasks.approval_status = 'approved' THEN 'Approved'
                            WHEN tasks.approval_status = 'rejected' THEN 'Rejected'
                            ELSE 'Unknown'
                        END AS approval_status

                    FROM task_assignments
                    JOIN tasks ON task_assignments.task_id = tasks.id
                    JOIN projects ON tasks.project_id = projects.id

                    WHERE task_assignments.user_id = :userId
                          AND tasks.tasktype = 'solo'
                          AND (
                                SELECT COUNT(*) 
                                FROM task_assignments ta 
                                WHERE ta.task_id = tasks.id
                            ) = 1
                    ORDER BY task_assignments.assigned_date DESC;");

             $stmt->bindValue(":userId", $userId);
             $stmt->execute();
             return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function handleUpdateApprovalStatus(array $data) {
        try {
            $stmt = $this->pdo->prepare("UPDATE tasks SET approval_status = :approvalStatus WHERE id = :taskId");
            $stmt->bindValue(":approvalStatus", $data["approvalAction"]);
            $stmt->bindValue(":taskId", $data["taskId"]);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        }   
        catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }


    public function fetchSubmittedTasks(int $projectId): ?array {
        try {
            $stmt = $this->pdo->prepare("SELECT 
                tasks.id AS id,
                tasks.taskname AS task,
                CONCAT(UCASE(tasks.tasktype), ' (', COUNT(task_assignments.user_id), ' member', IF(COUNT(task_assignments.user_id) > 1, 's', ''), ')') AS task_type_and_members,
                MIN(task_assignments.assigned_date) AS assigned_date,
                tasks.deadline AS deadline,

                CASE 
                    WHEN DATE(tasks.deadline) = CURDATE() THEN 'Due Today'
                    WHEN DATE(tasks.deadline) < CURDATE() THEN 'Overdue'
                    ELSE 'Upcoming'
                END AS deadline_status,

                CONCAT(DATEDIFF(CURDATE(), DATE(tasks.created_at)), ' days') AS milestone,

                CASE
                    WHEN tasks.approval_status IS NULL THEN 'Pending Review'
                    WHEN tasks.approval_status = 'approved' THEN 'Approved'
                    WHEN tasks.approval_status = 'rejected' THEN 'Rejected'
                    ELSE 'Unknown'
                END AS approval_status

                FROM tasks
                JOIN task_assignments ON task_assignments.task_id = tasks.id

                WHERE tasks.status = 'completed'
                AND tasks.project_id = :projectId

                GROUP BY tasks.id
                ORDER BY tasks.created_at DESC;");

            $stmt->execute([':projectId' => $projectId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
      
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function fetchProjectManagerAndMembersByTaskId(int $taskId): ?array {
         try {
                // Get the project manager of the task
                $stmt = $this->pdo->prepare("
                    SELECT 
                        users.id,
                        users.fullname
                    FROM tasks
                    JOIN projects ON tasks.project_id = projects.id
                    JOIN users ON projects.assigned_manager = users.id
                    WHERE tasks.id = :taskId
                ");
                
                $stmt->execute([':taskId' => $taskId]);
                $manager = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$manager) {
                    return null;
                }

                // Get all members assigned to this task
                $stmt = $this->pdo->prepare("
                    SELECT users.id, users.fullname
                    FROM task_assignments
                    JOIN users ON task_assignments.user_id = users.id
                    WHERE task_assignments.task_id = :taskId
                ");

                $stmt->execute([':taskId' => $taskId]);
                $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
         
                return [
                    "project_manager" => [
                        "id" => (int)$manager["id"],
                        "fullname" => $manager["fullname"]
                    ],
                    "members" => $members
                ];

        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }


    public function handleEditTaskStatus(int $taskId, string $newTaskStatus) {
        try {
            $stmt = $this->pdo->prepare("SELECT approval_status FROM tasks WHERE id = :taskId");
            $stmt->execute(["taskId" => $taskId]);
            $current = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$current) {
                return false; // Task not found
            }

            $isApprovedOrRejected = in_array($current["approval_status"], ['approved', 'rejected']);

            if ($isApprovedOrRejected) {
                $sql = "UPDATE tasks SET status = :status, approval_status = NULL WHERE id = :taskId";
            } else {
                $sql = "UPDATE tasks SET status = :status WHERE id = :taskId";
            }

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                "status" => $newTaskStatus,
                "taskId" => $taskId
            ]);

            return $stmt->rowCount() > 0; // true if update happened
        }
        catch(PDOException $e) {
            throw new Exception($e->getMessage());
         }
    }



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