<?php $searchQuery = isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>
<?php //var_dump($userId); ?>

<div class="container custom-container">
    <?php require __DIR__ . "/../components/backButton.view.php" ?>

    <h2><?= $headerTitle ?? "Display All Projects Data" ?></h2>
    <hr class="border-primary border-2 mb-4">

    <h6 class="text-muted">Total Project: <?php echo count($projects); ?></h6>
    <div class="mb-3 d-flex justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <a href="<?php echo BASE_URL . "/index.php?page=memberProjects&filter=all_projects&userId=$userId" . $searchQuery ?>" 
                class="btn custom-primary-btn filter-form-btn <?php echo ( $filter ?? "" ) === "all_projects" ? "filter-active" : "" ?>">
                    All Projects
            </a>
            <a href="<?php echo BASE_URL . "/index.php?page=memberProjects&filter=due_today&userId=$userId" . $searchQuery ?>" 
                class="btn custom-primary-btn filter-form-btn <?php echo ( $filter ?? "" ) === "due_today" ? "filter-active" : "" ?>">
                    Due Today
            </a>
            <a href="<?php echo BASE_URL . "/index.php?page=memberProjects&filter=overdue&userId=$userId" . $searchQuery ?>" 
                class="btn custom-primary-btn filter-form-btn <?php echo ( $filter ?? "" ) === "overdue" ? "filter-active" : "" ?>">
                    Overdue
            </a>
            <a href="<?php echo BASE_URL . "/index.php?page=memberProjects&filter=upcoming&userId=$userId" . $searchQuery ?>" 
                class="btn custom-primary-btn filter-form-btn <?php echo ( $filter ?? "" ) === "upcoming" ? "filter-active" : "" ?>">
                    Upcoming
            </a>
        </div>
        <form method="GET" action="<?php echo BASE_URL . "/index.php" ?>" class="d-flex gap-2" >
                <input type="hidden" name="page" value="memberProjects">
                <?php if (!empty($filter)): ?>
                    <input type="hidden" name="filter" value="<?= e($filter) ?>">
                <?php endif; ?>
                <?php if (!empty($userId)): ?>
                    <input type="hidden" name="userId" value="<?= e($userId) ?>">
                <?php endif; ?>

                <input type="text" class="form-control" name="search" placeholder="Search Project" value="<?= e($_GET["search"] ?? "") ?>">
                <button type="submit" class="btn custom-primary-btn filter-form-btn">
                    <img src="./public/images/magnifying-glass.png" alt="icon" style="width:15px; height:15px; filter: invert(1);">
                </button>
        </form>
    </div>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Name</th>
                <th scope="col">Assigned Manager</th>
                <th scope="col">Members</th>
                <th scope="col">Tasks</th>
                <th scope="col">Deadline</th>
                <th scope="col">Progress</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($projects as $project): ?>
                <tr>
                    <th scope="row"><?php echo e($project["id"]); ?></th>
                    <td><?php echo e($project["name"]); ?></td>
                    <td><?php echo e($project["fullname"]); ?></td>
                    <td><?php echo e($project["number_of_members"]); ?></td>
                    <td><?php echo e($project["number_of_tasks"]); ?></td>
                    <td><?php echo e($project["deadline"]); ?></td>
                    <td><?php echo e($project["progress"]) . "%"; ?></td>
                    <td><?php echo e($project["status"]); ?></td>
                    <td>
                        <a href="<?= BASE_URL . "/index.php?" . http_build_query(["page" => "projectPanel", "projectId" => $project['id']])  ?>" 
                            class="btn custom-primary-btn my-manage-btn">Manage
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>