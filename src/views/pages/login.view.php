<div class="container login-custom">
        <div class="d-flex justify-content-center align-items-center">
                <div class="p-4 bg-light border rounded shadow" style="max-width: 400px; width: 100%;">
                    <h2 class="text mb-4">LOGIN</h2>
                    <form method="POST" action="<?php echo BASE_URL . "/index.php?page=login"; ?>">
                        <div class="mb-2 d-flex gap-4 justify-content-center align-items-center">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control <?php ($errors["usernameErr"] ?? "") ? 'is-invalid' : ''; ?>" id="username" name="username" value="<?php echo e($_POST["username"] ?? ""); ?>">
                        </div>
                        <?php if (!empty($errors["usernameErr"] ?? null)): ?> 
                            <div class="invalid-feedback d-block mb-3 text-end pe-3" style="font-size: 0.75rem;">
                                   <?php echo $errors["usernameErr"]; ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="mb-2 d-flex gap-4 justify-content-center align-items-center" >
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control <?php ($errors["passwordErr"] ?? "") ? 'is-invalid' : ''; ?>" id="password", name="password">
                        </div>
                        <?php if (!empty($errors["passwordErr"] ?? null)): ?> 
                            <div class="invalid-feedback d-block mb-3 text-end pe-1" style="font-size: 0.75rem;">
                                     <?php echo e($errors["passwordErr"]); ?>
                            </div>
                        <?php endif; ?>

                        <div class="d-grid">
                            <button type="submit" class="btn custom-primary-btn">Submit</button>
                        </div>
                    </form>
                </div>
        </div>
</div>