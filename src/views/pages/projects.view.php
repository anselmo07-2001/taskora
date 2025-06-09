<div class="container custom-container">
        <h2>Display All Projects Data</h2>
        <hr class="border-primary border-2 mb-4">

        <h6 class="text-muted">Total Project: 5</h6>
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
                <tr>
                    <th scope="row">1</th>
                    <td>Website Redesign</td>
                    <td>Sophia Carter</td>
                    <td>13</td>
                    <td>12</td>
                    <td>2025-08-15</td>
                    <td>0%</td>
                    <td>In progress</td>
                    <td><a href="#" class="btn custom-primary-btn my-manage-btn">Manage</a></td>
                </tr>
                <tr>
                    <td scope="row">2</td>
                    <td>Mobile App Launch</td>
                    <td>Sophia Carter</td>
                    <td>13</td>
                    <td>13</td>
                    <td>2025-09-01</td>
                    <td>0%</td>
                    <td>Pending</td>
                    <td><a href="#" class="btn custom-primary-btn my-manage-btn">Manage</a></td>
                </tr>
                <tr>
                    <td scope="row">3</td>
                    <td>Marketing Revamp</td>
                    <td>Liam Johnson</td>
                    <td>13</td>
                    <td>14</td>
                    <td>2025-10-01</td>
                    <td>0%</td>
                    <td>Pending</td>
                    <td><a href="#" class="btn custom-primary-btn my-manage-btn">Manage</a></td>
                </tr>
                 <tr>
                    <td scope="row">4</td>
                    <td>Data Migration</td>
                    <td>Liam Johnson</td>
                    <td>12</td>
                    <td>12</td>
                    <td>2025-11-15</td>
                    <td>0%</td>
                    <td>Pending</td>
                    <td><a href="#" class="btn custom-primary-btn my-manage-btn">Manage</a></td>
                </tr>
                 <tr>
                    <td scope="row">5</td>
                    <td>Customer Portal Up</td>
                    <td>Olivia Smith</td>
                    <td>12</td>
                    <td>14</td>
                    <td>2025-12-31</td>
                    <td>0%</td>
                    <td>Pending</td>
                    <td><a href="#" class="btn custom-primary-btn my-manage-btn">Manage</a></td>
                </tr>
            </tbody>
        </table>
    </div>