<?php //var_dump($tabData["memberStats"]["soloTasks"]); ?>

<div class="mb-3 d-flex justify-content-between">
    <div class="d-flex align-items-center gap-2">
        <a href="<?= BASE_URL . "/index.php?" . http_build_query($baseUrl + ["currentNavTab" => "manageMembers", "filter" => "all"]); ?>" 
           class="btn custom-primary-btn filter-form-btn <?= $tabData["filter"] === "all" ? "filter-active" : "" ?>">All Tasks</a>
        <a href="<?= BASE_URL . "/index.php?" . http_build_query($baseUrl + ["currentNavTab" => "manageMembers", "filter" => "solo"]); ?>" 
           class="btn custom-primary-btn filter-form-btn <?= $tabData["filter"] === "solo" ? "filter-active" : "" ?>">Solo Task</a>
        <a href="<?= BASE_URL . "/index.php?" . http_build_query($baseUrl + ["currentNavTab" => "manageMembers", "filter" => "group"]); ?>" 
           class="btn custom-primary-btn filter-form-btn <?= $tabData["filter"] === "group" ? "filter-active" : "" ?>">Group Task</a>
    </div>
    <form method="GET" action="<?= BASE_URL . "/index.php?" ?>" class="d-flex gap-2">
        <input type="hidden" name="filter" value="<?= $tabData["filter"] ?? "solo" ?>">
        <input type="hidden" name="page" value="<?= "projectPanel" ?>">
        <input type="hidden" name="projectId" value="<?= $baseUrl["projectId"] ?>">
        <input type="hidden" name="currentPaginationPage" value="<?= $baseUrl["currentPaginationPage"] ?>">
        <input type="hidden" name="currentNavTab" value="<?= "manageMembers" ?>">

        <input require type="text" class="form-control" name="search" placeholder="Search Member" >
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
        <?php foreach($tabData["memberStats"] as $memberStats): ?>
            <tr>
                <th scope="row"><?= e($memberStats["id"]); ?></th>
                <td><?= e($memberStats["name"]); ?></td>
                <td><?= e($memberStats["total_task"]); ?></td>
                <td><?= e($memberStats["unsubmitted_task"]); ?></td>
                <td><?= e($memberStats["submitted_task"]); ?></td>
                <td><?= e($memberStats["approved_task"]); ?></td>
                <td><?= e($memberStats["rejected_task"]); ?></td>
                <td>
                        <a href="<?= BASE_URL . "/index.php?" . http_build_query(["page" => "memberProfilePanel", "memberId" => $memberStats["id"], "projectId" => $baseUrl["projectId"]]); ?>" 
                            class="btn custom-primary-btn my-manage-btn">
                            Manage
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>