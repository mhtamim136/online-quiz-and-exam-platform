<?php include __DIR__ . '/instructor_messages.php'; ?>
<div class="instructor-card">
    <form method="post" enctype="multipart/form-data" class="instructor-form" data-instructor-validate>
        <label>Title</label>
        <input name="title" value="<?php echo instructorE($material['title']); ?>" data-instructor-label="Title">
        <label>Material Type</label>
        <select name="material_type">
            <option value="document" <?php echo $material['material_type'] === 'document' ? 'selected' : ''; ?>>Document</option>
            <option value="link" <?php echo $material['material_type'] === 'link' ? 'selected' : ''; ?>>Link</option>
            <option value="video" <?php echo $material['material_type'] === 'video' ? 'selected' : ''; ?>>Video</option>
        </select>
        <label>File</label>
        <input name="material_file" type="file">
        <div class="instructor-actions"><button type="submit">Save Material</button></div>
    </form>
</div>
