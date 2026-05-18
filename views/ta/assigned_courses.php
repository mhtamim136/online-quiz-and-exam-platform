<?php require __DIR__ . '/../partials/ta_header.php'; ?>

<div class="ta-card">
    <table class="ta-table">
        <thead>
            <tr><th>Course</th><th>Subject</th><th>Instructor</th><th>Status</th><th>Students</th><th>Actions</th></tr>
        </thead>
        <tbody>
            <?php foreach ($courses as $course): ?>
                <tr>
                    <td><?php echo taE($course['title']); ?></td>
                    <td><?php echo taE($course['subject_name']); ?></td>
                    <td><?php echo taE($course['instructor_name']); ?></td>
                    <td><?php echo taE($course['status']); ?></td>
                    <td><?php echo taE($course['enrollment_count']); ?></td>
                    <td>
                        <a href="ta.php?action=course_detail&id=<?php echo (int)$course['id']; ?>">Details</a> |
                        <a href="ta.php?action=create_practice_quiz&course_id=<?php echo (int)$course['id']; ?>">Practice Quiz</a> |
                        <a href="ta.php?action=doubt_sessions&course_id=<?php echo (int)$course['id']; ?>">Sessions</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (count($courses) === 0): ?>
                <tr><td colspan="6">No courses are assigned to you yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../partials/ta_footer.php'; ?>
