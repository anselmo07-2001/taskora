<?php //var_dump($project); ?>

<div class="container custom-container">
    <?php require __DIR__ . "/../components/flashMessage.view.php" ?>
    <?php require __DIR__ . "/../components/backButton.view.php" ?>
    <div class="card custom-form-container">
        <div class="card-body p-5">
            <h1 class="text-center mb-5">Edit Project Information</h1>        
            <form method="POST" action=<?= BASE_URL . "/index.php?" . http_build_query(["page" => "editProject"]) ?> >
                <!-- hold the original value of the project (will be use to compare value) -->
                <input type="hidden" name="project" value="<?= e(json_encode($project)) ?>"/>
                <div class="mb-4">
                    <label for="projectName" class="form-label">Project Name</label>
                    <input type="text" class="form-control <?= ($errors["projectNameErr"] ?? "") ? 'is-invalid' : ''; ?>" 
                            id="projectName" placeholder="Enter project name" name="projectName" value="<?= e($newProjectName ?? $project["name"]); ?>">
                    <?php if (!empty($errors["projectNameErr"] ?? null)): ?> 
                            <div class="invalid-feedback d-block mb-2" style="font-size: 0.75rem;">
                                   <?php echo $errors["projectNameErr"]; ?>
                            </div>
                    <?php endif; ?>
                </div>

                <div class="mb-4">
                    <label for="projectDescription" class="form-label">Project Description</label>
                    <textarea type="text" class="form-control <?= ($errors["projectDescriptionErr"] ?? "") ? 'is-invalid' : ''; ?>"
                             id="projectDescription" placeholder="Enter project description" name="projectDescription" style="height: 7rem;"><?= e($newProjectDescription ?? $project["project_description"]); ?></textarea>
                    <?php if (!empty($errors["projectDescriptionErr"] ?? null)): ?> 
                            <div class="invalid-feedback d-block mb-2" style="font-size: 0.75rem;">
                                   <?php echo $errors["projectDescriptionErr"]; ?>
                            </div>
                    <?php endif; ?>
                </div>

                 <div class="mb-5">
                        <label for="projectDeadline" class="form-label">Project Deadline</label>
                        <div class="input-group mb-3">
                            <input type="date" class="form-control <?= ($errors["projectDeadlineErr"] ?? "") ? 'is-invalid' : ''; ?>" id="projectDeadline" placeholder="Enter a deadline" name="projectDeadline" value="<?= e($newProjectDeadline ?? $project["deadline"]);  ?>"/>
                            <span class="input-group-text p-2 my-bg-iconform-color-primary border-start-0" href="#" id="calendar-icon" style="cursor: pointer;">
                                <img src="./public/images/calendar.png" alt="icon" style="width:20px; height:20px; filter: invert(1);">
                            </span>
                            <?php if (!empty($errors["projectDeadlineErr"] ?? null)): ?> 
                                <div class="invalid-feedback d-block mb-2" style="font-size: 0.75rem;">
                                    <?php echo $errors["projectDeadlineErr"]; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                </div>

                <div class="d-grid mb-2">
                    <button type="submit" class="btn btn-success">Update Projects</button>
                </div>

                <div class="d-grid">
                    <a href="<?php echo BASE_URL . "/index.php?page=projects"; ?>" class="btn btn-danger">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
        document.getElementById('calendar-icon').addEventListener('click', function() {
            document.getElementById('projectDeadline').showPicker();
        });
</script>