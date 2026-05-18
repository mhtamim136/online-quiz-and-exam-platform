<?php require __DIR__ . '/../partials/ta_header.php'; ?>

<div class="ta-card">
    <h2><?php echo taE($course['title']); ?></h2>
    <table class="ta-table">
        <thead><tr><th>Name</th><th>Email</th><th>Student ID</th><th>Program</th><th>Status</th><th>Enrolled At</th></tr></thead>
        <tbody>
            <?php foreach ($students as $student): ?>
                <tr>
                    <td><?php echo taE($student['name']); ?></td>
                    <td><?php echo taE($student['email']); ?></td>
                    <td><?php echo taE($student['student_id']); ?></td>
                    <td><?php echo taE($student['program']); ?></td>
                    <td><?php echo taE($student['status']); ?></td>
                    <td><?php echo taE($student['enrolled_at']); ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (count($students) === 0): ?>
                <tr><td colspan="6">No students found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../partials/ta_footer.php'; ?>
