
<div class="container custom-container">
        <h1>Hi, <?php echo e($user["name"]); ?> <sup class="sup-lift fs-6 text-muted"><?= $user["role"] !== "Admin" ? "({$user['role']})" : "" ?></sup> </h1>
        <hr class="border-primary border-2">
    
        <div class="container mt-5">
            <div class="row mb-4">
                <div class="col-12 col-md-6 col-lg-4 mb-3">
                   <div class="card custom-card border rounded shadow p-3 lift-hover">
                        <div class="card-body d-flex flex-column align-items-center">
                            <img src="./public/images/scope.png" class="w-50 mb-2"/>
                            <p class="card-title fs-5">Projects</p>
                        </div>
                        <a href="showProjects.html" class="btn custom-primary-btn">Open</a>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4 mb-3">
                    <div class="card custom-card border rounded shadow p-3 lift-hover">
                        <div class="card-body d-flex flex-column align-items-center">
                            <img src="./public/images/choosing.png" class="w-50 mb-2"/>
                             <p class="card-title fs-5">Members</p>
                        </div>
                        <a href="#" class="btn custom-primary-btn">Open</a>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4 mb-3">
                    <div class="card custom-card border rounded shadow p-3 lift-hover">
                        <div class="card-body d-flex flex-column align-items-center">
                            <img src="./public/images/documentation.png" class="w-50 mb-2"/>
                             <p class="card-title fs-5">Tasks</p>
                        </div>
                        <a href="#" class="btn custom-primary-btn">Open</a>
                    </div>
                </div>

            
                <div class="col-12 col-md-6 col-lg-4 mb-3">
                    <div class="card custom-card border rounded shadow p-3 lift-hover">
                        <div class="card-body d-flex flex-column align-items-center">
                            <img src="./public/images/clipboard.png" class="w-50 mb-2"/>
                             <p class="card-title fs-5">Create Projects</p>
                        </div>
                        <a href="createProject.html" class="btn custom-primary-btn">Open</a>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4 mb-3">
                    <div class="card custom-card border rounded shadow p-3 lift-hover">
                        <div class="card-body d-flex flex-column align-items-center">
                            <img src="./public/images/add-friend.png" class="w-50 mb-2"/>
                             <p class="card-title fs-5">Create Account</p>
                        </div>
                        <a href="createAccount.html" class="btn custom-primary-btn">Open</a>
                    </div>
                </div>
            </div>
        </div>
   </div>