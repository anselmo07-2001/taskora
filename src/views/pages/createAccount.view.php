
<div class="container custom-container">
        <div class="card custom-form-container">
            <div class="card-body p-5">
                <h1 class="text-center mb-5">Create Account</h1>
                
                <form method="POST" action="<?php echo BASE_URL . "/index.php?page=createAccount"; ?>">
                    <div class="mb-4">
                        <label for="fullName" class="form-label">Full Name</label>
                        <input type="text" class="form-control <?= ($errors["fullNameErr"] ?? "") ? 'is-invalid' : ''; ?>" id="fullName" placeholder="Enter full name" name="fullName" value="<?php echo e($_POST["fullName"] ?? ""); ?>">
                        <?php if (!empty($errors["fullNameErr"] ?? null)): ?> 
                            <div class="invalid-feedback d-block mb-2" style="font-size: 0.75rem;">
                                   <?php echo $errors["fullNameErr"]; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-4">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control <?= ($errors["usernameErr"] ?? "") ? 'is-invalid' : ''; ?>" id="username" placeholder="Enter username" name="username" value="<?php echo e($_POST["username"] ?? ""); ?>">
                         <?php if (!empty($errors["usernameErr"] ?? null)): ?> 
                            <div class="invalid-feedback d-block mb-2" style="font-size: 0.75rem;">
                                   <?php echo $errors["usernameErr"]; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control <?= ($errors["passwordErr"] ?? "") ? 'is-invalid' : ''; ?>" id="password" placeholder="Enter password" name="password" />
                        <?php if (!empty($errors["passwordErr"] ?? null)): ?> 
                            <div class="invalid-feedback d-block mb-2" style="font-size: 0.75rem;">
                                   <?php echo $errors["passwordErr"]; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control <?= ($errors["confirmPasswordErr"] ?? "") ? 'is-invalid' : ''; ?>" id="password" placeholder="Enter confirm password" name="confirmPassword" />
                        <?php if (!empty($errors["confirmPasswordErr"] ?? null)): ?> 
                            <div class="invalid-feedback d-block mb-2" style="font-size: 0.75rem;">
                                   <?php echo $errors["confirmPasswordErr"]; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-5">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select <?= ($errors["roleErr"] ?? "") ? 'is-invalid' : ''; ?>" id="role" name="role" value="<?php echo e($_POST["role"] ?? ""); ?>" >
                            <option selected disabled>Choose role</option>
                            <option value="admin" <?php echo ($_POST["role"] ?? "") === "admin" ? "selected" : ""; ?> >Admin</option>
                            <option value="project_manager" <?php echo ($_POST["role"] ?? "") === "project_manager" ? "selected" : ""; ?>>Project Manager</option>
                            <option value="member" <?php echo ($_POST["role"] ?? "") === "member" ? "selected" : ""; ?>>Member</option>
                        </select>
                        <?php if (!empty($errors["roleErr"] ?? null)): ?> 
                            <div class="invalid-feedback d-block mb-2" style="font-size: 0.75rem;">
                                   <?php echo $errors["roleErr"]; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="d-grid mb-2">
                        <button type="submit" class="btn btn-success">Save Account</button>
                    </div>

                    <div class="d-grid">
                        <a href="<?php echo BASE_URL . "/index.php?page=home"; ?>" class="btn btn-danger">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>