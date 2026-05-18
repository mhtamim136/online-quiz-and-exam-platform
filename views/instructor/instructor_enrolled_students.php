<div class="instructor-card"><h2><?php echo instructorE($course['title']); ?></h2></div>
<table class="instructor-table">
    <tr><th>Student</th><th>Email</th><th>Enrolled At</th></tr>
    <?php foreach ($students as $student) { ?>
        <tr><td><?php echo instructorE($student['student_name']); ?></td><td><?php echo instructorE($student['student_email']); ?></td><td><?php echo instructorE($student['enrolled_at']); ?></td></tr>
    <?php } ?>
</table>
