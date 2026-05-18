<table class="instructor-table">
    <tr><th>Student</th><th>Email</th><th>Score</th><th>Started</th><th>Completed</th><th>Graded</th></tr>
    <?php foreach ($attempts as $attempt) { ?>
        <tr>
            <td><?php echo instructorE($attempt['student_name']); ?></td>
            <td><?php echo instructorE($attempt['student_email']); ?></td>
            <td><?php echo instructorE($attempt['score']); ?></td>
            <td><?php echo instructorE($attempt['started_at']); ?></td>
            <td><?php echo instructorE($attempt['completed_at']); ?></td>
            <td><?php echo (int) $attempt['is_graded'] === 1 ? 'Yes' : 'No'; ?></td>
        </tr>
    <?php } ?>
</table>
