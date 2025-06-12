<?php // var_dump($project ?? ""); ?>

<div class="container custom-container pb-5">
        <?php require __DIR__ . "/../components/flashMessage.view.php" ?>
        <div>
            <div class="d-flex align-items-center gap-2 mb-4">
                <img src="./public/images/scope.png" class="myproject-title-icon"/>
                <h1><?= e($project["name"]); ?></h1>
            </div>
            <h5 class="lh-sm">Objective</h5>
            <p class="fs-5 mb-4"><?= e($project["project_description"]); ?></p>
            <div class="mb-4">
                 <ul class="list-unstyled">
                     <li>Deadline: <span><?= e($project["deadline"]); ?></span></li>
                     <li>Deadline Status: <span><?= e($project["deadline_status"]); ?></span></li>
                     <li>Progress: <span><?= e($project["progress"]); ?></span></li>
                     <li>Members: <span><?= e($project["member_count"]); ?></span></li>
                     <li>Project Manager: <span><?= e($project["fullname"]); ?></span></li>
                     <li>Current Project Status: <span><?= e($project["status"]); ?></span></li>
                 </ul>
            </div>

            <?php if ($currentUserSession["role"] !== "member"): ?>
                <div class="mb-5">      
                    <form method="POST" class="d-flex align-items-center gap-3" 
                            action="<?= BASE_URL . "/index.php?" . http_build_query(["page" => "updateProjectStatus", "projectId" => $project["id"]]) ?>" 
                    >
                        <label for="project-status" class="form-label">Change the project status:</label>
                        <select class="form-select w-25" id="project-status" style="margin-right: -0.5rem;" name="projectStatus">
                            <option value="pending" <?= $project["status"] === "pending" ? "selected" : "" ?>>Pending</option>
                            <option value="in_progress" <?= $project["status"] === "in_progress" ? "selected" : "" ?>>In progress</option>
                            <option value="completed" <?= $project["status"] === "completed" ? "selected" : "" ?>>Completed</option>
                            <option value="failed" <?= $project["status"] === "failed" ? "selected" : "" ?>>Failed</option>
                        </select>
                        <button class="btn custom-primary-btn">Update Project</button>
                    </form>
                </div>
            <?php endif ?>

            <ul class="nav nav-tabs my-custom-tabs mb-5">
                <li class="nav-item">
                    <a href="<?= BASE_URL . "/index.php?" . http_build_query($baseUrl + ["currentNavTab" => "projectNotes"]); ?>" class="nav-link <?= $currentNavTab === "projectNotes" ? "active" : "" ?>" aria-current="page">Project Note</a>
                </li>
                <li class="nav-item">
                    <a href="<?= BASE_URL . "/index.php?" . http_build_query($baseUrl + ["currentNavTab" => "assignedSoloTask"]); ?>" class="nav-link <?= $currentNavTab === "assignedSoloTask" ? "active" : "" ?>">Assigned Solo Task</a>
                </li>
                <li class="nav-item">
                    <a href="<?= BASE_URL . "/index.php?" . http_build_query($baseUrl + ["currentNavTab" => "assignedGroupTask"]); ?>" class="nav-link <?= $currentNavTab === "assignedGroupTask" ? "active" : "" ?>">Assigned Group Task</a>
                </li>
                <li class="nav-item">
                    <a href="<?= BASE_URL . "/index.php?" . http_build_query($baseUrl + ["currentNavTab" => "submittedTask"]); ?>" class="nav-link <?= $currentNavTab === "submittedTask" ? "active" : "" ?>" >Submitted Task</a>
                </li>
                <li class="nav-item">
                    <a href="<?= BASE_URL . "/index.php?" . http_build_query($baseUrl + ["currentNavTab" => "createTask"]); ?>" class="nav-link <?= $currentNavTab === "createTask" ? "active" : "" ?>" >Create Task</a>
                </li>
                <li class="nav-item">
                    <a href="<?= BASE_URL . "/index.php?" . http_build_query($baseUrl + ["currentNavTab" => "manageMembers"]); ?>" class="nav-link <?= $currentNavTab === "manageMembers" ? "active" : "" ?>" >Manage Members</a>
                </li>
            </ul>

            <?php if ($currentNavTab === "projectNotes"): ?>
                <div class="mb-5">
                    <h6 class="mb-3">Add Project Note</h6>
                    <form method="POST" action="<?= BASE_URL . "/index.php?" . http_build_query($baseUrl); ?>">
                        <textarea style="height: 10rem;" class="w-100 form-control mb-2 <?= ($errors["projectnoteErr"] ?? "") ? 'is-invalid' : ''; ?>" rows="4" placeholder="Enter your project note here" name="projectNote"></textarea>
                        <div class="d-flex justify-content-between">
                            <div class="me-3 flex-grow-1">
                                <?php if (!empty($errors["projectnoteErr"] ?? null)): ?> 
                                    <div class="invalid-feedback d-block" style="font-size: 0.75rem;">
                                        <?php echo $errors["projectnoteErr"]; ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="d-flex align-items-start">
                                <button class="btn custom-primary-btn align-self-start">Save Project Note</button>
                            </div>
                        </div> 
                    </form> 
                </div>

                <?php foreach ($data["projectNotes"] AS $row): ?>         
                    <div class="card mb-3">
                        <div class="card-body position-relative">
                            <div class="d-flex align-items-start">
                                <img src="./public/images/usernote.png" class="rounded-circle me-3" alt="User avatar" style="height:3rem;">
                                <div>
                                    <h6 class="mb-0"><?= $row->fullname ?><sup><?= $row->role !== "admin" ? ' (' . $row->role . ')' : "" ?></sup></h6>
                                    <small class="text-muted"><?= $row->projectnote_type . " on " . date("M d, Y, \a\\t h:i A", strtotime($row->created_at)); ?> </small>
                                    <p class="mt-2 mb-0">
                                      <?= $row->content ?>
                                    </p>
                                </div>
                            </div>
                            <?php if($currentUserSession["userId"] === $row->user_id): ?>
                                <div class="dropdown position-absolute top-0 end-0 me-2 mt-2">
                                    <button class="btn p-0 border-0 bg-transparent" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <div class="d-flex flex-column align-items-center justify-content-center" style="width: 20px; height: 30px;">
                                            <span class="bg-secondary rounded-circle" style="width: 4px; height: 4px; margin: 2px 0;"></span>
                                            <span class="bg-secondary rounded-circle" style="width: 4px; height: 4px; margin: 2px 0;"></span>
                                            <span class="bg-secondary rounded-circle" style="width: 4px; height: 4px; margin: 2px 0;"></span>
                                        </div>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#">Edit</a></li>
                                        <li><a class="dropdown-item" href="#">Delete</a></li>
                                    </ul>
                                </div>  
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="d-flex justify-content-end">
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1">Previous</a>
                            </li>
                            <li class="page-item active">
                                <a class="page-link page-link-mycolor" href="#">1 <span class="visually-hidden">(current)</span></a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">3</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">4</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">5</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            <?php endif; ?>


        <?php if ($currentNavTab === "assignedSoloTask"): ?>
            <h6 class="text-muted">Total Solo Task: 5</h6>
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
                    <tr>
                        <th scope="row">1</th>
                        <td>Write unit tests</td>
                        <td>Sophia Carter</td>
                        <td>2025-05-26</td>
                        <td>2025-09-23</td>
                        <td>93 Days</td>
                        <td>Completed</td>
                        <td>Approved</td>
                        <td><a href="#" class="btn custom-primary-btn my-manage-btn">Manage</a></td>
                    </tr>
                    <tr>
                        <td scope="row">2</td>
                        <td>Run security audit</td>
                        <td>Osgar Rivera</td>
                        <td>2025-05-26</td>
                        <td>2025-09-07</td>
                        <td>58 Days</td>
                        <td>Completed</td>
                        <td>Approved</td>
                        <td><a href="#" class="btn custom-primary-btn my-manage-btn">Manage</a></td>
                    </tr>
                    <tr>
                        <td scope="row">3</td>
                        <td>Conduct code review</td>
                        <td>Choco Choco</td>
                        <td>2025-05-26</td>
                        <td>2025-08-20</td>
                        <td>112 Days</td>
                        <td>Pending</td>
                        <td>Not yet submitted</td>
                        <td><a href="#" class="btn custom-primary-btn my-manage-btn">Manage</a></td>
                    </tr>
                    <tr>
                        <td scope="row">4</td>
                        <td>Deploy to staging</td>
                        <td>Caramel</td>
                        <td>2025-05-26</td>
                        <td>2025-10-06</td>
                        <td>55 Days</td>
                        <td>Pending</td>
                        <td>Not yet submitted</td>
                        <td><a href="#" class="btn custom-primary-btn my-manage-btn">Manage</a></td>
                    </tr>
                    <tr>
                        <td scope="row">5</td>
                        <td>Fix UI bugs</td>
                        <td>Muning Meowzer</td>
                        <td>2025-05-26</td>
                        <td>2025-08-20</td>
                        <td>32 Days</td>
                        <td>Pending</td>
                        <td>Not yet submitted</td>
                        <td><a href="#" class="btn custom-primary-btn my-manage-btn">Manage</a></td>
                    </tr>
                </tbody>
            </table>
        <?php endif; ?>
    


        <?php if ($currentNavTab === "assignedGroupTask"): ?>
            <h6 class="text-muted">Total Group Task: 5</h6>
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
                        <th scope="col">Assigned Members</th>
                        <th scope="col">Assigned Date</th>
                        <th scope="col">Deadline</th>
                        <th scope="col">Milestone</th>
                        <th scope="col">Status</th>
                        <th scope="col">Approval Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">1</th>
                        <td>Write unit tests</td>
                        <td>2</td>
                        <td>2025-05-26</td>
                        <td>2025-09-23</td>
                        <td>93 Days</td>
                        <td>Completed</td>
                        <td>Approved</td>
                        <td><a href="#" class="btn custom-primary-btn my-manage-btn">Manage</a></td>
                    </tr>
                    <tr>
                        <td scope="row">2</td>
                        <td>Run security audit</td>
                        <td>3</td>
                        <td>2025-05-26</td>
                        <td>2025-09-07</td>
                        <td>58 Days</td>
                        <td>Completed</td>
                        <td>Approved</td>
                        <td><a href="#" class="btn custom-primary-btn my-manage-btn">Manage</a></td>
                    </tr>
                    <tr>
                        <td scope="row">3</td>
                        <td>Conduct code review</td>
                        <td>2</td>
                        <td>2025-05-26</td>
                        <td>2025-08-20</td>
                        <td>112 Days</td>
                        <td>Pending</td>
                        <td>Not yet submitted</td>
                        <td><a href="#" class="btn custom-primary-btn my-manage-btn">Manage</a></td>
                    </tr>
                    <tr>
                        <td scope="row">4</td>
                        <td>Deploy to staging</td>
                        <td>3</td>
                        <td>2025-05-26</td>
                        <td>2025-10-06</td>
                        <td>55 Days</td>
                        <td>Pending</td>
                        <td>Not yet submitted</td>
                        <td><a href="#" class="btn custom-primary-btn my-manage-btn">Manage</a></td>
                    </tr>
                    <tr>
                        <td scope="row">5</td>
                        <td>Fix UI bugs</td>
                        <td>3</td>
                        <td>2025-05-26</td>
                        <td>2025-08-20</td>
                        <td>32 Days</td>
                        <td>Pending</td>
                        <td>Not yet submitted</td>
                        <td><a href="#" class="btn custom-primary-btn my-manage-btn">Manage</a></td>
                    </tr>
                </tbody>
            </table>
        <?php endif; ?>
        
        <?php if ($currentNavTab === "submittedTask"): ?>
            <div class="modal fade" id="approveTaskModal" tabindex="-1" aria-labelledby="approveTaskModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <form action="approve-task.php" method="POST">
                        <div class="modal-header">
                        <h5 class="modal-title" id="approveTaskModalLabel">Approve this Task?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                        <div class="mb-3">
                            <label for="approveReason" class="form-label">Reason for Approving this Task</label>
                            <textarea class="form-control" id="approveReason" name="approveReason" rows="3" required></textarea>
                        </div>
                            <input type="hidden" name="task_id" value="123">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Approve Task</button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
    
            <div class="modal fade" id="rejectTaskModal" tabindex="-1" aria-labelledby="rejectTaskModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <form action="reject-task.php" method="POST">
                        <div class="modal-header">
                        <h5 class="modal-title" id="rejectTaskModalLabel">Reject this Task?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                        <div class="mb-3">
                            <label for="rejectReason" class="form-label">Reason for Rejecting this Task</label>
                            <textarea class="form-control" id="rejectReason" name="rejectReason" rows="3" required></textarea>
                        </div>
                            <input type="hidden" name="task_id" value="123">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Reject Task</button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>

            <h6 class="text-muted">Total Tasks: 5</h6>
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
                        <th scope="col">Task Type / Members</th>
                        <th scope="col">Assigned Date</th>
                        <th scope="col">Deadline</th>
                        <th scope="col">Milestone</th>
                        <th scope="col">Status</th>
                        <th scope="col">Approval Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead> 
                <tbody>
                    <tr>
                        <th scope="row">1</th>
                        <td>Write unit tests</td>
                        <td>Group / 2</td>
                        <td>2025-05-26</td>
                        <td>2025-09-23</td>
                        <td>93 Days</td>
                        <td>Completed</td>
                        <td>Pending Review</td>
                        <td>
                            <a href="#" class="btn btn-success my-manage-btn" data-bs-toggle="modal" data-bs-target="#approveTaskModal">Approved</a>
                            <a href="#" class="btn btn-danger my-manage-btn" data-bs-toggle="modal" data-bs-target="#rejectTaskModal">Rejected</a>
                            <a href="#" class="btn custom-primary-btn my-manage-btn" >Manage</a>
                        </td>
                    </tr>
                    <tr>
                        <td scope="row">2</td>
                        <td>Run security audit</td>
                        <td>Solo</td>
                        <td>2025-05-26</td>
                        <td>2025-09-07</td>
                        <td>58 Days</td>
                        <td>Completed</td>
                        <td>Pending Review</td>
                        <td>
                            <a href="#" class="btn btn-success my-manage-btn" data-bs-toggle="modal" data-bs-target="#approveTaskModal">Approved</a>
                            <a href="#" class="btn btn-danger my-manage-btn" data-bs-toggle="modal" data-bs-target="#rejectTaskModal">Rejected</a>
                            <a href="#" class="btn custom-primary-btn my-manage-btn">Manage</a>
                        </td>
                    </tr>
                    <tr>
                        <td scope="row">3</td>
                        <td>Conduct code review</td>
                        <td>Solo</td>
                        <td>2025-05-26</td>
                        <td>2025-08-20</td>
                        <td>112 Days</td>
                        <td>Completed</td>
                        <td>Pending Review</td>
                        <td>
                            <a href="#" class="btn btn-success my-manage-btn" data-bs-toggle="modal" data-bs-target="#approveTaskModal">Approved</a>
                            <a href="#" class="btn btn-danger my-manage-btn" data-bs-toggle="modal" data-bs-target="#rejectTaskModal">Rejected</a>
                            <a href="#" class="btn custom-primary-btn my-manage-btn">Manage</a>
                        </td>
                    </tr>
                    <tr>
                        <td scope="row">4</td>
                        <td>Deploy to staging</td>
                        <td>Group / 3</td>
                        <td>2025-05-26</td>
                        <td>2025-10-06</td>
                        <td>55 Days</td>
                        <td>Completed</td>
                        <td>Pending Review</td>
                        <td>
                            <a href="#" class="btn btn-success my-manage-btn" data-bs-toggle="modal" data-bs-target="#approveTaskModal">Approved</a>
                            <a href="#" class="btn btn-danger my-manage-btn" data-bs-toggle="modal" data-bs-target="#rejectTaskModal">Rejected</a>
                            <a href="#" class="btn custom-primary-btn my-manage-btn">Manage</a>
                        </td>
                    </tr>
                    <tr>
                        <td scope="row">5</td>
                        <td>Fix UI bugs</td>
                        <td>Group / 2</td>
                        <td>2025-05-26</td>
                        <td>2025-08-20</td>
                        <td>32 Days</td>
                        <td>Completed</td>
                        <td>Pending Review</td>
                        <td class="d-flex gap-2">
                            <a href="#" class="btn btn-success my-manage-btn" data-bs-toggle="modal" data-bs-target="#approveTaskModal">Approved</a>
                            <a href="#" class="btn btn-danger my-manage-btn" data-bs-toggle="modal" data-bs-target="#rejectTaskModal">Rejected</a>
                            <a href="#" class="btn custom-primary-btn my-manage-btn">Manage</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        <?php endif; ?>
        


        <?php if ($currentNavTab === "createTask"): ?>
            <div class="card custom-form-container">
                <div class="card-body p-5">
                    <h1 class="text-center mb-5">Create a Task</h1>
                    
                    <form>
                        <div class="mb-4">
                            <label for="taskname" class="form-label">Task Name</label>
                            <input type="text" class="form-control" id="taskname" placeholder="Enter task name" name="taskname">
                        </div>

                        <div class="mb-4">
                            <label for="taskDescription" class="form-label">Task Description</label>
                            <textarea type="text" class="form-control" id="taskDescription" style="height: 10rem;"
                                    placeholder="Enter task description" name="taskDescription"></textarea>
                        </div>

                        <div class="mb-5">
                            <label for="taskDeadline" class="form-label">Task Deadline</label>
                            <div class="input-group mb-3">
                                <input type="date" class="form-control" id="taskDeadline" placeholder="Enter a deadline" name="taskDeadline"/>
                                <span class="input-group-text p-2 my-bg-iconform-color-primary border-start-0" href="#" id="calendar-icon" style="cursor: pointer;">
                                    <img src="./public/images/calendar.png" alt="icon" style="width:20px; height:20px; filter: invert(1);">
                                </span>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label for="tasktype" class="form-label">Task Type</label>
                            <select class="form-select" id="tasktype">
                                <option selected disabled>Choose Task Type</option>
                                <option value="admin">Solo Task</option>
                                <option value="manager">Group Task</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="assignedMembers" class="form-label">Assign Members</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" id="assignedProjectManager" placeholder="Search Member" name="assignedMembers">
                                <a class="input-group-text p-2 my-bg-iconform-color-primary border-start-0" href="#">
                                    <img src="./public/images/magnifying-glass.png" alt="icon" style="width:20px; height:20px; filter: invert(1);">
                                </a>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label for="taskNote" class="form-label">Task Note</label>
                            <textarea type="text" class="form-control" id="taskNote" style="height: 10rem;"
                                    placeholder="Enter task note" name="taskNote"></textarea>
                        </div>

                        <div class="d-grid mb-2">
                            <button type="submit" class="btn btn-success">Save Project</button>
                        </div>

                        <div class="d-grid">
                            <button type="button" class="btn btn-danger">Cancel</button>
                        </div>
                    </form>
                </div>
            </div> 
        <?php endif ?>


        <?php if ($currentNavTab === "manageMembers"): ?>
            <h6 class="text-muted">Total Member: 5</h6>
            <div class="mb-3 d-flex justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <button href="#" class="btn custom-primary-btn filter-form-btn">Solo Task</button>
                    <button href="#" class="btn custom-primary-btn filter-form-btn">Group Task</button>
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
                        <th scope="col">Solo Task</th>
                        <th scope="col">Unsubmitted Solo Task</th>
                        <th scope="col">Submitted Solo Task</th>
                        <th scope="col">Approved Solo Task</th>
                        <th scope="col">Rejected Solo Task</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead> 
                <tbody>
                    <tr>
                        <th scope="row">1</th>
                        <td>Osgar Rivera</td>
                        <td>3</td>
                        <td>3</td>
                        <td>3</td>
                        <td>2</td>
                        <td>1</td>
                        <td>
                            <a href="#" class="btn custom-primary-btn my-manage-btn">Manage</a>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">1</th>
                        <td>Muning Rivera</td>
                        <td>2</td>
                        <td>1</td>
                        <td>2</td>
                        <td>1</td>
                        <td>1</td>
                        <td>
                            <a href="#" class="btn custom-primary-btn my-manage-btn">Manage</a>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">1</th>
                        <td>Caramel</td>
                        <td>2</td>
                        <td>2</td>
                        <td>2</td>
                        <td>2</td>
                        <td>1</td>
                        <td>
                            <a href="#" class="btn custom-primary-btn my-manage-btn">Manage</a>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">1</th>
                        <td>Choco Choco</td>
                        <td>3</td>
                        <td>1</td>
                        <td>2</td>
                        <td>3</td>
                        <td>1</td>
                        <td>
                            <a href="#" class="btn custom-primary-btn my-manage-btn">Manage</a>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">1</th>
                        <td>Whitiee Bobo</td>
                        <td>1</td>
                        <td>2</td>
                        <td>2</td>
                        <td>3</td>
                        <td>1</td>
                        <td>
                            <a href="#" class="btn custom-primary-btn my-manage-btn">Manage</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        <?php endif ?>

</div>