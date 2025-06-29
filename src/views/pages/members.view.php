<?php // var_dump($userTaskSummary[1]); ?>

<div class="container custom-container">
    <?php require __DIR__ . "/../components/backButton.view.php" ?>
    <h2>Display All Accounts</h2>
    <hr class="border-primary border-2 mb-4">

    <h6 class="text-muted">Total Project: 5</h6>
    <div class="mb-3 d-flex justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <button href="#" class="btn custom-primary-btn filter-form-btn">All Role</button>
            <button href="#" class="btn custom-primary-btn filter-form-btn">Member</button>
            <button href="#" class="btn custom-primary-btn filter-form-btn">Project Manager</button>
            <button href="#" class="btn custom-primary-btn filter-form-btn">Admin</button>
        </div>
        <form class="d-flex gap-2">
                <input type="text" class="form-control" name="searchProject" placeholder="Search Project">
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
                    <td><a href="#" class="btn custom-primary-btn my-manage-btn">Manage</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>