<?php

namespace App\Repository;

use App\Models\ProjectNotes;
use App\Support\SessionService;
use PDO;
use PDOException;
use Exception;

class ProjectRepository {
    public function __construct(private PDO $pdo) {}

    public function handleDeleteProject(int $projectId) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM projects WHERE id = :id");
            $stmt->bindValue(":id", $projectId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        }
        catch(PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }


    public function fetchMemberTaskSummary(int $projectId, array $filters = []): ?array {
        try {
            $taskTypeFilter = $filters['filter'] ?? null; // 'solo', 'group', or null
            $search = $filters['search'] ?? null;

            $conditions = [
                "project_members.project_id = :projectId",
                "users.status != 'deleted'",
                "users.role NOT IN ('project_manager', 'admin')"
            ];

            $params = [
                ':projectId' => $projectId
            ];

            if ($taskTypeFilter === 'solo' || $taskTypeFilter === 'group') {
                $conditions[] = "tasks.tasktype = :taskType";
                $params[':taskType'] = $taskTypeFilter;
            }

            if (!empty($search)) {
                $conditions[] = "users.fullname LIKE :search";
                $params[':search'] = "%{$search}%";
            }

            $whereSql = implode(" AND ", $conditions);

            $sql = "
                SELECT 
                    users.id AS id,
                    users.fullname AS name,

                    COUNT(CASE WHEN tasks.status IS NOT NULL THEN tasks.id END) AS total_task,

                    COUNT(CASE 
                        WHEN tasks.status IS NOT NULL AND tasks.status != 'completed' 
                        THEN tasks.id 
                    END) AS unsubmitted_task,

                    COUNT(CASE 
                        WHEN tasks.status = 'completed' 
                            AND (tasks.approval_status IS NULL OR tasks.approval_status IN ('approved', 'rejected'))
                        THEN tasks.id 
                    END) AS submitted_task,

                    COUNT(CASE 
                        WHEN tasks.approval_status = 'approved' 
                        THEN tasks.id 
                    END) AS approved_task,

                    COUNT(CASE 
                        WHEN tasks.approval_status = 'rejected' 
                        THEN tasks.id 
                    END) AS rejected_task

                FROM project_members
                JOIN users ON users.id = project_members.user_id
                LEFT JOIN task_assignments ON task_assignments.user_id = users.id
                LEFT JOIN tasks ON tasks.id = task_assignments.task_id 
                    AND tasks.project_id = :projectId

                WHERE $whereSql

                GROUP BY users.id, users.fullname
                ORDER BY users.fullname
            ";

            $stmt = $this->pdo->prepare($sql);

            foreach ($params as $key => $val) {
                $stmt->bindValue($key, $val, is_int($val) ? PDO::PARAM_INT : PDO::PARAM_STR);
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }


    public function fetchMembersInProject(int $projectId) {
        try {
            $stmt = $this->pdo->prepare("
                            SELECT 
                            users.id,
                            users.username,
                            users.fullname,
                            users.role
                        FROM project_members
                        JOIN users ON project_members.user_id = users.id
                        WHERE project_members.project_id = :projectId
                        AND project_members.is_active = 1
                        AND users.status NOT IN ('suspended', 'deleted')
                        AND users.role NOT IN ('admin', 'project_manager')
                    ");

            $stmt->execute(['projectId' => $projectId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function handleUpdateProjectStatus(int $projectId, string $newProjectStatus) {
        try {
            $stmt = $this->pdo->prepare("UPDATE projects SET status = :status WHERE id = :projectId");
            $stmt->execute([
                "status" => $newProjectStatus,
                "projectId" => $projectId
            ]);

            return $stmt->rowCount() > 0; // true if update happened
        }
        catch(PDOException $e) {
            throw new Exception($e->getMessage());
         }
    }


    /** fetch projects that owned by the project_manager or the admin */
    public function fetchProject(int $id) {
         try {
            $stmt = $this->pdo->prepare("SELECT 
                        projects.id,
                        projects.name,
                        projects.project_description,
                        projects.deadline,
                        CASE 
                            WHEN projects.deadline < CURDATE() THEN 'Overdue'
                            WHEN projects.deadline = CURDATE() THEN 'Today'
                            WHEN projects.deadline > CURDATE() THEN 'Upcoming'
                        END AS deadline_status,
                        -- Progress calculation 
                        IFNULL(
                            CONCAT(
                                ROUND(
                                    (SUM(CASE WHEN tasks.status = 'completed' THEN 1 ELSE 0 END) / NULLIF(COUNT(tasks.id), 0)) * 100,
                                    0
                                ), '%'
                            ),
                            '0%'
                        ) AS progress,

                        -- Members full names (excluding manager)
                        GROUP_CONCAT(DISTINCT 
                            CASE 
                                WHEN project_members.user_id != projects.assigned_manager THEN users.fullname
                                ELSE NULL
                            END
                            SEPARATOR ', '
                        ) AS members,

                        -- Count of members (excluding manager)
                        COUNT(DISTINCT 
                            CASE 
                                WHEN project_members.user_id != projects.assigned_manager THEN project_members.user_id
                                ELSE NULL
                            END
                        ) AS member_count,
                  
                        manager.fullname,
                     
                        projects.status

                        FROM projects
                      
                        JOIN users AS manager ON projects.assigned_manager = manager.id
                        LEFT JOIN tasks ON tasks.project_id = projects.id
                        LEFT JOIN project_members ON project_members.project_id = projects.id AND project_members.is_active = 1
                        LEFT JOIN users ON users.id = project_members.user_id

                        -- Filter by project ID
                        WHERE projects.id = :id
                        GROUP BY projects.id;");

            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result;
         }
         catch(PDOException $e) {
            throw new Exception($e->getMessage());
         }
    }

     /** fetch projects that owned by a member */
    public function fetchProjectsForMember(int $memberId, string $whereSql = "", array $params = []) {
        
        try {
            $where = [];
            $params['memberId'] = $memberId;

            // Only show projects where this user is a member (active or not)
            $where[] = "projects.id IN (
                SELECT project_id FROM project_members 
                WHERE user_id = :memberId
            )";

            if (!empty($whereSql)) {
                $where[] = preg_replace('/^WHERE\s+/i', '', $whereSql);
            }

            $finalWhere = '';
            if (!empty($where)) {
                $finalWhere = 'WHERE ' . implode(' AND ', $where);
            }

            $stmt = $this->pdo->prepare("
                SELECT 
                    projects.id,
                    projects.name,
                    projects.assigned_manager AS manager_id,  -- added field
                    manager.fullname AS fullname,

                    COUNT(DISTINCT CASE 
                        WHEN project_members.user_id != projects.assigned_manager THEN project_members.user_id 
                    END) AS number_of_members,

                    COUNT(DISTINCT tasks.id) AS number_of_tasks,

                    projects.deadline,
                    projects.status,

                    ROUND(
                        IF(COUNT(DISTINCT tasks.id) = 0, 0,
                            (COUNT(DISTINCT CASE WHEN tasks.approval_status = 'approved' THEN tasks.id END) / COUNT(DISTINCT tasks.id)) * 100
                        ), 0
                    ) AS progress

                FROM projects

                JOIN users AS manager ON manager.id = projects.assigned_manager

                LEFT JOIN project_members 
                    ON project_members.project_id = projects.id

                LEFT JOIN users ON users.id = project_members.user_id
                LEFT JOIN tasks ON tasks.project_id = projects.id

                {$finalWhere}

                GROUP BY 
                    projects.id,
                    projects.name,
                    projects.assigned_manager,  -- added to GROUP BY
                    manager.fullname,
                    projects.deadline,
                    projects.status
            ");

            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }


    public function fetchProjects(?int $projectManagerId = null, string $whereSql = "", array $params = []) {
        try {
            $where = [];

            if ($projectManagerId !== null) {
                $where[] = "projects.assigned_manager = :projectManagerId";
                $params['projectManagerId'] = $projectManagerId;
            }

            if (!empty($whereSql)) {
                $where[] = preg_replace('/^WHERE\s+/i', '', $whereSql);
            }

            $finalWhere = '';
            if (!empty($where)) {
                $finalWhere = 'WHERE ' . implode(' AND ', $where);
            }

            $stmt = "
                SELECT 
                    projects.id,
                    projects.name,
                    users.fullname,
                    COUNT(DISTINCT CASE 
                        WHEN project_members.user_id != projects.assigned_manager THEN project_members.user_id 
                    END) AS number_of_members,
                    COUNT(DISTINCT tasks.id) AS number_of_tasks,
                    projects.deadline,
                    projects.status,
                    ROUND(
                        IF(COUNT(DISTINCT tasks.id) = 0, 0,
                            (COUNT(DISTINCT CASE WHEN tasks.approval_status = 'approved' THEN tasks.id END) / COUNT(DISTINCT tasks.id)) * 100
                        ), 0
                    ) AS progress
                FROM projects
                LEFT JOIN users ON projects.assigned_manager = users.id
                LEFT JOIN project_members 
                    ON projects.id = project_members.project_id
                    AND project_members.user_id IN (
                        SELECT id FROM users WHERE status != 'deleted'
                    )
                LEFT JOIN tasks ON projects.id = tasks.project_id
                {$finalWhere}
                GROUP BY 
                    projects.id, 
                    projects.name, 
                    users.fullname, 
                    projects.deadline, 
                    projects.status
            ";

            $stmt = $this->pdo->prepare($stmt);
            $stmt->execute($params);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function handleCreateProject(array $formData) {
        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare("INSERT INTO projects (name, project_description, assigned_manager, deadline, status, date_created, is_suspended) VALUES (:name, :project_description, :assigned_manager, :deadline, :status, NOW(), :is_suspended)");
    
            $stmt->bindValue(":name", $formData["projectName"]);
            $stmt->bindValue(":project_description", $formData["projectDescription"]);
            $stmt->bindValue(":assigned_manager", $formData["assignedProjectManager"]);
            $stmt->bindValue(":deadline", $formData["projectDeadline"]);
            $stmt->bindValue(":status", $formData["projectStatus"]);
            $stmt->bindValue(":is_suspended", $formData["isSuspended"]);
            $stmt->execute();
    
            $projectId = $this->pdo->lastInsertId();
    
            $teamIds = [
                $formData["assignedProjectManager"],
                ...$formData["assignedMembers"]
            ];
    
            $projectUsers = [];
    
            foreach ($teamIds as $userId) {
                $projectUsers[] = [
                    'project_id' => $projectId,
                    'user_id' => $userId,
                    'is_active' => 1  // All userId are all active in the dropdown
                ];
            }
    
            $stmt = $this->pdo->prepare("INSERT INTO project_members (project_id, user_id, is_active) VALUES (:project_id, :user_id, :is_active)");
    
            foreach ($projectUsers as $row ) {
                $stmt->bindValue(':project_id', $row['project_id'], PDO::PARAM_INT);
                $stmt->bindValue(':user_id', $row['user_id'], PDO::PARAM_INT);
                $stmt->bindValue(':is_active', $row['is_active'], PDO::PARAM_INT);
                $stmt->execute();
            }
    
    
            $currentUser = SessionService::getSessionKey("user")["userId"];
            $stmt = $this->pdo->prepare("INSERT INTO project_notes (project_id, user_id, content, created_at, edited_at, projectnote_type) VALUES (:project_id, :user_id, :content, :created_at, :edited_at, :projectnote_type)");
    
            $stmt->bindValue(':project_id', $projectId, PDO::PARAM_INT);
            $stmt->bindValue(':user_id', $currentUser, PDO::PARAM_INT);
            $stmt->bindValue(':content', $formData["projectNote"]);
            $stmt->bindValue(':created_at', date('Y-m-d H:i:s')); 
            $stmt->bindValue(':edited_at', null, PDO::PARAM_NULL);
            $stmt->bindValue(':projectnote_type', $formData["projectNoteType"]);
            $stmt->execute();

            $this->pdo->commit();
            return true;
        } 
        catch (PDOException $e) {
            // Roll back all changes if anything failed
            $this->pdo->rollBack();

            // Optionally log the error or rethrow
            throw new Exception("Project creation failed: " . $e->getMessage());
        }
    }
}