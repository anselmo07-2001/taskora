<?php

function renderModal(array $config):string {
    $modalId = $config["id"] ?? "customModal";
    $title = $config["title"] ?? "Modal Title";
    $action = $config["action"] ?? "#";
    $textareaLabel = $config["textareaLabel"] ?? "Textarea";
    $textareaName = $config["textareaName"] ?? "content";
    $submitBtnText = $config["submitText"] ?? "Submit";
    $hiddenFields = $config["hiddenFields"] ?? [];
    $labelError = $config["labelError"] ?? "No changes were made";
    $modalTextAreaEl = $config["modalTextAreaEl"] ?? "modalTextAreaEl";
    $btnSubmit = $config["btnSubmit"] ?? "btn-success";

    ob_start();
?>

    <div class="modal fade" id="<?= $modalId ?>" tabindex="-1" aria-labelledby="<?= $modalId ?>Label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="<?= $action ?>">
                    <div class="modal-header">
                        <h5 class="modal-title" id="<?= $modalId ?>Label"><?= $title ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="<?= $modalTextAreaEl ?>" class="form-label"><?= $textareaLabel ?></label>
                            <textarea class="form-control" id="<?= $modalTextAreaEl ?>" name="<?= $textareaName ?>" rows="3" required></textarea>
                            <label id="modalMessage" class="text-danger mb-2 d-none"><?= $labelError ?></label>
                        </div>
                        <?php foreach ($hiddenFields as $row): ?>
                            <input type="hidden" name="<?= e($row["name"]) ?>" id="<?= e($row["id"]) ?>">
                        <?php endforeach; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn <?= $btnSubmit ?>"><?= $submitBtnText ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php return ob_get_clean();     
}
    
