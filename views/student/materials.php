<h1>Materials: <?php echo studentE($course['title']); ?></h1>

<?php if (empty($materials)): ?>
    <div class="student-card">
        <p class="student-muted">No materials have been uploaded for this course.</p>
    </div>
<?php else: ?>
    <table class="student-table">
        <thead>
        <tr>
            <th>Title</th>
            <th>Type</th>
            <th>Uploaded By</th>
            <th>Date</th>
            <th>Open</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($materials as $material): ?>
            <tr>
                <td><?php echo studentE($material['title']); ?></td>
                <td><?php echo studentE($material['material_type']); ?></td>
                <td><?php echo studentE($material['uploaded_by_name']); ?></td>
                <td><?php echo studentE($material['created_at']); ?></td>
                <td><a href="<?php echo studentE($material['file_path']); ?>" target="_blank">Open</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
