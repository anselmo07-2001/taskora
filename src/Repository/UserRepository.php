<?php

namespace App\Repository;

use App\Models\UserModel;
use PDO;
use PDOException;
use Exception;

class UserRepository {
    public function __construct(private PDO $pdo) {}

    public function handleUpdateAccount(string $sql, array $params) {
        try {
            $stmt = $this->pdo->prepare($sql);
            
            foreach($params as $key => $value) {
                $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
            }

            $stmt->execute();
            return $stmt->rowCount() > 0;
        }
        catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
        
    }


    public function countAllUsers(string $filter = 'all', string $search = '', string $role = 'admin',  int $userId = 1 ): int {
        try {
            $sql = "
                SELECT COUNT(DISTINCT users.id) AS total_users
                FROM users
                LEFT JOIN project_members ON project_members.user_id = users.id
                LEFT JOIN projects 
                    ON projects.id = project_members.project_id
                WHERE users.status != 'deleted'
            ";

            $params = [];

            // Filter users by their role if specified
            if ($filter !== 'all') {
                $sql .= " AND users.role = :user_role";
                $params[':user_role'] = $filter;
            }

            // Apply search on fullname
            if (!empty($search)) {
                $sql .= " AND users.fullname LIKE :search";
                $params[':search'] = '%' . $search . '%';
            }

            // If project_manager, limit to users in their projects
            if ($role === 'project_manager') {
                $sql .= " AND projects.assigned_manager = :manager_id";
                $params[':manager_id'] = $userId;
            }

            $stmt = $this->pdo->prepare($sql);

            // Bind values
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
            }

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return isset($result['total_users']) ? (int) $result['total_users'] : 0;

        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function handleUpdateAccountStatus(int $userId, string $status) { 
        try {
            $stmt = $this->pdo->prepare("UPDATE users SET status = :status WHERE id = :userId");
            $stmt->bindValue(":userId", $userId, PDO::PARAM_INT);
            $stmt->bindValue(":status", $status);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        }
        catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }      
    }

    public function fetchUserProfileById(int $userId) {
        try {
            $stmt = $this->pdo->prepare("SELECT id, username, fullname, role, status, password FROM `users` WHERE id = :userId");
            $stmt->bindValue(":userId", $userId);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $user = $stmt->fetch();

            if (!empty($user)) {
             return $user;
            }
            else {
                return null;
            }
        }
        catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }
   
    public function findByUsername($username) : ?UserModel {
        $stmt = $this->pdo->prepare("SELECT * FROM `users` WHERE username = :username");
        $stmt->bindValue(":username", $username);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, UserModel::class);
        $user = $stmt->fetch();

        if (!empty($user)) {
             return $user;
         }
         else {
            return null;
         }
    }

    public function createAccount(array $formData) {
        $stmt = $this->pdo->prepare("INSERT INTO `users` (username, fullname, password, role, status) VALUES (:username, :fullName, :password, :role, :status)");
        $stmt->bindValue(":username", $formData["username"]);
        $stmt->bindValue(":fullName", $formData["fullName"]);
        $stmt->bindValue(":password", $formData["password"]);
        $stmt->bindValue(":role", $formData["role"]);
        $stmt->bindValue(":status", $formData["status"]);

        return $stmt->execute();
    }

    public function fetchAllActiveUser(string $role)  {
        $stmt = $this->pdo->prepare("SELECT * FROM `users` WHERE role = :role AND `status` = 'active'");
        $stmt->bindValue(":role", $role);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);   
    }
}