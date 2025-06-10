<?php // var_dump($currentUserSession); ?>

<div class="container custom-container">
        <div class="card custom-form-container">
            <div class="card-body p-5">
                <h1 class="text-center mb-5">Create a Project</h1>
                
                <form method="POST" action="<?php echo BASE_URL . "/index.php?page=createProject"; ?>">
                    <div class="mb-4">
                        <label for="projectName" class="form-label">Project Name</label>
                        <input type="text" class="form-control <?= ($errors["projectNameErr"] ?? "") ? 'is-invalid' : ''; ?>" id="projectName" placeholder="Enter project name" name="projectName" value="<?php echo e($_POST["projectName"] ?? ""); ?>">
                        <?php if (!empty($errors["projectNameErr"] ?? null)): ?> 
                            <div class="invalid-feedback d-block mb-2" style="font-size: 0.75rem;">
                                   <?php echo $errors["projectNameErr"]; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-4">
                        <label for="projectDescription" class="form-label">Project Description</label>
                        <textarea type="text" class="form-control <?= ($errors["projectDescriptionErr"] ?? "") ? 'is-invalid' : ''; ?>" id="projectDescription" style="height: 10rem;"
                                   placeholder="Enter project description" name="projectDescription"><?php echo e($_POST["projectDescription"] ?? ""); ?></textarea>
                        <?php if (!empty($errors["projectDescriptionErr"] ?? null)): ?> 
                            <div class="invalid-feedback d-block mb-2" style="font-size: 0.75rem;">
                                   <?php echo $errors["projectDescriptionErr"]; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-5">
                        <label for="projectDeadline" class="form-label">Project Deadline</label>
                        <div class="input-group mb-3">
                            <input type="date" class="form-control <?= ($errors["projectDeadlineErr"] ?? "") ? 'is-invalid' : ''; ?>" id="projectDeadline" placeholder="Enter a deadline" name="projectDeadline" value="<?php echo e($_POST["projectDeadline"] ?? ""); ?>"/>
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

                    <div class="mb-4">
                        <label for="assignedProjectManager" class="form-label">Assigned Project Manager</label>
                        <?php if ($currentUserSession["role"] === "project_manager"): ?>
                            <input type="hidden" name="assignedProjectManager" value="<?= e($currentUserSession["userId"]); ?>">
                        <?php endif; ?>

                        <select <?= $currentUserSession["role"] === "project_manager" ? "disabled" : 'name="assignedProjectManager"' ?>
                                class="form-select 
                                    <?= ($errors["assignedProjectManagerErr"] ?? "") && $currentUserSession["role"] !== "project_manager" 
                                        ? 'is-invalid' : ''; ?>" id="assignedProjectManager" 
                                    >

                            <?php if ($currentUserSession["role"] === "project_manager"): ?>
                                <option value="<?php echo e($currentUserSession["userId"]);?>" selected> 
                                        <?php echo e($currentUserSession["fullname"]);?>
                                </option>
                            <?php endif; ?>   

                            <?php if ($currentUserSession["role"] !== "project_manager"): ?>
                                <option selected disabled></option>
                                <?php foreach($projectManagers AS $projectManager): ?>
                                    <option value="<?php echo e($projectManager["id"]);?>" 
                                            <?php echo (($_POST["assignedProjectManager"] ?? "") == $projectManager["id"]) ? 'selected' : ''; ?>> 
                                        <?php echo e($projectManager["fullname"]);?>
                                    </option>
                                <?php endforeach; ?>

                            <?php endif; ?>

                        </select>
                        <?php if (!empty($errors["assignedProjectManagerErr"] ?? null) && $currentUserSession["role"] !== "project_manager"): ?> 
                                <div class="invalid-feedback d-block mb-2" style="font-size: 0.75rem;">
                                    <?php echo $errors["assignedProjectManagerErr"]; ?>
                                </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-4">
                        <label for="assignedMembers" class="form-label">Assigned Members</label>
                        <select class="form-select selectpicker <?= ($errors["assignedMembersErr"] ?? "") ? 'is-invalid' : ''; ?>" id="assignedMembers" name="assignedMembers[]" multiple data-live-search="true" data-width="100%">
                            <?php foreach($members AS $member): ?>
                                <option value="<?php echo $member["id"];?>" <?= (in_array($member["id"], $_POST["assignedMembers"] ?? [])) ? 'selected' : '' ?>>
                                    <?php echo $member["fullname"]; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (!empty($errors["assignedMembersErr"] ?? null)): ?> 
                                <div class="invalid-feedback d-block mb-2" style="font-size: 0.75rem;">
                                    <?php echo $errors["assignedMembersErr"]; ?>
                                </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-5">
                        <label for="projectNote" class="form-label">Project Note</label>
                        <textarea type="text" class="form-control <?= ($errors["projectNoteErr"] ?? "") ? 'is-invalid' : ''; ?>" id="projectNote" style="height: 10rem;"
                                  placeholder="Enter project note" name="projectNote"><?php echo e($_POST["projectNote"] ?? ""); ?></textarea>
                        <?php if (!empty($errors["projectNoteErr"] ?? null)): ?> 
                                <div class="invalid-feedback d-block mb-2" style="font-size: 0.75rem;">
                                    <?php echo $errors["projectNoteErr"]; ?>
                                </div>
                        <?php endif; ?>
                    </div>

                    <div class="d-grid mb-2">
                        <button type="submit" name="action" value="save" class="btn btn-success">Save Project</button>
                    </div>

                    <div class="d-grid">
                        <a href="<?php echo BASE_URL . "/index.php?page=home"; ?>" class="btn btn-danger">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
        document.getElementById('calendar-icon').addEventListener('click', function() {
            document.getElementById('projectDeadline').showPicker();
        });
</script>