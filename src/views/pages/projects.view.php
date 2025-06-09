
<div class="container custom-container">
        <h2>Display All Projects Data</h2>
        <hr class="border-primary border-2 mb-4">

        <h6 class="text-muted">Total Project: <?php echo count($projects); ?></h6>
        <div class="mb-3 d-flex justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <button href="#" class="btn custom-primary-btn filter-form-btn">Due Today</button>
                <button href="#" class="btn custom-primary-btn filter-form-btn">Overdue</button>
                <button href="#" class="btn custom-primary-btn filter-form-btn">Upcoming</button>
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
                        <td><a href="#" class="btn custom-primary-btn my-manage-btn">Manage</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>