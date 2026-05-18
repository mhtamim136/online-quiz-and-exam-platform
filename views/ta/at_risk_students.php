<?php require __DIR__ . '/../partials/ta_header.php'; ?>

<div class="ta-card">
    <form id="taAtRiskForm" method="get" action="ta.php" class="ta-filter-row">
        <input type="hidden" name="action" value="at_risk_students">
        <select id="course_id" name="course_id">
            <option value="0">All assigned courses</option>
            <?php foreach ($courses as $course): ?>
                <option value="<?php echo (int)$course['id']; ?>" <?php echo (int)$courseId === (int)$course['id'] ? 'selected' : ''; ?>><?php echo taE($course['title']); ?></option>
            <?php endforeach; ?>
        </select>
        <input id="threshold" name="threshold" type="text" value="<?php echo taE($threshold); ?>">
        <button class="ta-button">Load</button>
    </form>
    <div id="atRiskError" class="ta-inline-error"></div>
</div>

<div class="ta-card">
    <table class="ta-table">
        <thead><tr><th>Student</th><th>Student ID</th><th>Course</th><th>Quiz</th><th>Score</th><th>Average</th></tr></thead>
        <tbody id="atRiskTableBody">
            <?php foreach ($students as $student): ?>
                <tr>
                    <td><?php echo taE($student['student_name']); ?></td>
                    <td><?php echo taE($student['student_id']); ?></td>
                    <td><?php echo taE($student['course_title']); ?></td>
                    <td><?php echo taE($student['quiz_title']); ?></td>
                    <td><?php echo taE($student['percentage_score']); ?>%</td>
                    <td><?php echo taE($student['average_score']); ?>%</td>
                </tr>
            <?php endforeach; ?>
            <?php if (count($students) === 0): ?>
                <tr><td colspan="6">No at-risk students found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../partials/ta_footer.php'; ?>
