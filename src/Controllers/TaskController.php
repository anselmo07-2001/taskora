<?php

namespace App\Controllers;

use App\Controllers\AbstractController;

class TaskController extends AbstractController{

    public function createTask($request) {
        $taskName = $request["post"]["taskname"];
        $taskDescription = $request["post"]["taskDescription"];
        $taskDeadline = $request["post"]["taskDeadline"];
        $taskType = $request["post"]["taskType"];
        // $assignedMembers = $request["post"]["assignedMembers"];
        $taskNote = $request["post"]["taskNote"];

        var_dump($request);
    }

}