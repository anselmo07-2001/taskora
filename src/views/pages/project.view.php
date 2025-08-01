<?php //var_dump($currentUserSession); ?>

<div class="container custom-container pb-5">
        <?php require __DIR__ . "/../components/backButton.view.php" ?>
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

            <div class="modal fade" id="updateProjectStatusModal" tabindex="-1" aria-labelledby="updateProjectStatusModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <form method="POST" action="<?= BASE_URL . "/index.php?" . http_build_query(["page" => "updateProjectStatus"] + $baseUrl); ?>">
                        <div class="modal-header">
                            <h5 class="modal-title" id="updateProjectStatusModalLabel">Update the status of the Project?</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="updateProjectStatusNote" class="form-label">Add a Project Note</label>
                                <textarea class="form-control" id="updateProjectStatusNote" name="updateProjectStatusNote" rows="3" required></textarea>
                            </div>
                            <input type="hidden" id="selectedProjectStatus" name="selectedProjectStatus">
                            <input type="hidden" id="defaultProjectStatus" name="defaultProjectStatus">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Change Project Status</button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>


            <?php if ($currentUserSession["role"] !== "member"): ?>
                <div class="mb-5">      
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <label for="project-status" class="form-label">Change the project status:</label>
                        <select class="form-select w-25" id="project-status" style="margin-right: -0.5rem;" name="projectStatus">
                            <option value="pending" <?= $project["status"] === "pending" ? "selected" : "" ?>>Pending</option>
                            <option value="in_progress" <?= $project["status"] === "in_progress" ? "selected" : "" ?>>In progress</option>
                            <option value="completed" <?= $project["status"] === "completed" ? "selected" : "" ?>>Completed</option>
                            <option value="failed" <?= $project["status"] === "failed" ? "selected" : "" ?>>Failed</option>
                        </select>
                        <button class="btn custom-primary-btn" data-bs-toggle="modal" 
                              data-bs-target="#updateProjectStatusModal" data-current-projectStatus="<?= $project["progress"] ?>">
                            Update Project
                        </button>
                    </div>
                    <small id="changeProjectStatusMessage" class="text-danger d-none">Please change the project status before updating</small>
                    <p id="notYetCompleteStatusMessage" class="text-danger d-none small">You must finish all tasks before setting the project status to "Approved".</p>
                </div>
            <?php endif ?>

            <ul class="nav nav-tabs my-custom-tabs mb-5">
                <li class="nav-item">
                    <a href="<?= BASE_URL . "/index.php?" . http_build_query($baseUrl + ["currentNavTab" => "projectNotes"]); ?>" 
                        class="nav-link <?= $currentNavTab === "projectNotes" ? "active" : "" ?>" aria-current="page">
                        Project Note
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= BASE_URL . "/index.php?" . http_build_query($baseUrl + ["currentNavTab" => "assignedSoloTask"]); ?>" 
                        class="nav-link <?= $currentNavTab === "assignedSoloTask" ? "active" : "" ?>">
                        Assigned Solo Task
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= BASE_URL . "/index.php?" . http_build_query($baseUrl + ["currentNavTab" => "assignedGroupTask"]); ?>" 
                        class="nav-link <?= $currentNavTab === "assignedGroupTask" ? "active" : "" ?>">
                    Assigned Group Task
                    </a>
                </li>
                <?php if ($currentUserSession["role"] !== "member" ): ?>
                    <li class="nav-item">
                        <a href="<?= BASE_URL . "/index.php?" . http_build_query($baseUrl + ["currentNavTab" => "submittedTask"]); ?>" 
                            class="nav-link <?= $currentNavTab === "submittedTask" ? "active" : "" ?>" >
                            Submitted Task
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= BASE_URL . "/index.php?" . http_build_query($baseUrl + ["currentNavTab" => "createTask"]); ?>" 
                            class="nav-link <?= $currentNavTab === "createTask" ? "active" : "" ?>" >
                            Create Task
                        </a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a href="<?= BASE_URL . "/index.php?" . http_build_query($baseUrl + ["currentNavTab" => "manageMembers"]); ?>" 
                        class="nav-link <?= $currentNavTab === "manageMembers" ? "active" : "" ?>" >Manage Members
                    </a>
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
        

        <?php if ($currentNavTab === "submittedTask" && $currentUserSession["role"] !== "member" ): ?>
            <?php require __DIR__ . "/projectSubmittedTask.view.php"; ?>
        <?php endif; ?>
        

        <?php if ($currentNavTab === "createTask" && $currentUserSession["role"] !== "member"): ?>
            <?php require __DIR__ . "/projectCreateTask.view.php"; ?>
        <?php endif ?>

        
        <?php if ($currentNavTab === "manageMembers"): ?>
            <?php require __DIR__ . "/projectManageMembers.view.php"; ?>
        <?php endif ?>
</div>


<script>
        const selectEl = document.getElementById("project-status");
        const changeProjectStatusMessageEl = document.getElementById("changeProjectStatusMessage");
        const notYetCompleteStatusMessageEl = document.getElementById("notYetCompleteStatusMessage");
        const currentProjectProgress = document.querySelector('button[data-bs-target="#updateProjectStatusModal"]').getAttribute('data-current-projectStatus');;
        const defaultSelectedOptionValue = selectEl.value;


        document.getElementById('updateProjectStatusModal').addEventListener('show.bs.modal', function (event) {  
            const selectedOptionValue = selectEl.value;

            if (selectedOptionValue === "completed" && currentProjectProgress !== "100%") {
                event.preventDefault();
                notYetCompleteStatusMessageEl.classList.remove('d-none');

                setTimeout(() => {
                    notYetCompleteStatusMessageEl.classList.add("d-none");
                }, 4500);
                return;
            }
            else {
                notYetCompleteStatusMessageEl.classList.add("d-none");
            } 
            
            if (defaultSelectedOptionValue === selectedOptionValue) {
                event.preventDefault();
                changeProjectStatusMessageEl.classList.remove('d-none');

                setTimeout(() => {
                    changeProjectStatusMessageEl.classList.add("d-none");
                }, 3000);
                return;
            }else {
                changeProjectStatusMessageEl.classList.add("d-none");
            } 
            
            const selectedHiddenInput = this.querySelector('#selectedProjectStatus');
            const defaultValueHiddenInput = this.querySelector("#defaultProjectStatus");
            
            selectedHiddenInput.value = selectedOptionValue;
            defaultValueHiddenInput.value = defaultSelectedOptionValue
        });

</script>

