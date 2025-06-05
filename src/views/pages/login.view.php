
<div class="container login-custom">
        <div class="d-flex justify-content-center align-items-center">
                <div class="p-4 bg-light border rounded shadow" style="max-width: 400px; width: 100%;">
                    <h2 class="text mb-4">LOGIN</h2>
                    <form method="POST" action="<?php echo BASE_URL . "/index.php?page=login"; ?>">
                        <div class="mb-2 d-flex gap-4 justify-content-center align-items-center">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control <?php echo $usernameErr ? 'is-invalid' : ''; ?>" id="username" name="username">
                        </div>
                        <?php if (!empty($usernameErr)): ?> 
                            <div class="invalid-feedback d-block mb-3 text-end pe-3" style="font-size: 0.75rem;">
                                   <?php echo e($usernameErr); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="mb-2 d-flex gap-4 justify-content-center align-items-center" >
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control <?php echo $passwordErr ? 'is-invalid' : ''; ?>" id="password", name="password">
                        </div>
                        <?php if (!empty($passwordErr)): ?> 
                            <div class="invalid-feedback d-block mb-3 text-end pe-1" style="font-size: 0.75rem;">
                                     <?php echo e($passwordErr); ?>
                            </div>
                        <?php endif; ?>

                        <div class="d-grid">
                            <button type="submit" class="btn custom-primary-btn">Submit</button>
                        </div>
                    </form>
                </div>
        </div>
</div>