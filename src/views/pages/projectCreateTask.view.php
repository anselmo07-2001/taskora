<?php // var_dump($previousInput ?? ""); ?>

<div class="card custom-form-container">
    <div class="card-body p-5">
        <h1 class="text-center mb-5">Create a Task</h1>
        
        <form method="POST" action="<?= BASE_URL . "/index.php?" . http_build_query(["page" => "createTask", "currentNavTab" => "createTask"] + $baseUrl); ?>">
            <div class="mb-4">
                <label for="taskname" class="form-label">Task Name</label>
                <input type="text" class="form-control <?= ($errors["taskNameErr"] ?? "") ? 'is-invalid' : ''; ?>" 
                        id="taskname" placeholder="Enter task name" name="taskName" value="<?= $previousInput["taskName"] ?? "" ?>">
                <?php if (!empty($errors["taskNameErr"] ?? null)): ?> 
                    <div class="invalid-feedback d-block mb-2" style="font-size: 0.75rem;">
                        <?php echo $errors["taskNameErr"]; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-4">
                <label for="taskDescription" class="form-label">Task Description</label>
                <textarea type="text" class="form-control <?= ($errors["taskDescriptionErr"] ?? "") ? 'is-invalid' : ''; ?>" id="taskDescription" style="height: 10rem;"
                        placeholder="Enter task description" name="taskDescription"><?= $previousInput["taskDescription"] ?? "" ?></textarea>
                <?php if (!empty($errors["taskDescriptionErr"] ?? null)): ?> 
                    <div class="invalid-feedback d-block mb-2" style="font-size: 0.75rem;">
                        <?php echo $errors["taskDescriptionErr"]; ?>
                    </div>
                <?php endif; ?>
            </div>

            
            <div class="mb-5">
                <label for="taskDeadline" class="form-label">Task Deadline</label>
                <div class="input-group mb-3">
                    <input type="date" class="form-control <?= ($errors["taskDescriptionErr"] ?? "") ? 'is-invalid' : ''; ?>" 
                                id="taskDeadline" placeholder="Enter a deadline" name="taskDeadline" value="<?= $previousInput["taskDeadline"] ?? "" ?>"/>
                    <span class="input-group-text p-2 my-bg-iconform-color-primary border-start-0" href="#" id="calendar-icon" style="cursor: pointer;">
                        <img src="./public/images/calendar.png" alt="icon" style="width:20px; height:20px; filter: invert(1);">
                    </span>
                </div>
                <?php if (!empty($errors["taskDeadlineErr"] ?? null)): ?> 
                    <div class="invalid-feedback d-block mb-2" style="font-size: 0.75rem;">
                        <?php echo $errors["taskDeadlineErr"]; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-5">
                <label for="tasktype" class="form-label">Task Type</label>
                <select class="form-select <?= ($errors["taskTypeErr"] ?? "") ? 'is-invalid' : ''; ?>" 
                            id="tasktype" name="taskType">
                    <option value="" selected disabled>Choose Task Type</option>
                    <option value="solo" <?= ( $previousInput["taskType"] ?? "" ) === "solo" ? "selected" : ""; ?>>Solo Task</option>
                    <option value="group" <?= ( $previousInput["taskType"] ?? "" ) === "group" ? "selected" : ""; ?>>Group Task</option>
                </select>
                <?php if (!empty($errors["taskTypeErr"] ?? null)): ?> 
                    <div class="invalid-feedback d-block mb-2" style="font-size: 0.75rem;">
                        <?php echo $errors["taskTypeErr"]; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-4">
                <label for="assignMembers" class="form-label">Assign Members</label>
                <select class="form-select selectpicker <?= ($errors["assignedMembersErr"] ?? "") ? 'is-invalid' : ''; ?>" 
                            name="assignedMembers[]" multiple data-live-search="true" data-width="100%" title="Select members">           
                    <?php foreach($tabData["projectMembers"] AS $member): ?>
                        <option value="<?php echo e($member["id"]);?>"
                                <?= in_array($member["id"], $previousInput["assignedMembers"] ?? []) ? "selected" : "" ?>>
                            <?php echo e($member["fullname"]);?>
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
                <label for="taskNote" class="form-label">Task Note</label>
                <textarea type="text" class="form-control <?= ($errors["taskNoteErr"] ?? "") ? 'is-invalid' : ''; ?>" id="taskNote" style="height: 10rem;"
                        placeholder="Enter task note" name="taskNote"><?= $previousInput["taskNote"] ?? "" ?></textarea>
                <?php if (!empty($errors["taskNoteErr"] ?? null)): ?> 
                    <div class="invalid-feedback d-block mb-2" style="font-size: 0.75rem;">
                        <?php echo $errors["taskNoteErr"]; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="d-grid mb-2">
                <button type="submit" class="btn btn-success">Save Project</button>
            </div>

            <div class="d-grid">
                <button type="button" class="btn btn-danger">Cancel</button>
            </div>
        </form>
    </div>
</div> 

<script>
        document.getElementById('calendar-icon').addEventListener('click', function() {
            document.getElementById('taskDeadline').showPicker();
        });
</script>