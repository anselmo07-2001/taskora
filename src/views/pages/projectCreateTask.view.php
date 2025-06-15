<?php var_dump($tabData); ?>

<div class="card custom-form-container">
    <div class="card-body p-5">
        <h1 class="text-center mb-5">Create a Task</h1>
        
        <form method="POST" action="<?= BASE_URL . "/index.php?" . http_build_query(["page" => "createTask"] + $baseUrl); ?>">
            <div class="mb-4">
                <label for="taskname" class="form-label">Task Name</label>
                <input type="text" class="form-control" id="taskname" placeholder="Enter task name" name="taskname">
            </div>

            <div class="mb-4">
                <label for="taskDescription" class="form-label">Task Description</label>
                <textarea type="text" class="form-control" id="taskDescription" style="height: 10rem;"
                        placeholder="Enter task description" name="taskDescription"></textarea>
            </div>

            
            <div class="mb-5">
                <label for="taskDeadline" class="form-label">Task Deadline</label>
                <div class="input-group mb-3">
                    <input type="date" class="form-control" id="taskDeadline" placeholder="Enter a deadline" name="taskDeadline"/>
                    <span class="input-group-text p-2 my-bg-iconform-color-primary border-start-0" href="#" id="calendar-icon" style="cursor: pointer;">
                        <img src="./public/images/calendar.png" alt="icon" style="width:20px; height:20px; filter: invert(1);">
                    </span>
                </div>
            </div>

            <div class="mb-5">
                <label for="tasktype" class="form-label">Task Type</label>
                <select class="form-select" id="tasktype" name="taskType" required>
                    <option value="" selected disabled>Choose Task Type</option>
                    <option value="soloTask">Solo Task</option>
                    <option value="groupTask">Group Task</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="assignMembers" class="form-label">Assign Members</label>
                <select class="form-select">    
                    <option value="" disabled selected>Select a member</option>      
                    <?php foreach($tabData["projectMembers"] AS $member): ?>
                        <option value="<?php echo e($member["id"]);?>">
                            <?php echo e($member["fullname"]);?>
                        </option>
                    <?php endforeach; ?>      
                </select>
            </div>

            <div class="mb-5">
                <label for="taskNote" class="form-label">Task Note</label>
                <textarea type="text" class="form-control" id="taskNote" style="height: 10rem;"
                        placeholder="Enter task note" name="taskNote"></textarea>
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