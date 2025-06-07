<?php

use App\Support\SessionService;

$successMessage = SessionService::getAlertMessage('success_message'); ?>
<?php if ($successMessage !== null) : ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> <?= $successMessage ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>

<?php $errorMessage = SessionService::getAlertMessage('error_message'); ?>
<?php if ($errorMessage !== null) : ?>
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Failed!</strong> <?= $errorMessage ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>