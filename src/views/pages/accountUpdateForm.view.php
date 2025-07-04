<?php //var_dump($errors ?? "") ; ?>

<div class="container custom-container">
    <?php require __DIR__ . "/../components/flashMessage.view.php" ?>
    <?php require __DIR__ . "/../components/backButton.view.php" ?>
    <div class="card custom-form-container">
        <div class="card-body p-5">
            <h1 class="text-center mb-5">Update Account Information</h1>
            
            <form method="POST" action=<?= BASE_URL . "/index.php?" . http_build_query(["page" => "updateAccountInfoForm"]) ?> >
                <input type="hidden" name="userAccount" value="<?= e(json_encode($userAccount)) ?>"/>

                <div class="mb-4">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control <?= ($errors["usernameErr"] ?? "") ? 'is-invalid' : ''; ?>" id="username" placeholder="Enter username name"    name="username" value="<?= e($newUserName ?? $userAccount["username"]); ?>">
                    <?php if (!empty($errors["usernameErr"] ?? null)): ?> 
                            <div class="invalid-feedback d-block mb-2" style="font-size: 0.75rem;">
                                   <?php echo $errors["usernameErr"]; ?>
                            </div>
                    <?php endif; ?>
                </div>

                <div class="mb-4">
                    <label for="fullname" class="form-label">Full Name</label>
                    <input type="text" class="form-control <?= ($errors["fullNameErr"] ?? "") ? 'is-invalid' : ''; ?>" id="fullname" placeholder="Enter full name" name="fullname" value="<?= e($newFullName ?? $userAccount["fullname"]); ?>">
                    <?php if (!empty($errors["fullNameErr"] ?? null)): ?> 
                            <div class="invalid-feedback d-block mb-2" style="font-size: 0.75rem;">
                                   <?php echo $errors["fullNameErr"]; ?>
                            </div>
                    <?php endif; ?>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="password" placeholder="Enter password" name="password">
                    <?php if (!empty($errors["newPasswordErr"] ?? null)): ?> 
                            <div class="invalid-feedback d-block mb-2" style="font-size: 0.75rem;">
                                   <?php echo $errors["newPasswordErr"]; ?>
                            </div>
                    <?php endif; ?>
                </div>

                <div class="mb-5">
                    <label for="confirmPassword" class="form-label">Confirm New Password</label>
                    <input type="password" class="form-control" id="confirmPassword" placeholder="Enter confirm password" name="confirmPassword">
                    <?php if (!empty($errors["confirmPasswordErr"] ?? null)): ?> 
                            <div class="invalid-feedback d-block mb-2" style="font-size: 0.75rem;">
                                   <?php echo $errors["confirmPasswordErr"]; ?>
                            </div>
                    <?php endif; ?>
                </div>

                <div class="d-grid mb-2">
                    <button type="submit" class="btn btn-success">Update Account</button>
                </div>

                <div class="d-grid">
                    <a href="<?php echo BASE_URL . "/index.php?page=home"; ?>" class="btn btn-danger">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>