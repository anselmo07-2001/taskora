<?php var_dump($request); ?>

<h6 class="text-muted">Total Solo Task: <?= count($tabData["soloTask"]); ?></h6>
        <div class="mb-3 d-flex justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <a href="<?= BASE_URL . "/index.php?" . http_build_query( $baseUrl + ["currentNavTab" => "assignedSoloTask", "filter" => "allSoloTask"] +    
                                ( ($request["get"]["search"] ?? "") !== "" ? ["search" => $request["get"]["search"]] : []) )?>" 
                                 class="btn custom-primary-btn filter-form-btn <?= $tabData["filter"] === "allSoloTask" ? "filter-active" : "" ?>">
                    All Solo Task
                </a>
                            
                <a href="<?= BASE_URL . "/index.php?" . http_build_query($baseUrl + ["currentNavTab" => "assignedSoloTask", "filter" => "due_today"] + 
                                ( ($request["get"]["search"] ?? "") !== "" ? ["search" => $request["get"]["search"]] : []) ) ?>" 
                                class="btn custom-primary-btn filter-form-btn <?= $tabData["filter"] === "due_today" ? "filter-active" : "" ?>">
                    Due Today
                </a>

                <a href="<?= BASE_URL . "/index.php?" . http_build_query($baseUrl + ["currentNavTab" => "assignedSoloTask", "filter" => "overdue"] + 
                                ( ($request["get"]["search"] ?? "") !== "" ? ["search" => $request["get"]["search"]] : []) ) ?>" 
                                class="btn custom-primary-btn filter-form-btn <?= $tabData["filter"] === "due_today" ? "filter-active" : "" ?>">
                    Overdue
                </a>
                <a href="<?= BASE_URL . "/index.php?" . http_build_query($baseUrl + ["currentNavTab" => "assignedSoloTask", "filter" => "upcoming"] + 
                                ( ($request["get"]["search"] ?? "") !== "" ? ["search" => $request["get"]["search"]] : []) ) ?>" 
                                class="btn custom-primary-btn filter-form-btn <?= $tabData["filter"] === "due_today" ? "filter-active" : "" ?>">
                    Upcoming
                </a>
            </div>
            <form method="GET" class="d-flex gap-2" action="<?= BASE_URL . "/index.php" ?>">
                 <!-- Preserve base URL parameters -->
                <input type="hidden" name="page" value="projectPanel">
                <input type="hidden" name="projectId" value="<?= e($request["get"]["projectId"] ?? "") ?>">
                <input type="hidden" name="currentNavTab" value="assignedSoloTask">
                <input type="hidden" name="currentPaginationPage" value="<?= e($request["get"]["currentPaginationPage"] ?? 1) ?>">

                <!-- Preserve filter if selected -->
                <?php if (!empty($tabData["filter"])): ?>
                    <input type="hidden" name="filter" value="<?= e($tabData["filter"]) ?>">
                <?php endif; ?>

                <input type="text" class="form-control" name="search" placeholder="Search Task | Member" value="<?= e($request["get"]["search"] ?? "");?>">

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