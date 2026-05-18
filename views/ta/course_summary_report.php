<?php require __DIR__ . '/../partials/ta_header.php'; ?>

<div class="ta-card">
    <h2><?php echo taE($course['title']); ?></h2>
    <p class="ta-muted">At-risk threshold: <?php echo taE($threshold); ?>%</p>
    <table class="ta-table">
        <tbody>
            <tr><th>Total Students</th><td><?php echo taE($report['total_students']['total']); ?></td></tr>
            <tr><th>Total Quizzes</th><td><?php echo taE($report['total_quizzes']['total']); ?></td></tr>
            <tr><th>Practice Quizzes Created By TA</th><td><?php echo taE($report['practice_quizzes']['total']); ?></td></tr>
            <tr><th>Total Attempts</th><td><?php echo taE($report['attempts']['total']); ?></td></tr>
            <tr><th>Average Score</th><td><?php echo taE(round((float)$report['attempts']['average_score'], 2)); ?>%</td></tr>
            <tr><th>At-Risk Students</th><td><?php echo taE($report['at_risk']['total']); ?></td></tr>
            <tr><th>Q&amp;A Questions</th><td><?php echo taE($report['qa_questions']['total']); ?></td></tr>
            <tr><th>Doubt Sessions</th><td><?php echo taE($report['doubt_sessions']['total']); ?></td></tr>
            <tr><th>Booked Doubt Session Seats</th><td><?php echo taE($report['booked_seats']['total']); ?></td></tr>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../partials/ta_footer.php'; ?>
