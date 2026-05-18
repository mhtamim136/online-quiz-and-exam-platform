<?php require __DIR__ . '/../partials/ta_header.php'; ?>

<div class="ta-card">
    <form method="get" action="ta.php" class="ta-filter-row">
        <input type="hidden" name="action" value="quizzes">
        <select name="course_id">
            <option value="0">All assigned courses</option>
            <?php foreach ($courses as $course): ?>
                <option value="<?php echo (int)$course['id']; ?>" <?php echo (int)$courseId === (int)$course['id'] ? 'selected' : ''; ?>><?php echo taE($course['title']); ?></option>
            <?php endforeach; ?>
        </select>
        <select name="status">
            <option value="">All status</option>
            <option value="draft" <?php echo $status === 'draft' ? 'selected' : ''; ?>>Draft</option>
            <option value="published" <?php echo $status === 'published' ? 'selected' : ''; ?>>Published</option>
        </select>
        <select name="quiz_type">
            <option value="">All types</option>
            <option value="graded" <?php echo $quizType === 'graded' ? 'selected' : ''; ?>>Graded</option>
            <option value="practice" <?php echo $quizType === 'practice' ? 'selected' : ''; ?>>Practice</option>
        </select>
        <button class="ta-button">Filter</button>
        <?php if ((int)$courseId > 0): ?>
            <a class="ta-button secondary" href="ta.php?action=create_practice_quiz&course_id=<?php echo (int)$courseId; ?>">Create Practice Quiz</a>
        <?php endif; ?>
    </form>
</div>

<div class="ta-card">
    <table class="ta-table">
        <thead><tr><th>Course</th><th>Title</th><th>Type</th><th>Status</th><th>Total</th><th>Pass</th><th>Attempts</th><th>Actions</th></tr></thead>
        <tbody>
            <?php foreach ($quizzes as $quiz): ?>
                <tr>
                    <td><?php echo taE($quiz['course_title']); ?></td>
                    <td><?php echo taE($quiz['title']); ?></td>
                    <td><?php echo taE($quiz['quiz_type']); ?></td>
                    <td><?php echo taE($quiz['status']); ?></td>
                    <td><?php echo taE($quiz['total_marks']); ?></td>
                    <td><?php echo taE($quiz['pass_mark']); ?></td>
                    <td><?php echo taE($quiz['attempt_count']); ?></td>
                    <td>
                        <a href="ta.php?action=questions&quiz_id=<?php echo (int)$quiz['id']; ?>">Questions</a>
                        <?php if ((int)$quiz['created_by'] === taCurrentId() && $quiz['quiz_type'] === 'practice'): ?>
                            | <a href="ta.php?action=edit_practice_quiz&id=<?php echo (int)$quiz['id']; ?>">Edit</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (count($quizzes) === 0): ?>
                <tr><td colspan="8">No quizzes found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../partials/ta_footer.php'; ?>
