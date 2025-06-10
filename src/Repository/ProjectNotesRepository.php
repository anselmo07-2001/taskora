<?php

namespace App\Repository;

use PDO;

class ProjectNotesRepository {

    public function __construct(private PDO $pdo) {}

    public function handleCreateProjectNote() {
        echo "repo";
    }
}