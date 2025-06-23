<?php //var_dump($tabData["memberStats"]["soloTasks"]); ?>

<h6 class="text-muted">Total Member: 5</h6>
<div class="mb-3 d-flex justify-content-between">
    <div class="d-flex align-items-center gap-2">
        <a href="<?= BASE_URL . "/index.php?" . http_build_query($baseUrl + ["currentNavTab" => "manageMembers", "filter" => "solo"]); ?>" 
           class="btn custom-primary-btn filter-form-btn <?= $tabData["filter"] === "solo" ? "filter-active" : "" ?>">Solo Task</a>
        <a href="<?= BASE_URL . "/index.php?" . http_build_query($baseUrl + ["currentNavTab" => "manageMembers", "filter" => "group"]); ?>" 
           class="btn custom-primary-btn filter-form-btn <?= $tabData["filter"] === "group" ? "filter-active" : "" ?>">Group Task</a>
    </div>
    <form class="d-flex gap-2">
        <input type="text" class="form-control" name="searchProject" placeholder="Search Name">
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
            <th scope="col"><?= ucfirst($tabData["filter"]); ?> Tasks</th>
            <th scope="col">Unsubmitted <?= ucfirst($tabData["filter"]); ?> Task</th>
            <th scope="col">Submitted <?= ucfirst($tabData["filter"]); ?> Task</th>
            <th scope="col">Approved <?= ucfirst($tabData["filter"]); ?> Task</th>
            <th scope="col">Rejected <?= ucfirst($tabData["filter"]); ?> Task</th>
            <th scope="col">Action</th>
        </tr>
    </thead> 
    <tbody>

        <?php if($tabData["filter"] == "solo"): ?>
            <?php foreach($tabData["memberStats"]["soloTasks"] as $tasks): ?>
                <tr>
                    <th scope="row"><?= e($tasks["id"]); ?></th>
                    <td><?= e($tasks["name"]); ?></td>
                    <td><?= e($tasks["total_task"]); ?></td>
                    <td><?= e($tasks["unsubmitted_task"]); ?></td>
                    <td><?= e($tasks["submitted_task"]); ?></td>
                    <td><?= e($tasks["approved_task"]); ?></td>
                    <td><?= e($tasks["rejected_task"]); ?></td>
                    <td>
                        <a href="#" class="btn custom-primary-btn my-manage-btn">Manage</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>


         <?php if($tabData["filter"] == "group"): ?>
            <?php foreach($tabData["memberStats"]["groupTasks"] as $tasks): ?>
                <tr>
                    <th scope="row"><?= e($tasks["id"]); ?></th>
                    <td><?= e($tasks["name"]); ?></td>
                    <td><?= e($tasks["total_task"]); ?></td>
                    <td><?= e($tasks["unsubmitted_task"]); ?></td>
                    <td><?= e($tasks["submitted_task"]); ?></td>
                    <td><?= e($tasks["approved_task"]); ?></td>
                    <td><?= e($tasks["rejected_task"]); ?></td>
                    <td>
                        <a href="#" class="btn custom-primary-btn my-manage-btn">Manage</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>