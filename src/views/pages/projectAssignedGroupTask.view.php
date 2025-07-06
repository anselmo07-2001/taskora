<?php // var_dump($tabData["groupTask"]); ?>

<h6 class="text-muted">Total Group Task: <?= count($tabData["groupTask"]); ?></h6>
<div class="mb-3 d-flex justify-content-between">
    <div class="d-flex align-items-center gap-2">
        <a href="<?= BASE_URL . "/index.php?" . http_build_query( $baseUrl + ["currentNavTab" => "assignedGroupTask", "filter" => "allGroupTask"] +    
                        ( ($request["get"]["search"] ?? "") !== "" ? ["search" => $request["get"]["search"]] : []) )?>" 
                            class="btn custom-primary-btn filter-form-btn <?= $tabData["filter"] === "allGroupTask" ? "filter-active" : "" ?>">
            All Group Task
        </a>              
        <a href="<?= BASE_URL . "/index.php?" . http_build_query($baseUrl + ["currentNavTab" => "assignedGroupTask", "filter" => "due_today"] ) ?>"             
                        class="btn custom-primary-btn filter-form-btn <?= $tabData["filter"] === "due_today" ? "filter-active" : "" ?>">
            Due Today
        </a>
        <a href="<?= BASE_URL . "/index.php?" . http_build_query($baseUrl + ["currentNavTab" => "assignedGroupTask", "filter" => "overdue"]) ?>"
                        class="btn custom-primary-btn filter-form-btn <?= $tabData["filter"] === "overdue" ? "filter-active" : "" ?>">
            Overdue
        </a>
        <a href="<?= BASE_URL . "/index.php?" . http_build_query($baseUrl + ["currentNavTab" => "assignedGroupTask", "filter" => "upcoming"]) ?>"
                        class="btn custom-primary-btn filter-form-btn <?= $tabData["filter"] === "upcoming" ? "filter-active" : "" ?>">
            Upcoming
        </a>
    </div>
    <form method="GET" class="d-flex gap-2" action="<?= BASE_URL . "/index.php" ?>">
            <!-- Preserve base URL parameters -->
        <input type="hidden" name="page" value="projectPanel">
        <input type="hidden" name="projectId" value="<?= e($request["get"]["projectId"] ?? "") ?>">
        <input type="hidden" name="currentNavTab" value="assignedGroupTask">
        <input type="hidden" name="currentPaginationPage" value="<?= e($request["get"]["currentPaginationPage"] ?? 1) ?>">

        <!-- Preserve filter if selected -->
        <?php if (!empty($tabData["filter"])): ?>
            <input type="hidden" name="filter" value="<?= e($tabData["filter"]) ?>">
        <?php endif; ?>

        <input type="text" class="form-control" name="search" placeholder="Search Task" value="<?= e($request["get"]["search"] ?? "");?>">

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
            <th scope="col">Assigned Members</th>
            <th scope="col">Assigned Date</th>
            <th scope="col">Deadline</th>
            <th scope="col">Milestone</th>
            <th scope="col">Status</th>
            <th scope="col">Approval Status</th>
            <th scope="col">Action</th> 
        </tr>
    </thead>
    <tbody>
        <?php foreach($tabData["groupTask"] as $task): ?>
            <tr>
                <th scope="row"><?= e($task["id"]); ?></th>
                <td><?= e($task["taskname"]); ?></td>
                <td><?= e($task["assigned_members"]); ?></td>
                <td><?= e($task["assigned_date"]); ?></td>
                <td><?= e($task["deadline"]); ?></td>
                <td><?= $task["milestone"]; ?></td>
                <td><?= e($task["status"]); ?></td>
                <td>
                    <?php if ($task["status"] !== "completed" && $task["approval_status"] === NULL): ?>
                        <span>Pending Completion</span>
                    <?php elseif ($task["status"] === "completed" && $task["approval_status"] === NULL): ?>
                        <span>Awaiting Approval</span>
                    <?php elseif ($task["status"] === "completed" && $task["approval_status"] === "approved"): ?>
                        <span>Approved</span>        
                    <?php elseif ($task["status"] === "completed" && $task["approval_status"] === "rejected"): ?>
                        <span>Rejected</span>
                    <?php endif; ?>
                </td>
                <td>
                    <div class="d-flex gap-1">
                        <form method="POST" action="<?= BASE_URL . "/index.php?" . http_build_query(["page" => "deleteTask"]) ?>">
                                        <input type="hidden" name="redirectUrl" value="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>">
                                        <input type="hidden" name="taskId" value="<?= e($task["id"]); ?>"/>
                                        <button type="submit" class="btn btn-danger my-manage-btn">Delete</button>
                        </form>
                        <a href="<?= BASE_URL . "/index.php?" . 
                                        http_build_query(["page" => "editTask", "taskId" => e($task["id"]), "redirect" => urlencode($_SERVER['REQUEST_URI']) ]) ?>" 
                                        class="btn btn-secondary my-manage-btn">
                                        Edit
                        </a>
                        <a href="<?= BASE_URL . "/index.php?" . http_build_query(["page" => "taskPanel", "taskId" => e($task["id"]) ]) ?>" class="btn custom-primary-btn my-manage-btn">
                            Manage
                        </a>
                    </div>
                </td>
            </tr>
         <?php endforeach; ?>
    </tbody>
</table>