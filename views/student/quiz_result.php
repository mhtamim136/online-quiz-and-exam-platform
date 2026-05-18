<h1>Quiz Result</h1>

<section class="student-card">
    <h2><?php echo studentE($attempt['quiz_title']); ?></h2>
    <p><strong>Course:</strong> <?php echo studentE($attempt['course_title']); ?></p>
    <p><strong>Score:</strong> <?php echo studentE($attempt['score']); ?> / <?php echo studentE($attempt['total_marks']); ?></p>
    <p><strong>Pass Mark:</strong> <?php echo studentE($attempt['pass_mark']); ?></p>
    <p>
        <strong>Result:</strong>
        <?php if ((float)$attempt['score'] >= (float)$attempt['pass_mark']): ?>
            <span class="student-badge good">Pass</span>
        <?php else: ?>
            <span class="student-badge bad">Fail</span>
        <?php endif; ?>
    </p>
    <p><strong>Completed:</strong> <?php echo studentE($attempt['completed_at']); ?></p>
</section>

<section class="student-card">
    <h2>Question Breakdown</h2>
    <table class="student-table">
        <thead>
        <tr>
            <th>Question</th>
            <th>Your Answer</th>
            <th>Correct Answer</th>
            <th>Marks</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($breakdown as $row): ?>
            <tr>
                <td><?php echo studentE($row['question_text']); ?></td>
                <td><?php echo studentE($row['selected_text'] ?? 'Not answered'); ?></td>
                <td><?php echo studentE($row['correct_text']); ?></td>
                <td><?php echo studentE($row['marks']); ?></td>
                <td>
                    <?php if ((int)$row['selected_is_correct'] === 1): ?>
                        <span class="student-badge good">Correct</span>
                    <?php else: ?>
                        <span class="student-badge bad">Wrong</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>
