<?php // var_dump($projectManagers[0]); ?>

<div class="container custom-container">
        <div class="card custom-form-container">
            <div class="card-body p-5">
                <h1 class="text-center mb-5">Create a Project</h1>
                
                <form method="POST" action="<?php echo BASE_URL . "/index.php?page=createProject"; ?>">
                    <div class="mb-4">
                        <label for="projectName" class="form-label">Project Name</label>
                        <input type="text" class="form-control" id="projectName" placeholder="Enter project name" name="projectName">
                    </div>

                    <div class="mb-4">
                        <label for="projectDescription" class="form-label">Project Description</label>
                        <textarea type="text" class="form-control" id="projectDescription" style="height: 10rem;"
                                   placeholder="Enter project description" name="projectDescription"></textarea>
                    </div>

                    <div class="mb-5">
                        <label for="projectDeadline" class="form-label">Project Deadline</label>
                        <div class="input-group mb-3">
                            <input type="date" class="form-control" id="projectDeadline" placeholder="Enter a deadline" name="projectDeadline"/>
                            <span class="input-group-text p-2 my-bg-iconform-color-primary border-start-0" href="#" id="calendar-icon" style="cursor: pointer;">
                                <img src="./public/images/calendar.png" alt="icon" style="width:20px; height:20px; filter: invert(1);">
                            </span>
                        </div>
                    </div>            

                    <div class="mb-4">
                        <label for="assignedProjectManager" class="form-label">Assigned Project Manager</label>
                        <select class="form-select" id="assignedProjectManager" name="assignedProjectManager">
                            <option selected disabled></option>
                            <?php foreach($projectManagers AS $projectManager): ?>
                                 <option value="<?php echo e($projectManager["id"]); ?>"><?php echo e($projectManager["fullname"]); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="assignedMembers" class="form-label">Assigned Members</label>
                        <select class="form-select selectpicker" id="assignedMembers" name="assignedMembers[]" multiple data-live-search="true" data-width="100%">
                            <?php foreach($members AS $member): ?>
                                 <option value="<?php echo $member["id"]; ?>"><?php echo $member["fullname"]; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-5">
                        <label for="projectNote" class="form-label">Project Note</label>
                        <textarea type="text" class="form-control" id="projectNote" style="height: 10rem;"
                                  placeholder="Enter project note" name="projectNote"></textarea>
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