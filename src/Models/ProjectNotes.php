<?php

namespace App\Models;

class ProjectNotes {
    public int $id;
    public int $project_id;
    public int $user_id;
    public string $content;
    public string $created_at;
    public string $edited_at;
    public string $projectnote_type;

    public string $fullname;
    public string $role;
}
