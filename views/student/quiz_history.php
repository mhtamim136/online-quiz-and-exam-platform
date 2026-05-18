<h1>Quiz History</h1>

<?php if (empty($attempts)): ?>
    <div class="student-card">
        <p class="student-muted">No quiz attempts found.</p>
    </div>
<?php else: ?>
    <table class="student-table">
        <thead>
        <tr>
            <th>Quiz</th>
            <th>Course</th>
            <th>Type</th>
            <th>Score</th>
            <th>Started</th>
            <th>Completed</th>
            <th>Result</th>
            <th>Detail</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($attempts as $attempt): ?>
            <tr>
                <td><?php echo studentE($attempt['quiz_title']); ?></td>
                <td><?php echo studentE($attempt['course_title']); ?></td>
                <td><?php echo studentE($attempt['quiz_type']); ?></td>
                <td><?php echo studentE($attempt['score']); ?> / <?php echo studentE($attempt['total_marks']); ?></td>
                <td><?php echo studentE($attempt['started_at']); ?></td>
                <td><?php echo studentE($attempt['completed_at']); ?></td>
                <td>
                    <?php if ((float)$attempt['score'] >= (float)$attempt['pass_mark']): ?>
                        <span class="student-badge good">Pass</span>
                    <?php else: ?>
                        <span class="student-badge bad">Fail</span>
                    <?php endif; ?>
                </td>
                <td><a href="student.php?action=quiz_result&attempt_id=<?php echo studentE($attempt['id']); ?>">View</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
