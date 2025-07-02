<?php require __DIR__ . "/../components/pagination.view.php" ?>
<?php //var_dump($totalAccountsByFilter); ?>

<div class="container custom-container">
    <?php require __DIR__ . "/../components/backButton.view.php" ?>
    <h2>Display All Accounts</h2>
    <hr class="border-primary border-2 mb-4">

    <h6 class="text-muted">Total Account: <?= $totalAccountsByFilter ?></h6>
    <div class="mb-3 d-flex justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <a href="<?= BASE_URL . "/index.php?page=members&" . http_build_query(["filter" => "all"]); ?>" 
                class="btn custom-primary-btn filter-form-btn <?= $filter === "all" ? "filter-active" : "" ?>">All Role</a>
            <a href="<?= BASE_URL . "/index.php?page=members&" . http_build_query(["filter" => "member"]); ?>" 
                class="btn custom-primary-btn filter-form-btn <?= $filter === "member" ? "filter-active" : "" ?>">Member</a>
            <a href="<?= BASE_URL . "/index.php?page=members&" . http_build_query(["filter" => "project_manager"]); ?>" 
                class="btn custom-primary-btn filter-form-btn <?= $filter === "project_manager" ? "filter-active" : "" ?>">Project Manager</a>
            <a href="<?= BASE_URL . "/index.php?page=members&" . http_build_query(["filter" => "admin"]); ?>" 
                class="btn custom-primary-btn filter-form-btn <?= $filter === "admin" ? "filter-active" : "" ?>">Admin</a>
        </div>
        <form method="GET" class="d-flex gap-2">
                <input type="hidden" name="page" value="members"/>
                <input type="hidden" name="filter" value="<?= $filter ?>"/>
                <input required type="text" class="form-control" name="search" placeholder="Search User" value="<?= $search ?>">
                <button class="btn custom-primary-btn filter-form-btn">
                    <img src="./public/images/magnifying-glass.png" alt="icon" style="width:15px; height:15px; filter: invert(1);">
                </button>
        </form>
    </div>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Name</th>
                <th scope="col">Role</th>
                <th scope="col">Project</th>
                <th scope="col">Tasks</th>
                <th scope="col">Unsubmitted Tasks</th>
                <th scope="col">Submitted Tasks</t h>
                <th scope="col">Approved Status</th>
                <th scope="col">Rejected Status</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($userTaskSummary as $user): ?>
                <tr>
                    <th scope="row"><?= e($user["id"]); ?></th>
                    <td><?= e($user["name"]); ?></td>
                    <td><?= e($user["role"]); ?></td>
                    <td><?= e($user["total_project_count"]); ?></td>
                    <td><?= e($user["total_task"]); ?></td>
                    <td><?= e($user["unsubmitted_task"]); ?></td>
                    <td><?= e($user["submitted_task"]); ?></td>
                    <td><?= e($user["approved_task"]); ?></td>
                    <td><?= e($user["rejected_task"]); ?></td>
                    <td><a href="<?= BASE_URL . "/index.php?" . http_build_query(["page" => "memberProjects", "userId" => $user["id"]]); ?>" class="btn custom-primary-btn my-manage-btn">Manage</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>


    <?php
        echo renderPagination([
            "currentPaginationPage" => $paginationMeta["currentPaginationPage"],
            "baseUrl" => ["filter" => $filter, "search" => $_GET["search"] ?? ""],
            "paginationStart" => $paginationMeta["start"],
            "paginationEnd" => $paginationMeta["end"],
            "totalPages" => $paginationMeta["totalItems"],
            "page" => "members"
        ]);
    ?>
</div>