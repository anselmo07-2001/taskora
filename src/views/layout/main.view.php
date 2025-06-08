<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=\, initial-scale=1.0">
    <title>Taskora</title>
    <link href="./public/style.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-custom py-3">
        <div class="container">
            <a class="navbar-brand" href="<?php echo BASE_URL . "/index.php?page=home" ?>">
                <img src="./public/images/taskora.png" alt="Logo" class="custom-logo" class="d-inline-block align-text-top">
            </a>

            <?php if(!empty($user)): ?>
                <div class="dropdown">
                    <button class="btn custom-primary-btn d-flex align-items-center gap-2"
                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="./public/images/user.png" class="custom-icon-manage-account-btn"/>
                        Manage Account
                    </button>
                    <ul class="dropdown-menu mt-2">
                        <li class="text-center">
                            <form method="POST" action="<?php echo BASE_URL . "/index.php?page=logout"; ?>" >
                                <image src="./public/images/power-off.png" class="custom-icon-small-size custom-logout-btn"/>
                                <button href="login.html" class="btn">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
             <?php endif ?>
        </div>
    </nav>
    
     <?php echo $contents; ?>   

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
</body>
</html>