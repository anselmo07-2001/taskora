<?php

namespace App\Repository;

use App\Models\ProjectNotes;
use App\Support\SessionService;
use PDO;
use PDOException;
use Exception;

class ProjectRepository {
    public function __construct(private PDO $pdo) {}

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
                        CONCAT(
                            ROUND(
                                (SUM(CASE WHEN tasks.status = 'completed' THEN 1 ELSE 0 END) /
                                NULLIF(COUNT(tasks.id), 0)) * 100, 0
                            ), '%'
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


    public function fetchAllProjects(string $whereSql, array $params) {
        try {
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
                                (COUNT(DISTINCT CASE WHEN tasks.status = 'completed' THEN tasks.id END) / COUNT(DISTINCT tasks.id)) * 100
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
                {$whereSql}
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