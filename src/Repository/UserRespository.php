<?php

namespace App\Repository;

use App\Models\UserModel;
use PDO;

class UserRespository {
    public function __construct(private PDO $pdo) {}
   
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