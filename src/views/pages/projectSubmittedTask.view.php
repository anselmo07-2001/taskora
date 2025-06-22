<?php require __DIR__ . "/../components/modal.view.php";  ?>
<?php //var_dump($tabData["submittedTask"]); ?>

<?php 
    echo renderModal([
        "id" => "approveTaskModal",
        "title" => "Approve the task",
        "action" => BASE_URL . "/index.php?page=approveTask&" . 
           http_build_query(["projectId" => $baseUrl["projectId"], "currentNavTab" => "submittedTask", "currentPaginationPage" => $baseUrl["currentPaginationPage"]]),
        "textareaLabel" => "Add your Task Note",
        "textareaName" => "approvedTaskNote",
        "modalTextAreaEl" => "approvedTaskNote",
        "submitText" => "Approved Task",
        "hiddenFields" => [
            [  "name" => "taskId", "id" => "approveModalTaskId" ],
        ]
    ]);
?>

<?php 
    echo renderModal([
        "id" => "rejectTaskModal",
        "title" => "Reject the task",
        "action" => BASE_URL . "/index.php?page=rejectTask&" .
           http_build_query(["projectId" => $baseUrl["projectId"], "currentNavTab" => "submittedTask", "currentPaginationPage" => $baseUrl["currentPaginationPage"]]),
        "textareaLabel" => "Add your Task Note",
        "textareaName" => "rejectTaskNote",
        "modalTextAreaEl" => "rejectTaskNote",
        "submitText" => "Reject Task",
        "btnSubmit" => "btn-danger",
        "hiddenFields" => [
            [  "name" => "taskId", "id" => "rejectModalTaskId" ],
        ]
    ]);
?>



<div class="modal fade" id="approveTaskModal" tabindex="-1" aria-labelledby="approveTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <form action="approve-task.php" method="POST">
            <div class="modal-header">
            <h5 class="modal-title" id="approveTaskModalLabel">Approve this Task?</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <div class="mb-3">
                <label for="approveReason" class="form-label">Reason for Approving this Task</label>
                <textarea class="form-control" id="approveReason" name="approveReason" rows="3" required></textarea>
            </div>
                <input type="hidden" name="task_id" value="123">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success">Approve Task</button>
            </div>
        </form>
        </div>
    </div>
</div>
    
<div class="modal fade" id="rejectTaskModal" tabindex="-1" aria-labelledby="rejectTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <form action="reject-task.php" method="POST">
            <div class="modal-header">
            <h5 class="modal-title" id="rejectTaskModalLabel">Reject this Task?</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <div class="mb-3">
                <label for="rejectReason" class="form-label">Reason for Rejecting this Task</label>
                <textarea class="form-control" id="rejectReason" name="rejectReason" rows="3" required></textarea>
            </div>
                <input type="hidden" name="task_id" value="123">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">Reject Task</button>
            </div>
        </form>
        </div>
    </div>
</div>

<h6 class="text-muted">Total Submitted Tasks: <?= count($tabData["submittedTask"]);?></h6>
<div class="mb-3 d-flex justify-content-between">
    <div class="d-flex align-items-center gap-2">
        <button href="#" class="btn custom-primary-btn filter-form-btn">Due Today</button>
        <button href="#" class="btn custom-primary-btn filter-form-btn">Overdue</button>
        <button href="#" class="btn custom-primary-btn filter-form-btn">Upcoming</button>
    </div>
    <form class="d-flex gap-2">
        <input type="text" class="form-control" name="searchProject" placeholder="Search Task | Member">
        <button class="btn custom-primary-btn filter-form-btn">
            <img src="./public/images/magnifying-glass.png" alt="icon" style="width:15px; height:15px; filter: invert(1);">
        </button>
    </form>
</div>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th scope="col">Id</th>
            <th scope="col">Task</th>
            <th scope="col">Task Type / Members</th>
            <th scope="col">Assigned Date</th>
            <th scope="col">Deadline</th>
            <th scope="col">Deadline Status</th>
            <th scope="col">Milestone</th>
            <th scope="col">Approval Status</th>
            <th scope="col">Action</th>
        </tr>
    </thead> 
    <tbody>
        <?php foreach($tabData["submittedTask"] as $task): ?>
            <tr>
                <th scope="row"><?= e($task["id"]); ?></th>
                <td><?= e($task["task"]); ?></td>
                <td><?= e($task["task_type_and_members"]); ?></td>
                <td><?= e($task["assigned_date"]); ?></td>
                <td><?= e($task["deadline"]); ?></td>
                <td><?= e($task["deadline_status"]); ?></td>
                <td><?= e($task["milestone"]); ?></td>
                <td><?= e($task["approval_status"]); ?></td>
                <td>
                    <button class="btn btn-sm btn-success my-manage-btn mb-2 <?= $task["approval_status"] === "Approved" ? "disabled" : "" ?>" 
                            <?= $task["approval_status"] === "Approved" ? "disabled" : "" ?>
                            data-bs-toggle="modal" data-bs-target="#approveTaskModal" data-taskid="<?= e($task["id"]); ?>">Approved
                    </button>
                    <button class="btn btn-sm btn-danger my-manage-btn mb-2 <?= $task["approval_status"] === "Rejected" ? "disabled" : "" ?>"
                            <?= $task["approval_status"] === "Rejected" ? "disabled" : "" ?> 
                            data-bs-toggle="modal" data-bs-target="#rejectTaskModal" data-taskid="<?= e($task["id"]); ?>">Rejected
                    </button>
                    <a href="<?= BASE_URL . "/index.php?" . http_build_query(["page" => "taskPanel", "taskId" => e($task["id"]) ]) ?>" 
                       class="btn custom-primary-btn my-manage-btn mb-2" >Manage</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
   let taskId;

   document.getElementById("approveTaskModal").addEventListener('show.bs.modal', function (event) {
        taskId = event.relatedTarget.getAttribute("data-taskid");
   })
   document.getElementById("approveTaskModal").addEventListener('submit', function (e) {       
        $hiddenTaskIdEl = document.getElementById("approveModalTaskId");
        $hiddenTaskIdEl.value = taskId
   });

   document.getElementById("rejectTaskModal").addEventListener('show.bs.modal', function (event) {
        taskId = event.relatedTarget.getAttribute("data-taskid");
   })
   document.getElementById("rejectTaskModal").addEventListener('submit', function (e) {       
        $hiddenTaskIdEl = document.getElementById("rejectModalTaskId");
        $hiddenTaskIdEl.value = taskId
   });

</script>