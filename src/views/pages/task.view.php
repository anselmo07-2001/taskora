<?php // var_dump($task); die ?>

<div class="container custom-container">
    <div class="mb-5">
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
            <h6 class="mb-3">Add Task Note</h6>
            <form>
                <textarea style="height: 10rem;" class="w-100 form-control mb-3" rows="4" placeholder="Enter your project note here" name="tasknote"></textarea>
            </form>
            <div class="d-flex justify-content-end">
                <button class="btn custom-primary-btn">Save Project Note</button>
            </div>  
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
                                        <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editTaskNoteModal" 
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

