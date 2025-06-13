<div class="mb-5">
    <h6 class="mb-3">Add Project Note</h6>
    <form method="POST" action="<?= BASE_URL . "/index.php?" . http_build_query($baseUrl); ?>">
        <textarea style="height: 10rem;" class="w-100 form-control mb-2 <?= ($errors["projectnoteErr"] ?? "") ? 'is-invalid' : ''; ?>" rows="4" placeholder="Enter your project note here" name="projectNote"></textarea>
        <div class="d-flex justify-content-between">
            <div class="me-3 flex-grow-1">
                <?php if (!empty($errors["projectnoteErr"] ?? null)): ?> 
                    <div class="invalid-feedback d-block" style="font-size: 0.75rem;">
                        <?php echo $errors["projectnoteErr"]; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="d-flex align-items-start">
                <button class="btn custom-primary-btn align-self-start">Save Project Note</button>
            </div>
        </div> 
    </form> 
</div>
                
<!--  modal for edit project notes -->
<div class="modal fade" id="editProjectNoteModal" tabindex="-1" aria-labelledby="editProjectNoteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <form method="POST" action="<?= BASE_URL . "/index.php?" . http_build_query(["page" => "updateProjectNote"] + $baseUrl); ?>">
            <div class="modal-header">
                <h5 class="modal-title" id="editProjectNoteModalLabel">Edit this Project Note?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="updateValueNote" class="form-label">Update your Project Note</label>
                    <textarea class="form-control" id="updateValueNote" name="updateValueNote" rows="3" required></textarea>
                    <label id="editNoteMessage" class="text-danger mb-2 d-none">No changes were made to the project note.</label>
                </div>
                <input type="hidden" id="projectNoteId" name="projectNoteId">
                <input type="hidden" id="addedProjectNoteStatus" name="addedProjectNoteStatus">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success">Edit Project Note</button>
            </div>
        </form>
        </div>
    </div>
</div>


<?php foreach ($data["projectNotes"] AS $row): ?> 
    <div class="card mb-3">
        <div class="card-body position-relative">
            <div class="d-flex align-items-start">
                <img src="./public/images/usernote.png" class="rounded-circle me-3" alt="User avatar" style="height:3rem;">
                <div>
                    <h6 class="mb-0"><?= $row->fullname ?><sup><?= $row->role !== "admin" ? ' (' . $row->role . ')' : "" ?></sup></h6>
                    <small class="text-muted"><?= $row->projectnote_type . " on " . date("M d, Y, \a\\t h:i A", strtotime($row->created_at)); ?> </small>
                    <?php if ($row->created_at !== $row->edited_at): ?>
                        <small class="text-muted d-block">Last content modified <?= (new DateTime($row->edited_at))->format('M d, Y, \a\t h:i A'); ?></small>
                    <?php endif; ?>
                    <p class="mt-2 mb-0">
                        <?= $row->content ?>
                    </p>
                </div>
            </div>
            <?php if($currentUserSession["userId"] === $row->user_id || $currentUserSession["role"] === "project_manager" || $currentUserSession["role"] === "admin"): ?>
                <div class="dropdown position-absolute top-0 end-0 me-2 mt-2">
                    <button class="btn p-0 border-0 bg-transparent" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="d-flex flex-column align-items-center justify-content-center" style="width: 20px; height: 30px;">
                            <span class="bg-secondary rounded-circle" style="width: 4px; height: 4px; margin: 2px 0;"></span>
                            <span class="bg-secondary rounded-circle" style="width: 4px; height: 4px; margin: 2px 0;"></span>
                            <span class="bg-secondary rounded-circle" style="width: 4px; height: 4px; margin: 2px 0;"></span>
                        </div>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editProjectNoteModal" 
                                data-note-id="<?= e($row->id); ?>" data-note-text="<?= e($row->content); ?>" data-note-type="<?= e($row->projectnote_type); ?>" >
                                Edit
                            </button>
                        </li>
                        <li><button class="dropdown-item" >Delete</button></li>
                    </ul>
                </div>  
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>


<div class="d-flex justify-content-end">
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1">Previous</a>
            </li>
            <li class="page-item active">
                <a class="page-link page-link-mycolor" href="#">1 <span class="visually-hidden">(current)</span></a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">2</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">3</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">4</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">5</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">Next</a>
            </li>
        </ul>
    </nav>
</div>


<script>
    const form = document.querySelector('#editProjectNoteModal form');
    const textarea = document.querySelector('#updateValueNote');
    const hiddenInputAddedProjectNoteStatus = document.querySelector("#addedProjectNoteStatus");
    const hiddenInputProjectNoteId = document.querySelector('#projectNoteId');
    const messageLabel = document.querySelector('#editNoteMessage');
    const modal = document.getElementById('editProjectNoteModal');

    let originalNote = '';

    document.getElementById('editProjectNoteModal').addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        const noteText = button.getAttribute('data-note-text');
        const noteId = button.getAttribute('data-note-id');
        const noteType = button.getAttribute('data-note-type');

        originalNote = noteText;
        let parts = []

        if (noteType === "Update project status") {
             const match = noteText.match(/^(\[.*?\])\s?(.*)/);
             
             if (match) {
                parts = [match[1], match[2]];
                textarea.value = parts[1];
                originalNote = parts[1];
            } 
        }
        
        if (noteType !== "Update project status") {
            textarea.value = noteText;
        }

        hiddenInputProjectNoteId.value = noteId;
        hiddenInputAddedProjectNoteStatus.value = parts[0];
    });


    form.addEventListener('submit', function (e) {
        if (textarea.value.trim() === originalNote.trim()) {
            e.preventDefault();
            messageLabel.classList.remove('d-none'); // Show message
        }
    });

    modal.addEventListener('hidden.bs.modal', function () {
        messageLabel.classList.add('d-none'); // Also hide the message when modal is closed
    });
</script>