<?php // var_dump($errors); die; ?>

<div class="container custom-container">
    <?php require __DIR__ . "/../components/flashMessage.view.php" ?>

    <?php
    //EDIT TASK MODAL
        require __DIR__ . "/../components/modal.view.php"; 
        echo renderModal([
            "id" => "editTaskNoteModal",
            "title" => "Edit task note",
            "action" => BASE_URL . "/index.php?page=editTaskNote",
            "textareaLabel" => "Edit your note",
            "textareaName" => "editTaskNoteTextArea",
            "submitText" => "Save Task",
            "hiddenFields" => [
                [  "name" => "taskNoteId", "id" => "modalTaskNoteId" ],
                [  "name" => "taskId", "id" => "modalTaskId" ],
            ]
        ]);
    ?>


    <?php
    //EDIT TASK STATUS MODAL
        echo renderModal([
            "id" => "editTaskStatusModal",
            "title" => "Update the task status?",
            "action" => BASE_URL . "/index.php?page=editTaskStatus",
            "textareaLabel" => "Add a Task Note",
            "textareaName" => "taskStatusNote",
            "submitText" => "Update Task",
            "hiddenFields" => [   
                   [ "name" => "previousTaskStatus", "id" => "modalEditPreviousTaskStatus"],
                   [ "name" => "newTaskStatus", "id" => "modalEditNewTaskStatus"],
                   [ "name" => "taskId", "id" => "modalEditTaskId"]
            ]
        ]);
    ?>

    <div class="mb-4">
        <div class="d-flex align-items-center gap-2">
            <img src="./public/images/scope.png" class="mytask-title-icon"/>
            <h6><?= e($task["project_name"]); ?></h6>
        </div>
        <h1 class="mb-4"><?= e($task["task_name"]); ?></h1>

        <h5 class="lh-sm">Objective</h5>
        <p class="fs-5 mb-4"><?= e($task["task_description"]); ?></p>
        <div class="mb-4">
                <ul class="list-unstyled">
                    <li>Task type: <span><?= ucfirst(($task["tasktype"])); ?></span></li>
                    <?php if($task["tasktype"] === "group"): ?>
                        <li>Total Number of Assigned Member: <span><?= e($task["total_assigned_members"]); ?></span></li>
                    <?php endif; ?>
                    <?php if($task["tasktype"] === "solo"): ?>
                        <li>Assigned To: <span><?= e($task["assigned_to"]); ?></span></li>
                    <?php endif; ?>
                    <li>Project Manager: <span><?= e($task["assigned_by"]); ?></span></li>
                    <li>Deadline: <span><?= e($task["task_deadline"]); ?></span></li>
                    <li>Milestone: <span><?= e($task["task_due_status"]); ?></span></li>
                    <li>Current Project Status: <span><?= e($task["current_task_status"]); ?></span></li>
                </ul>
        </div>
    </div>


    <div class="mb-5">      
        <div class="d-flex align-items-center gap-3 mb-2">
            <label for="taskStatus" class="form-label">Change the task status:</label>
            <select class="form-select w-25" id="taskStatus" style="margin-right: -0.5rem;">
                <option value="pending" <?= $task["current_task_status"] === "pending" ? "selected" : "" ?> >Pending</option>
                <option value="in_progress" <?= $task["current_task_status"] === "in_progress" ? "selected" : "" ?> >In progress</option>
                <option value="completed" <?= $task["current_task_status"] === "completed" ? "selected" : "" ?> >Completed</option>
            </select>
            <button class="btn custom-primary-btn" data-bs-toggle="modal" data-bs-target="#editTaskStatusModal" data-taskId="<?= e($task["task_id"]); ?>" >Update Task</button>   
        </div>
        <small id="editTaskStatusErrorMsg" class="text-danger d-none">Please change the task status before updating</small>
    </div>

    <div class="mb-5">
            <h6 class="mb-3">Add Task Note</h6>
            <form method="POST" action="<?= BASE_URL . "/index.php?" . http_build_query(["page" => "createTaskNote"]) ?>">
                <input type="hidden" name="userId" value="<?= $currentUserSession["userId"] ?>">
                <input type="hidden" name="taskId" value="<?= $task["task_id"] ?>">
                <textarea style="height: 10rem;" class="w-100 form-control mb-2 <?= ($errors["taskNoteFormErr"] ?? "") ? 'is-invalid' : ''; ?>" rows="4" placeholder="Enter your project note here" name="content"></textarea>
                <div class="d-flex justify-content-between">
                    <div class="me-3 flex-grow-1">
                        <?php if (!empty($errors["taskNoteFormErr"] ?? null)): ?> 
                            <div class="invalid-feedback d-block" style="font-size: 0.75rem;">
                                <?php echo $errors["taskNoteFormErr"]; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex align-items-start">
                        <button class="btn custom-primary-btn align-self-start">Save Task Note</button>
                    </div>
                </div> 
            </form>
    </div>

    <div class="text-muted mb-2 ">Total Task Note: <?= count($task["task_notes"]); ?></div>

    <?php foreach($task["task_notes"] as $tasknote): ?>
        
        <div class="card mb-3">
            <div class="card-body position-relative">
                <div class="d-flex align-items-start">
                        <img src="./public/images/usernote.png" class="rounded-circle me-3" alt="User avatar" style="height:3rem;">
                        <div>
                            <h6 class="mb-0"><?= e($tasknote["note_author"]); ?><sup><?= $tasknote["role"] !== "admin" ? ' (' . $tasknote["role"] . ')' : "" ?></sup></h6>
                            <small class="text-muted"><?= e($tasknote["tasknote_type"]) . " on " . date("M d, Y, \a\\t h:i A", strtotime($tasknote["note_created_at"])); ?> </small>
                            <?php if ($tasknote["note_created_at"] !== $tasknote["note_edited_at"]): ?>
                                <small class="text-muted d-block">Last content modified <?= (new DateTime($tasknote["note_edited_at"]))->format('M d, Y, \a\t h:i A'); ?></small>
                            <?php endif; ?>
                            <?php if (e($tasknote["tasknote_type"]) === "Update task status"): ?>
                                    <?php $content = preg_replace('/^(\[.*?\])/', '<strong>$1</strong>', e($tasknote["note_content"])); ?>
                                    <p class="mt-2 mb-0">
                                        <?= $content ?>
                                    </p>
                            <?php else: ?>
                                <p class="mt-2 mb-0"> <?= e($tasknote["note_content"]); ?></p>  
                            <?php endif; ?>  
                        </div>

                        <?php if($currentUserSession["userId"] === $tasknote["creator_id"] || $currentUserSession["role"] === "project_manager" || $currentUserSession["role"] === "admin"): ?>
                            <div class="dropdown position-absolute top-0 end-0 me-2 mt-2">
                                <button class="btn p-0 border-0 bg-transparent" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="d-flex flex-column align-items-center justify-content-center" style="width: 20px; height: 30px;">
                                        <span class="bg-secondary rounded-circle" style="width: 4px; height: 4px; margin: 2px 0;"></span>
                                        <span class="bg-secondary rounded-circle" style="width: 4px; height: 4px; margin: 2px 0;"></span>
                                        <span class="bg-secondary rounded-circle" style="width: 4px; height: 4px; margin: 2px 0;"></span>
                                    </div>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editTaskNoteModal" data-note-taskId="<?= e($task["task_id"]); ?>"
                                            data-note-id="<?= e($tasknote["note_id"]); ?>" data-note-text="<?= e($tasknote["note_content"]); ?>" data-note-type="<?= e($tasknote["tasknote_type"]); ?>" >
                                            Edit
                                        </button>
                                    </li>
                                    <li>
                                        <form method="POST" action="<?= BASE_URL . "/index.php?" . http_build_query(["page" => "deleteTaskNote"])?>">
                                            <input type="hidden" name="projectNoteId" value="<?= e($tasknote["note_id"]); ?>">
                                            <button type="submit" class="dropdown-item">Delete</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>  
                        <?php endif; ?>
                </div>
            </div>
        </div>

    <?php endforeach; ?>

   



    <div class="d-flex justify-content-end">
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1">Previous</a>
                </li>
                <li class="page-item active">
                    <a class="page-link page-link-mycolor" href="#">1 <span class="visually-hidden">(current)</span></a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="#">2</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="#">3</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="#">4</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="#">5</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="#">Next</a>
                </li>
            </ul>
        </nav>
    </div>
<div>


<script>

    document.addEventListener("DOMContentLoaded", function () {
        const editModal = document.getElementById("editTaskNoteModal");
        const editModalTextAreaEl = document.getElementById("modalTextAreaEl");
        const messageLabel = document.getElementById("modalMessage");
           
        let originalTaskNote = "";

        editModal.addEventListener('show.bs.modal', function (event) {  
            const button = event.relatedTarget;
            const noteText = button.getAttribute('data-note-text');
            const noteId = button.getAttribute('data-note-id');
            const taskId = button.getAttribute('data-note-taskId');
            const noteType = button.getAttribute('data-note-type');
            const modalTaskNoteIdHiddenEl = document.getElementById("modalTaskNoteId");
            const modalTaskIdHiddenEl = document.getElementById("modalTaskId");
            
            originalTaskNote = noteText;
            modalTaskNoteIdHiddenEl.value = noteId;
            modalTaskIdHiddenEl.value = taskId;
        
            if (noteType !== "Update project status") {
                 editModalTextAreaEl.value = noteText;
            }
        });


        editModal.addEventListener('submit', function (e) {            
            if (editModalTextAreaEl.value.trim() === originalTaskNote) {
                e.preventDefault();
                messageLabel.classList.remove('d-none');
            }
        });

        editModal.addEventListener('hidden.bs.modal', function () {
            messageLabel.classList.add('d-none'); // Also hide the message when modal is closed
        });


        //////////////////// EDIT TASK STATUS MODAL /////////////////////////

        const selectEl = document.getElementById("taskStatus");
        const defaultSelectedOptionValue = selectEl.value;
        const editTaskStatusErrorMsg = document.getElementById("editTaskStatusErrorMsg");

        document.getElementById("editTaskStatusModal").addEventListener("show.bs.modal", function(event) {
            const selectedOptionValue = selectEl.value;
            const button = event.relatedTarget;
            const taskId = button.dataset.taskid;
             
             if (defaultSelectedOptionValue === selectedOptionValue) {
                event.preventDefault();
                editTaskStatusErrorMsg.classList.remove('d-none');

                setTimeout(() => {
                    editTaskStatusErrorMsg.classList.add("d-none");
                }, 3000);
                return;
            }else {
                editTaskStatusErrorMsg.classList.add("d-none");
            } 

            const newSelectedTaskStatus = this.querySelector('#modalEditNewTaskStatus');
            const previousTaskStatus = this.querySelector("#modalEditPreviousTaskStatus");
            const modalEditTaskId = this.querySelector("#modalEditTaskId");
            
            
            newSelectedTaskStatus.value = selectedOptionValue;
            previousTaskStatus.value = defaultSelectedOptionValue;
            modalEditTaskId.value = taskId;
        });

    });


</script>





