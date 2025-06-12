<?php // var_dump($project ?? ""); ?>

<div class="container custom-container pb-5">
        <?php require __DIR__ . "/../components/flashMessage.view.php" ?>
        <div>
            <div class="d-flex align-items-center gap-2 mb-4">
                <img src="./public/images/scope.png" class="myproject-title-icon"/>
                <h1><?= e($project["name"]); ?></h1>
            </div>
            <h5 class="lh-sm">Objective</h5>
            <p class="fs-5 mb-4"><?= e($project["project_description"]); ?></p>
            <div class="mb-4">
                 <ul class="list-unstyled">
                     <li>Deadline: <span><?= e($project["deadline"]); ?></span></li>
                     <li>Deadline Status: <span><?= e($project["deadline_status"]); ?></span></li>
                     <li>Progress: <span><?= e($project["progress"]); ?></span></li>
                     <li>Members: <span><?= e($project["member_count"]); ?></span></li>
                     <li>Project Manager: <span><?= e($project["fullname"]); ?></span></li>
                     <li>Current Project Status: <span><?= e($project["status"]); ?></span></li>
                 </ul>
            </div>

            <?php if ($currentUserSession["role"] !== "member"): ?>
                <div class="mb-5">      
                    <form method="POST" class="d-flex align-items-center gap-3" 
                            action="<?= BASE_URL . "/index.php?" . http_build_query(["page" => "updateProjectStatus", "projectId" => $project["id"]]) ?>" 
                    >
                        <label for="project-status" class="form-label">Change the project status:</label>
                        <select class="form-select w-25" id="project-status" style="margin-right: -0.5rem;" name="projectStatus">
                            <option value="pending" <?= $project["status"] === "pending" ? "selected" : "" ?>>Pending</option>
                            <option value="in_progress" <?= $project["status"] === "in_progress" ? "selected" : "" ?>>In progress</option>
                            <option value="completed" <?= $project["status"] === "completed" ? "selected" : "" ?>>Completed</option>
                            <option value="failed" <?= $project["status"] === "failed" ? "selected" : "" ?>>Failed</option>
                        </select>
                        <button class="btn custom-primary-btn">Update Project</button>
                    </form>
                </div>
            <?php endif ?>

            <ul class="nav nav-tabs my-custom-tabs mb-5">
                <li class="nav-item">
                    <a href="<?= BASE_URL . "/index.php?" . http_build_query($baseUrl + ["currentNavTab" => "projectNotes"]); ?>" class="nav-link <?= $currentNavTab === "projectNotes" ? "active" : "" ?>" aria-current="page">Project Note</a>
                </li>
                <li class="nav-item">
                    <a href="<?= BASE_URL . "/index.php?" . http_build_query($baseUrl + ["currentNavTab" => "assignedSoloTask"]); ?>" class="nav-link <?= $currentNavTab === "assignedSoloTask" ? "active" : "" ?>">Assigned Solo Task</a>
                </li>
                <li class="nav-item">
                    <a href="<?= BASE_URL . "/index.php?" . http_build_query($baseUrl + ["currentNavTab" => "assignedGroupTask"]); ?>" class="nav-link <?= $currentNavTab === "assignedGroupTask" ? "active" : "" ?>">Assigned Group Task</a>
                </li>
                <li class="nav-item">
                    <a href="<?= BASE_URL . "/index.php?" . http_build_query($baseUrl + ["currentNavTab" => "submittedTask"]); ?>" class="nav-link <?= $currentNavTab === "submittedTask" ? "active" : "" ?>" >Submitted Task</a>
                </li>
                <li class="nav-item">
                    <a href="<?= BASE_URL . "/index.php?" . http_build_query($baseUrl + ["currentNavTab" => "createTask"]); ?>" class="nav-link <?= $currentNavTab === "createTask" ? "active" : "" ?>" >Create Task</a>
                </li>
                <li class="nav-item">
                    <a href="<?= BASE_URL . "/index.php?" . http_build_query($baseUrl + ["currentNavTab" => "manageMembers"]); ?>" class="nav-link <?= $currentNavTab === "manageMembers" ? "active" : "" ?>" >Manage Members</a>
                </li>
            </ul>

        <?php if ($currentNavTab === "projectNotes"): ?>
            <?php require __DIR__ . "/projectProjectNotes.view.php"; ?>
        <?php endif; ?>


        <?php if ($currentNavTab === "assignedSoloTask"): ?>
            <?php require __DIR__ . "/projectAssignedSoloTask.view.php"; ?>
        <?php endif; ?>
    

        <?php if ($currentNavTab === "assignedGroupTask"): ?>
            <?php require __DIR__ . "/projectAssignedGroupTask.view.php"; ?>
        <?php endif; ?>
        

        <?php if ($currentNavTab === "submittedTask"): ?>
            <?php require __DIR__ . "/projectSubmittedTask.view.php"; ?>
        <?php endif; ?>
        

        <?php if ($currentNavTab === "createTask"): ?>
            <?php require __DIR__ . "/projectCreateTask.view.php"; ?>
        <?php endif ?>

        
        <?php if ($currentNavTab === "manageMembers"): ?>
            <?php require __DIR__ . "/projectManageMembers.view.php"; ?>
        <?php endif ?>
</div>

