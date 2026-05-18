<div class="instructor-actions"><a class="instructor-btn" href="instructor.php?action=upload_material&course_id=<?php echo instructorE($course['id']); ?>">Upload Material</a></div>
<table class="instructor-table">
    <tr><th>Title</th><th>Type</th><th>File/Link</th><th>Created</th><th>Action</th></tr>
    <?php foreach ($materials as $material) { ?>
        <tr><td><?php echo instructorE($material['title']); ?></td><td><?php echo instructorE($material['material_type']); ?></td><td><?php echo instructorE($material['file_path']); ?></td><td><?php echo instructorE($material['created_at']); ?></td><td><a href="instructor.php?action=edit_material&id=<?php echo instructorE($material['id']); ?>">Edit</a></td></tr>
    <?php } ?>
</table>
