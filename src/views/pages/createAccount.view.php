<div class="container custom-container">
        <div class="card custom-form-container">
            <div class="card-body p-5">
                <h1 class="text-center mb-5">Create Account</h1>
                
                <form>
                    <div class="mb-4">
                        <label for="fullName" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="fullName" placeholder="Enter full name">
                    </div>

                    <div class="mb-4">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" placeholder="Enter username">
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" placeholder="Enter password">
                    </div>

                    <div class="mb-5">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role">
                            <option selected disabled>Choose role</option>
                            <option value="admin">Admin</option>
                            <option value="manager">Project Manager</option>
                            <option value="member">Member</option>
                        </select>
                    </div>

                    <div class="d-grid mb-2">
                        <button type="submit" class="btn btn-success">Save Account</button>
                    </div>

                    <div class="d-grid">
                        <button type="button" class="btn btn-danger">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>