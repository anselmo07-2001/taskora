<?php require __DIR__ . "/../components/pagination.view.php" ?>
<div class="container custom-container">
    <?php require __DIR__ . "/../components/flashMessage.view.php" ?>
    <h2>Display All Tasks</h2>
    <hr class="border-primary border-2 mb-4">

    <?php
        $label = ([
            'all' => 'All',
            'due_today' => 'Due Today',
            'overdue' => 'Overdue',
            'upcoming' => 'Upcoming'
        ][$filter] ?? 'All');
    ?>
    <h6 class="text-muted"><?= $label ?> Tasks: <?= $totalTasks ?></h6>
    <div class="mb-3 d-flex justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <a href="<?= BASE_URL . "/index.php?" . http_build_query(["page" => "tasks", "filter" => "all"]) ?>" 
                    class="btn custom-primary-btn filter-form-btn <?= $filter === "all" ? "filter-active" : "" ?>">All Tasks</a>
            <a href="<?= BASE_URL . "/index.php?" . http_build_query(["page" => "tasks", "filter" => "due_today"]) ?>" 
                    class="btn custom-primary-btn filter-form-btn <?= $filter === "due_today" ? "filter-active" : "" ?>">Due Today</a>
            <a href="<?= BASE_URL . "/index.php?" . http_build_query(["page" => "tasks", "filter" => "overdue"]) ?>" 
                class="btn custom-primary-btn filter-form-btn <?= $filter === "overdue" ? "filter-active" : "" ?>">Ovedue</a>
            <a href="<?= BASE_URL . "/index.php?" . http_build_query(["page" => "tasks", "filter" => "upcoming"]) ?>" 
                class="btn custom-primary-btn filter-form-btn <?= $filter === "upcoming" ? "filter-active" : "" ?>">Upcoming</a>
        </div>
        <form method="GET" class="d-flex gap-2">
                <input type="hidden" name="page" value="tasks" />
                <input type="hidden" name="currentPaginationPage" value="<?= $currentPaginationPage ?? 1 ?>" />
                <input type="hidden" name="filter" value="<?= $filter ?? "" ?>" />

                <input type="text" class="form-control" name="search" placeholder="Search Task" value="<?= $search ?? "" ?>">
                <button class="btn custom-primary-btn filter-form-btn">
                    <img src="./public/images/magnifying-glass.png" alt="icon" style="width:15px; height:15px; filter: invert(1);">
                </button>
        </form>
    </div>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Tasks</th>
                <th scope="col">Project</th>
                <th scope="col">Assigned Member</th>
                <th scope="col">Assigned Date</th>
                <th scope="col">Status</th>
                <th scope="col">Deadline</th>
                <th scope="col">Approval Status</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($tasks as $task): ?>
                <tr>
                    <th scope="row"><?= e($task["id"]); ?></th>
                    <td><?= e($task["task"]); ?></td>
                    <td><?= e($task["project_name"]); ?></td>
                    <td><?= e($task["assigned_member"]); ?></td>
                    <td><?= e($task["assigned_date"]); ?></td>
                    <td><?= e($task["status"]); ?></td>
                    <td><?= e($task["deadline"]); ?></td>
                    <td><?= e($task["approval_status"]); ?></td>
                    <td>
                        <div class="d-flex gap-1">
                            <form method="POST" action="<?= BASE_URL . "/index.php?" . http_build_query(["page" => "deleteTask"]) ?>">
                                <input type="hidden" name="taskId" value="<?= e($task["id"]); ?>"/>
                                <button type="submit" class="btn btn-danger my-manage-btn">Delete</button>
                            </form>
                            <a href="<?= BASE_URL . "/index.php?" . http_build_query([ "page" => "taskPanel", "taskId" => e($task["id"])]) ?>" 
                                class="btn custom-primary-btn my-manage-btn">
                                    Open
                            </a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php
        if ($totalTasks > 10) {
            echo renderPagination([
                "currentPaginationPage" => $paginationMeta["currentPaginationPage"],
                "baseUrl" => ["filter" => $filter, "search" => $search ?? ""],
                "paginationStart" => $paginationMeta["start"],
                "paginationEnd" => $paginationMeta["end"],
                "totalPages" => $paginationMeta["totalItems"],
                "page" => "tasks"
            ]);
        } 
    ?>
</div>