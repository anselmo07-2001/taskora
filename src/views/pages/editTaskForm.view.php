<?php //var_dump($project); ?>

<div class="container custom-container">
    <?php require __DIR__ . "/../components/flashMessage.view.php" ?>
    <?php require __DIR__ . "/../components/backButton.view.php" ?>
    <div class="card custom-form-container">
        <div class="card-body p-5">
            <h1 class="text-center mb-5">Edit Task Information</h1>        
            <form method="POST" action=<?= BASE_URL . "/index.php?" . http_build_query(["page" => "editTask"]) ?> >
                <!-- hold the original value of the project (will be use to compare value) -->
                <input type="hidden" name="task" value="<?= e(json_encode($task)) ?>"/>
                <input type="hidden" name="redirectUrl" value="<?= $redirectUrl ?>"/>

                <div class="mb-4">
                    <label for="taskName" class="form-label">Task Name</label>
                    <input type="text" class="form-control <?= ($errors["taskNameErr"] ?? "") ? 'is-invalid' : ''; ?>" 
                            id="taskName" placeholder="Enter task name" name="taskName" value="<?= e($newTaskName ?? $task["taskname"]); ?>">
                    <?php if (!empty($errors["taskNameErr"] ?? null)): ?> 
                            <div class="invalid-feedback d-block mb-2" style="font-size: 0.75rem;">
                                   <?php echo $errors["taskNameErr"]; ?>
                            </div>
                    <?php endif; ?>
                </div>

                <div class="mb-4">
                    <label for="taskDescription" class="form-label">Task Description</label>
                    <textarea type="text" class="form-control <?= ($errors["taskDescriptionErr"] ?? "") ? 'is-invalid' : ''; ?>"
                             id="taskDescription" placeholder="Enter task description" name="taskDescription" style="height: 7rem;"><?= e($newTaskDescription ?? $task["task_description"]); ?></textarea>
                    <?php if (!empty($errors["taskDescriptionErr"] ?? null)): ?> 
                            <div class="invalid-feedback d-block mb-2" style="font-size: 0.75rem;">
                                   <?php echo $errors["taskDescriptionErr"]; ?>
                            </div>
                    <?php endif; ?>
                </div>

                 <div class="mb-5">
                        <label for="taskDeadline" class="form-label">Task Deadline</label>
                        <div class="input-group mb-3">
                            <input type="date" class="form-control <?= ($errors["taskDeadlineErr"] ?? "") ? 'is-invalid' : ''; ?>" id="taskDeadline" placeholder="Enter a deadline" name="taskDeadline" value="<?= e($newTaskDeadline ?? $task["deadline"]); ?>"/>
                            <span class="input-group-text p-2 my-bg-iconform-color-primary border-start-0" href="#" id="calendar-icon" style="cursor: pointer;">
                                <img src="./public/images/calendar.png" alt="icon" style="width:20px; height:20px; filter: invert(1);">
                            </span>
                            <?php if (!empty($errors["taskDeadlineErr"] ?? null)): ?> 
                                <div class="invalid-feedback d-block mb-2" style="font-size: 0.75rem;">
                                    <?php echo $errors["taskDeadlineErr"]; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                </div>

                <div class="d-grid mb-2">
                    <button type="submit" class="btn btn-success">Update Task</button>
                </div>

                <div class="d-grid">
                    <a href="<?php echo BASE_URL . "/index.php?page=tasks"; ?>" class="btn btn-danger">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
        document.getElementById('calendar-icon').addEventListener('click', function() {
            document.getElementById('taskDeadline').showPicker();
        });
</script>