<?php

namespace App\Repository;

use PDO;

class ProjectRepository {
    public function __construct(private PDO $pdo) {}

    public function handleCreateProject() {
        echo "creaating the projects";
    }
}