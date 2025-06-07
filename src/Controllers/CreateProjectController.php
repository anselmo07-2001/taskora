<?php

namespace App\Controllers;

class CreateProjectController extends AbstractController {
    public function showProjectFormPage() {
        $this->render("createProject.view", []);
    }
}