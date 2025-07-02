
<div class="container custom-container">
        <?php require __DIR__ . "/../components/flashMessage.view.php" ?>

        <h1>Hi, <?php echo e($user["fullname"]); ?> <sup class="sup-lift fs-6 text-muted"><?= $user["role"] !== "admin" ? "(" . ucwords(str_replace('_', ' ', $user["role"])) . ")" : "" ?></sup> </h1>
        <hr class="border-primary border-2">
    
        <div class="container mt-5">
            <div class="row mb-4">

                <?php if ($user["role"] === "member"): ?>
                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                        <div class="card custom-card border rounded shadow p-3 lift-hover">
                            <div class="card-body d-flex flex-column align-items-center">
                                <img src="./public/images/scope.png" class="w-50 mb-2"/>
                                <p class="card-title fs-5">Projects</p>
                            </div>
                            <a href="<?php echo BASE_URL . "/index.php?page=projects" ?>" class="btn custom-primary-btn">Open</a>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                        <div class="card custom-card border rounded shadow p-3 lift-hover">
                            <div class="card-body d-flex flex-column align-items-center">
                                <img src="./public/images/notes.png" class="w-50 mb-2"/>
                                <p class="card-title fs-5">My Tasks </p>
                            </div>
                            <a href="<?php echo BASE_URL . "/index.php?page=mySoloTasks" ?>" class="btn custom-primary-btn">Open</a>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                        <div class="card custom-card border rounded shadow p-3 lift-hover">
                            <div class="card-body d-flex flex-column align-items-center">
                                <img src="./public/images/teamwork.png" class="w-50 mb-2"/>
                                <p class="card-title fs-5">My Group Tasks</p>
                            </div>
                            <a href="<?php echo BASE_URL . "/index.php?page=myGroupTasks" ?>" class="btn custom-primary-btn">Open</a>
                        </div>
                    </div>     
                <?php endif; ?>


                <?php if ($user["role"] === "project_manager"): ?>
                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                        <div class="card custom-card border rounded shadow p-3 lift-hover">
                            <div class="card-body d-flex flex-column align-items-center">
                                <img src="./public/images/scope.png" class="w-50 mb-2"/>
                                <p class="card-title fs-5">Projects</p>
                            </div>
                            <a href="<?php echo BASE_URL . "/index.php?page=projects" ?>" class="btn custom-primary-btn">Open</a>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                        <div class="card custom-card border rounded shadow p-3 lift-hover">
                            <div class="card-body d-flex flex-column align-items-center">
                                <img src="./public/images/choosing.png" class="w-50 mb-2"/>
                                <p class="card-title fs-5">Manage Members</p>
                            </div>
                            <a href="<?php echo BASE_URL . "/index.php?page=members" ?>" class="btn custom-primary-btn">Open</a>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                        <div class="card custom-card border rounded shadow p-3 lift-hover">
                            <div class="card-body d-flex flex-column align-items-center">
                                <img src="./public/images/documentation.png" class="w-50 mb-2"/>
                                <p class="card-title fs-5">Manage Tasks</p>
                            </div>
                            <a href="<?php echo BASE_URL . "/index.php?page=tasks" ?>" class="btn custom-primary-btn">Open</a>
                        </div>
                    </div>     
                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                        <div class="card custom-card border rounded shadow p-3 lift-hover">
                            <div class="card-body d-flex flex-column align-items-center">
                                <img src="./public/images/clipboard.png" class="w-50 mb-2"/>
                                <p class="card-title fs-5">Create Projects</p>
                            </div>
                            <a href="<?php echo BASE_URL . "/index.php?page=createProject" ?>" class="btn custom-primary-btn">Open</a>
                        </div>
                    </div>
                <?php endif; ?>


                <?php if ($user["role"] === "admin"): ?>
                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                        <div class="card custom-card border rounded shadow p-3 lift-hover">
                            <div class="card-body d-flex flex-column align-items-center">
                                <img src="./public/images/scope.png" class="w-50 mb-2"/>
                                <p class="card-title fs-5">Projects</p>
                            </div>
                            <a href="<?php echo BASE_URL . "/index.php?page=projects" ?>" class="btn custom-primary-btn">Open</a>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                        <div class="card custom-card border rounded shadow p-3 lift-hover">
                            <div class="card-body d-flex flex-column align-items-center">
                                <img src="./public/images/choosing.png" class="w-50 mb-2"/>
                                <p class="card-title fs-5">Members</p>
                            </div>
                            <a href="<?php echo BASE_URL . "/index.php?page=members" ?>" class="btn custom-primary-btn">Open</a>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                        <div class="card custom-card border rounded shadow p-3 lift-hover">
                            <div class="card-body d-flex flex-column align-items-center">
                                <img src="./public/images/documentation.png" class="w-50 mb-2"/>
                                <p class="card-title fs-5">Tasks</p>
                            </div>
                            <a href="<?php echo BASE_URL . "/index.php?page=tasks" ?>" class="btn custom-primary-btn">Open</a>
                        </div>
                    </div>

                
                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                        <div class="card custom-card border rounded shadow p-3 lift-hover">
                            <div class="card-body d-flex flex-column align-items-center">
                                <img src="./public/images/clipboard.png" class="w-50 mb-2"/>
                                <p class="card-title fs-5">Create Projects</p>
                            </div>
                            <a href="<?php echo BASE_URL . "/index.php?page=createProject" ?>" class="btn custom-primary-btn">Open</a>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                        <div class="card custom-card border rounded shadow p-3 lift-hover">
                            <div class="card-body d-flex flex-column align-items-center">
                                <img src="./public/images/add-friend.png" class="w-50 mb-2"/>
                                <p class="card-title fs-5">Create Account</p>
                            </div>
                            <a href="<?php echo BASE_URL . "/index.php?page=createAccount" ?>" class="btn custom-primary-btn">Open</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
   </div>