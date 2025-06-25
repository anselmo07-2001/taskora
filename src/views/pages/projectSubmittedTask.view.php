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


<h6 class="text-muted">Total Submitted Tasks: <?= count($tabData["submittedTask"]);?></h6>
<div class="mb-3 d-flex justify-content-between">
    <div class="d-flex align-items-center gap-2">
        <a href="<?= BASE_URL . "/index.php?" . http_build_query($baseUrl + ["currentNavTab" => "submittedTask", "filter" => "all"]) ?>" 
            class="btn custom-primary-btn filter-form-btn <?= $tabData["filter"] === "all" ? "filter-active" : "" ?>">
                All Task
        </a>
        <a href="<?= BASE_URL . "/index.php?" . http_build_query($baseUrl + ["currentNavTab" => "submittedTask", "filter" => "due_today"]) ?>" 
            class="btn custom-primary-btn filter-form-btn <?= $tabData["filter"] === "due_today" ? "filter-active" : "" ?>">
                Due Today
        </a>
        <a href="<?= BASE_URL . "/index.php?" . http_build_query($baseUrl + ["currentNavTab" => "submittedTask", "filter" => "overdue"]) ?>" 
            class="btn custom-primary-btn filter-form-btn <?= $tabData["filter"] === "overdue" ? "filter-active" : "" ?>">
                Overdue
        </a>
        <a href="<?= BASE_URL . "/index.php?" . http_build_query($baseUrl + ["currentNavTab" => "submittedTask", "filter" => "upcoming"]) ?>" 
            class="btn custom-primary-btn filter-form-btn <?= $tabData["filter"] === "upcoming" ? "filter-active" : "" ?>">
                Upcoming
        </a>
    </div>
    <form method="GET" action="<?= BASE_URL . "/index.php?" ?>" class="d-flex gap-2">
        <input type="hidden" name="filter" value="<?= $tabData["filter"] ?? "all" ?>">
        <input type="hidden" name="page" value="<?= "projectPanel" ?>">
        <input type="hidden" name="projectId" value="<?= $baseUrl["projectId"] ?>">
        <input type="hidden" name="currentPaginationPage" value="<?= $baseUrl["currentPaginationPage"] ?>">
        <input type="hidden" name="currentNavTab" value="<?= "submittedTask" ?>">

        <input required ="text" class="form-control" name="search" placeholder="Search Task | Member">
        <button type="submit" class="btn custom-primary-btn filter-form-btn">
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