<?php

namespace App\Models;

class ProjectModel {
    public int $id;
    public string $name;
    public string $project_description;
    public int $assigned_manager;
    public string $date;
    public string $status;
    public string $date_created;
    public int $is_suspended; 
}
