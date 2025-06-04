<div class="container login-custom">
        <div class="d-flex justify-content-center align-items-center">
                <div class="p-4 bg-light border rounded shadow" style="max-width: 400px; width: 100%;">
                    <h2 class="text mb-4">LOGIN</h2>
                    <form method="POST" action="<?php echo BASE_URL . "/index.php?page=login"; ?>">
                        <div class="mb-3 d-flex gap-4 justify-content-center align-items-center">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-4 d-flex gap-4 justify-content-center align-items-center" >
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password", name="password" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn custom-primary-btn">Submit</button>
                        </div>
                    </form>
                </div>
        </div>
</div>