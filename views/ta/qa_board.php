<?php require __DIR__ . '/../partials/ta_header.php'; ?>

<div class="ta-card">
    <form method="get" action="ta.php" class="ta-filter-row">
        <input type="hidden" name="action" value="qa_board">
        <select name="course_id">
            <option value="0">All assigned courses</option>
            <?php foreach ($courses as $course): ?>
                <option value="<?php echo (int)$course['id']; ?>" <?php echo (int)$courseId === (int)$course['id'] ? 'selected' : ''; ?>><?php echo taE($course['title']); ?></option>
            <?php endforeach; ?>
        </select>
        <button class="ta-button">Filter</button>
    </form>
</div>

<div class="ta-card">
    <table class="ta-table">
        <thead><tr><th>Course</th><th>Student</th><th>Question</th><th>Answers</th><th>Status</th><th></th></tr></thead>
        <tbody>
            <?php foreach ($questions as $question): ?>
                <tr>
                    <td><?php echo taE($question['course_title']); ?></td>
                    <td><?php echo taE($question['student_name']); ?></td>
                    <td><?php echo taE($question['title']); ?></td>
                    <td><?php echo taE($question['answer_count']); ?></td>
                    <td><?php echo (int)$question['is_resolved'] === 1 ? 'Resolved' : 'Open'; ?></td>
                    <td><a href="ta.php?action=qa_detail&id=<?php echo (int)$question['id']; ?>">Open</a></td>
                </tr>
            <?php endforeach; ?>
            <?php if (count($questions) === 0): ?>
                <tr><td colspan="6">No Q&amp;A questions found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../partials/ta_footer.php'; ?>
