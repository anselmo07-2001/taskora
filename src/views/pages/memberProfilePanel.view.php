<?php // var_dump($memberProfile); ?>

<div class="container custom-container">
        <?php require __DIR__ . "/../components/flashMessage.view.php" ?>
        <?php require __DIR__ . "/../components/backButton.view.php" ?>
        <h1><?= e($memberProfile["fullname"] ?? ""); ?><sup class="sup-lift fs-6 text-muted">(Member)</sup></h1>
        <div class="mb-5">View all tasks involving <?= e($memberProfile["fullname"] ?? ""); ?></div>

        <?php if ($memberProfile["status"] !== "suspended"): ?>
            <form method="POST" class="mb-4" action="<?= BASE_URL . "/index.php?" . http_build_query(["page" => "modifyUserAccountStatus"]) ?>">
                <div class="fw-semibold">Suspend this Account</div>
                <div class="mb-2">If you want to temporary deactivated this account, you may click this button </div>
                <input type="hidden" name="userId" value="<?= $memberProfile["id"] ?>" />
                <input type="hidden" name="status" value="suspended" />
                <input type="hidden" name="redirectBack" value="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>">
                <button type="submit" class="btn btn-warning">Suspend Account</button>
            </form>
        <?php endif; ?>

        <?php if ($memberProfile["status"] === "suspended"): ?>
            <form method="POST" class="mb-4" action="<?= BASE_URL . "/index.php?" . http_build_query(["page" => "modifyUserAccountStatus"]) ?>">
                <div class="fw-semibold">Unsuspend this Account</div>
                <div class="mb-2">Account is current suspended, you may click this button to resume this account</div>
                <input type="hidden" name="userId" value="<?= $memberProfile["id"] ?>" />
                <input type="hidden" name="status" value="active" />
                <input type="hidden" name="redirectBack" value="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>">
                <button type="submit" class="btn btn-success">Unsuspend Account</button>
            </form>
        <?php endif; ?>


        <div class="mb-5">
            <div class="fw-semibold">Delete this Account</div>
            <div class="mb-2">If you want to delete this account, Please note that all data related to this account will also deleted </div>
            <button class="btn btn-danger">Delete Account</button>
        </div>

        <div class="mb-4 bg-light text-dark p-2 rounded">
            <div class="fw-semibold">You may also choose to delete or suspend tasks or projects</div>
            <div>Reminder: Suspended tasks are locked — you can’t edit, change status, or submit them.
                To suspend or delete a project, use the Project Manager dashboard.
            </div>
        </div>

        <div>
            <h6 class="text-muted">Total Task: <?= count($memberTasks) ?? 0; ?></h6>
        <div class="mb-3 d-flex justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <a href="<?= BASE_URL . "/index.php?" . 
                             http_build_query(["page" => "memberProfilePanel", "memberId" => $memberProfile["id"], "filter" => "all", "projectId" => $projectId]) ?>" 
                    class="btn custom-primary-btn filter-form-btn <?= $filter === "all" ? "filter-active" : "" ?>">
                        All Tasks
                </a>
                <a href="<?= BASE_URL . "/index.php?" . 
                             http_build_query(["page" => "memberProfilePanel", "memberId" => $memberProfile["id"], "filter" => "solo", "projectId" => $projectId]) ?>" 
                    class="btn custom-primary-btn filter-form-btn <?= $filter === "solo" ? "filter-active" : "" ?>">
                        Solo Task
                </a>
                <a href="<?= BASE_URL . "/index.php?" . 
                             http_build_query(["page" => "memberProfilePanel", "memberId" => $memberProfile["id"], "filter" => "group", "projectId" => $projectId]) ?>" 
                    class="btn custom-primary-btn filter-form-btn <?= $filter === "group" ? "filter-active" : "" ?>">
                        Group Task
                </a>
            </div>
            <form method="GET" class="d-flex gap-2" action="<?= BASE_URL . "/index.php?"?>">
                 <input type="hidden" name="projectId" value="<?= $projectId ?? "" ?>">
                 <input type="hidden" name="page" value="<?= "memberProfilePanel" ?>">
                 <input type="hidden" name="memberId" value="<?= $memberProfile["id"] ?? "" ?>">
                 <input type="hidden" name="filter" value="<?= $filter ?? "all" ?>">
                 
                 <input type="text" class="form-control" name="searchTask" placeholder="Search Task" value=<?= $search ?>>
                 <button class="btn custom-primary-btn filter-form-btn">
                      <img src="./public/images/magnifying-glass.png" alt="icon" style="width:15px; height:15px; filter: invert(1);">
                 </button>
            </form>
        </div>

        <!-- Suspended Task BTN Modal -->
        <div class="modal fade" id="suspendModal" tabindex="-1" aria-labelledby="suspendModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="your-php-handler.php" method="POST">
                        <div class="modal-header">
                        <h5 class="modal-title" id="suspendModalLabel">Suspend Task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                        <div class="mb-3">
                            <label for="suspendReason" class="form-label">Reason for Suspension</label>
                            <textarea class="form-control" id="suspendReason" name="suspend_reason" rows="3" required></textarea>
                        </div>
                        <!-- hidden input to pass task ID -->
                        <input type="hidden" name="task_id" value="123">
                        </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Submit Reason</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <form action="reject-task.php" method="POST">
                    <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Reject Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejectReason" class="form-label">Reason for Rejection</label>
                        <textarea class="form-control" id="rejectReason" name="reject_reason" rows="3" required></textarea>
                    </div>
                        <input type="hidden" name="task_id" value="123">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Submit Rejection</button>
                    </div>
                </form>
                </div>
            </div>
        </div>


            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Name</th>
                        <th scope="col">Task Type / Members </th>
                        <th scope="col">Assigned Date</th>
                        <th scope="col">Deadline</th>
                        <th scope="col">Milestone</th>
                        <th scope="col">Status</th>
                        <th scope="col">Approval Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($memberTasks as $memberTask): ?>
                        <tr>
                            <th scope="row"><?= e($memberTask["id"]); ?></th>
                            <td><?= e($memberTask["name"]); ?></td>
                            <td><?= e($memberTask["taskType_members"]); ?></td>
                            <td><?= e($memberTask["assigned date"]); ?></td>
                            <td><?= e($memberTask["deadline"]); ?></td>
                            <td><?= e($memberTask["milestone"]); ?></td>
                            <td><?= e($memberTask["status"]); ?></td>
                            <td><?= e($memberTask["approval status"]); ?></td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <a href="#" class="btn btn-warning my-manage-btn" data-bs-toggle="modal" data-bs-target="#suspendModal">Suspend</a>
                                    <a href="#" class="btn btn-danger my-manage-btn" data-bs-toggle="modal" data-bs-target="#rejectModal">Rejected</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
   </div>