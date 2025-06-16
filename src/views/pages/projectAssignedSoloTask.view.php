<?php // var_dump($tabData["soloTask"]); ?>

<h6 class="text-muted">Total Solo Task: <?= count($tabData["soloTask"]); ?></h6>
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
                    <th scope="col">Assigned Member</th>
                    <th scope="col">Assigned Date</th>
                    <th scope="col">Deadline</th>
                    <th scope="col">Milestone</th>
                    <th scope="col">Status</th>
                    <th scope="col">Approval Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($tabData["soloTask"] as $row): ?>
                    <tr>
                        <th scope="row"><?= e($row["id"]); ?></th>
                        <td><?= e($row["taskname"]); ?></td>
                        <td><?= e($row["fullname"]); ?></td>
                        <td><?= e($row["assigned_date"]); ?></td>
                        <td><?= e($row["deadline"]); ?></td>
                        <td><?= e($row["milestone"]); ?></td>
                        <td><?= e($row["status"]); ?></td>
                        <td><?= e($row["approval_status"] ?? "Not yet submitted"); ?></td>
                        <td><a href="#" class="btn custom-primary-btn my-manage-btn">Manage</a></td>
                    </tr>
                <?php endforeach; ?>
        </tbody>
</table>