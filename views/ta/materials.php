<?php require __DIR__ . '/../partials/ta_header.php'; ?>

<div class="ta-card">
    <h2><?php echo taE($course['title']); ?></h2>
    <?php if (!empty($errors)): ?>
        <div class="ta-errors"><?php foreach ($errors as $error): ?><div><?php echo taE($error); ?></div><?php endforeach; ?></div>
    <?php endif; ?>
    <form id="taMaterialForm" method="post" action="ta.php?action=materials&course_id=<?php echo (int)$course['id']; ?>" enctype="multipart/form-data">
        <input id="material_id" type="hidden" name="material_id" value="<?php echo (int)$old['material_id']; ?>">
        <div id="materialError" class="ta-inline-error"></div>
        <div class="ta-form-grid">
            <div class="ta-form-row">
                <label for="title">Title</label>
                <input id="title" name="title" type="text" value="<?php echo taE($old['title']); ?>">
            </div>
            <div class="ta-form-row">
                <label for="material_type">Material Type</label>
                <select id="material_type" name="material_type">
                    <?php foreach (array('document', 'link', 'video') as $type): ?>
                        <option value="<?php echo taE($type); ?>" <?php echo $old['material_type'] === $type ? 'selected' : ''; ?>><?php echo taE($type); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="ta-form-row">
                <label for="material_file">Upload File</label>
                <input id="material_file" name="material_file" type="file">
            </div>
            <div class="ta-form-row">
                <label for="external_link">External Link</label>
                <input id="external_link" name="external_link" type="text" value="<?php echo taE($old['external_link']); ?>">
            </div>
        </div>
        <?php if ($editingMaterial): ?>
            <p class="ta-muted">Current path/link: <?php echo taE($editingMaterial['file_path']); ?></p>
        <?php endif; ?>
        <button class="ta-button"><?php echo $editingMaterial ? 'Update Material' : 'Add Material'; ?></button>
        <?php if ($editingMaterial): ?>
            <a class="ta-button secondary" href="ta.php?action=materials&course_id=<?php echo (int)$course['id']; ?>">Cancel Edit</a>
        <?php endif; ?>
    </form>
</div>

<div class="ta-card">
    <h3>Materials</h3>
    <table class="ta-table">
        <thead><tr><th>Title</th><th>Type</th><th>Path/Link</th><th>Uploaded By</th><th>Actions</th></tr></thead>
        <tbody>
            <?php foreach ($materials as $material): ?>
                <tr>
                    <td><?php echo taE($material['title']); ?></td>
                    <td><?php echo taE($material['material_type']); ?></td>
                    <td><a href="<?php echo taE($material['file_path']); ?>" target="_blank"><?php echo taE($material['file_path']); ?></a></td>
                    <td><?php echo taE($material['uploaded_by_name']); ?></td>
                    <td>
                        <?php if ((int)$material['uploaded_by'] === taCurrentId()): ?>
                            <a href="ta.php?action=materials&course_id=<?php echo (int)$course['id']; ?>&edit_id=<?php echo (int)$material['id']; ?>">Edit</a>
                            <form class="ta-confirm-form" data-confirm-message="Delete this material?" method="post" action="ta.php?action=delete_material" style="display:inline;">
                                <input type="hidden" name="material_id" value="<?php echo (int)$material['id']; ?>">
                                <input type="hidden" name="course_id" value="<?php echo (int)$course['id']; ?>">
                                <button class="ta-button danger">Delete</button>
                            </form>
                        <?php else: ?>
                            View only
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (count($materials) === 0): ?>
                <tr><td colspan="5">No materials found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../partials/ta_footer.php'; ?>
