
<div class="container custom-container">
    <h6 class="text-muted">Total Solo Task: <?= count($tasks); ?></h6>
    <div class="mb-3 d-flex justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <a href="<?= BASE_URL . "/index.php?page=myGroupTasks&" . http_build_query(["filter" => "all"])?>" 
                    class="btn custom-primary-btn filter-form-btn <?= $filter === "all" ? "filter-active" : "" ?>">
                All
            </a>
            <a href="<?= BASE_URL . "/index.php?page=myGroupTasks&" . http_build_query(["filter" => "due_today"])?>" 
                    class="btn custom-primary-btn filter-form-btn <?= $filter === "due_today" ? "filter-active" : "" ?>">
                Due Today
            </a>
            <a href="<?= BASE_URL . "/index.php?page=myGroupTasks&" . http_build_query(["filter" => "overdue"])?>" 
                    class="btn custom-primary-btn filter-form-btn <?= $filter === "overdue" ? "filter-active" : "" ?>">
                Overdue
            </a>
            <a href="<?= BASE_URL . "/index.php?page=myGroupTasks&" . http_build_query(["filter" => "upcoming"])?>" 
                    class="btn custom-primary-btn filter-form-btn <?= $filter === "upcoming" ? "filter-active" : "" ?>">
                Upcoming
            </a>
        </div>
        <form method="GET" action="<?= BASE_URL . "/index.php" ?>" class="d-flex gap-2">
                <input type="hidden" name="page" value="myGroupTasks" />
                <input type="hidden" name="filter" value="<?= $filter ?? "all" ?>" />

                <input required type="text" class="form-control" name="search" placeholder="Search Task">
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
                <th scope="col">Members</th>
                <th scope="col">Project</th>
                <th scope="col">Assigned Date</th>
                <th scope="col">Status</th>
                <th scope="col">Deadline</th>
                <th scope="col">Milestone</th>
                <th scope="col">Approval Status</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($tasks as $task): ?>
                <tr>
                    <th scope="row"><?= e($task["id"]); ?></th>
                    <td><?= e($task["task"]); ?></td>
                    <td><?= e($task["members"]); ?></td>
                    <td><?= e($task["project"]); ?></td>
                    <td><?= e($task["assigned_date"]); ?></td>
                    <td><?= e($task["status"]); ?></td>
                    <td><?= e($task["deadline"]); ?></td>
                    <td><?= e($task["milestone"]); ?></td>
                    <td><?= e($task["approval_status"]); ?></td>
                    <td><a href="<?= BASE_URL . "/index.php?" . http_build_query(["page" => "taskPanel", "taskId" => e($task["id"]) ]) ?>" class="btn custom-primary-btn my-manage-btn">Manage</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>