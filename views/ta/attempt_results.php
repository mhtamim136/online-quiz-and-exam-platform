<?php require __DIR__ . '/../partials/ta_header.php'; ?>

<div class="ta-card">
    <form method="get" action="ta.php" class="ta-filter-row">
        <input type="hidden" name="action" value="attempt_results">
        <select name="course_id">
            <option value="0">All assigned courses</option>
            <?php foreach ($courses as $course): ?>
                <option value="<?php echo (int)$course['id']; ?>" <?php echo (int)$courseId === (int)$course['id'] ? 'selected' : ''; ?>><?php echo taE($course['title']); ?></option>
            <?php endforeach; ?>
        </select>
        <select name="quiz_id">
            <option value="0">All quizzes</option>
            <?php foreach ($quizzes as $quiz): ?>
                <option value="<?php echo (int)$quiz['id']; ?>" <?php echo (int)$quizId === (int)$quiz['id'] ? 'selected' : ''; ?>><?php echo taE($quiz['title']); ?></option>
            <?php endforeach; ?>
        </select>
        <button class="ta-button">Filter</button>
    </form>
</div>

<div class="ta-card">
    <table class="ta-table">
        <thead><tr><th>Student</th><th>Course</th><th>Quiz</th><th>Score</th><th>Started</th><th>Completed</th><th>Result</th></tr></thead>
        <tbody>
            <?php foreach ($attempts as $attempt): ?>
                <tr>
                    <td><?php echo taE($attempt['student_name']); ?></td>
                    <td><?php echo taE($attempt['course_title']); ?></td>
                    <td><?php echo taE($attempt['quiz_title']); ?></td>
                    <td><?php echo taE($attempt['score']); ?> / <?php echo taE($attempt['total_marks']); ?></td>
                    <td><?php echo taE($attempt['started_at']); ?></td>
                    <td><?php echo taE($attempt['completed_at']); ?></td>
                    <td><?php echo (float)$attempt['score'] >= (float)$attempt['pass_mark'] ? 'Pass' : 'Fail'; ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (count($attempts) === 0): ?>
                <tr><td colspan="7">No attempts found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../partials/ta_footer.php'; ?>
