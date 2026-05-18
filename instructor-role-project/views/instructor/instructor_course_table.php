<table class="instructor-table">
    <tr><th>Title</th><th>Subject</th><th>Status</th><th>Students</th><th>Action</th></tr>
    <?php foreach ($courses as $course) { ?>
        <tr>
            <td><?php echo instructorE($course['title']); ?></td>
            <td><?php echo instructorE($course['subject_name']); ?></td>
            <td><?php echo instructorE($course['status']); ?></td>
            <td><?php echo instructorE($course['max_students']); ?></td>
            <td><a href="instructor.php?action=course_detail&id=<?php echo instructorE($course['id']); ?>">Open</a></td>
        </tr>
    <?php } ?>
</table>
