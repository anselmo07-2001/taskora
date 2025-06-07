
<div class="container custom-container">
        <div class="card custom-form-container">
            <div class="card-body p-5">
                <h1 class="text-center mb-5">Create a Project</h1>
                
                <form>
                    <div class="mb-4">
                    <label for="projectname" class="form-label">Project Name</label>
                    <input type="text" class="form-control" id="projectname" placeholder="Enter project name" name="projectname">
                    </div>

                    <div class="mb-4">
                        <label for="projectDescription" class="form-label">Project Description</label>
                        <textarea type="text" class="form-control" id="projectDescription" style="height: 10rem;"
                                   placeholder="Enter project description" name="projectDescription"></textarea>
                    </div>

                    <div class="mb-5">
                        <label for="projectDeadline" class="form-label">Project Deadline</label>
                        <div class="input-group mb-3">
                            <input type="date" class="form-control" id="projectDeadline" placeholder="Enter a deadline" name="projectDeadline"/>
                            <span class="input-group-text p-2 my-bg-iconform-color-primary border-start-0" href="#" id="calendar-icon" style="cursor: pointer;">
                                <img src="./public/images/calendar.png" alt="icon" style="width:20px; height:20px; filter: invert(1);">
                            </span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="assignedProjectManager" class="form-label">Assign Project Manager</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="assignedProjectManager" placeholder="Search Project Manager" name="assignedProjectManager">
                            <a class="input-group-text p-2 my-bg-iconform-color-primary border-start-0" href="#">
                                <img src="./public/images/magnifying-glass.png" alt="icon" style="width:20px; height:20px; filter: invert(1);">
                            </a>
                        </div>
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
                        <label for="projectProjectNote" class="form-label">Project Note</label>
                        <textarea type="text" class="form-control" id="projectProjectNote" style="height: 10rem;"
                                  placeholder="Enter project note" name="projectProjectNote"></textarea>
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
    </div>
</div>

<script>
        document.getElementById('calendar-icon').addEventListener('click', function() {
            document.getElementById('projectDeadline').showPicker();
        });
</script>