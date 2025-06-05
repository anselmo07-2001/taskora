<?php

namespace App\Repository;

use App\Models\UserModel;
use PDO;

class UserRespository {
    public function __construct(private PDO $pdo) {}
   
    public function findByUsername($username) : ?UserModel {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = :username");
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
}