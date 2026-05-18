<div class="instructor-actions"><a class="instructor-btn" href="instructor.php?action=create_announcement&course_id=<?php echo instructorE($course['id']); ?>">Create Announcement</a></div>
<table class="instructor-table">
    <tr><th>Title</th><th>Body</th><th>Created</th><th>Action</th></tr>
    <?php foreach ($announcements as $announcement) { ?>
        <tr><td><?php echo instructorE($announcement['title']); ?></td><td><?php echo instructorE($announcement['body']); ?></td><td><?php echo instructorE($announcement['created_at']); ?></td><td><a href="instructor.php?action=edit_announcement&id=<?php echo instructorE($announcement['id']); ?>">Edit</a></td></tr>
    <?php } ?>
</table>
