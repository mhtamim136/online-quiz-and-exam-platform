<?php require __DIR__ . '/../partials/ta_header.php'; ?>

<div class="ta-grid">
    <div class="ta-stat"><span>Assigned Courses</span><strong><?php echo taE($stats['assigned_courses']['total']); ?></strong></div>
    <div class="ta-stat"><span>Enrolled Students</span><strong><?php echo taE($stats['enrolled_students']['total']); ?></strong></div>
    <div class="ta-stat"><span>Total Quizzes</span><strong><?php echo taE($stats['quizzes']['total']); ?></strong></div>
    <div class="ta-stat"><span>My Practice Quizzes</span><strong><?php echo taE($stats['practice_quizzes']['total']); ?></strong></div>
    <div class="ta-stat"><span>Upcoming Sessions</span><strong><?php echo taE($stats['upcoming_sessions']['total']); ?></strong></div>
    <div class="ta-stat"><span>Open Questions</span><strong><?php echo taE($stats['open_questions']['total']); ?></strong></div>
    <div class="ta-stat"><span>At-Risk Students</span><strong><?php echo taE($stats['at_risk_students']['total']); ?></strong></div>
    <div class="ta-stat"><span>Risk Threshold</span><strong><?php echo taE($stats['threshold']); ?>%</strong></div>
</div>

<div class="ta-card">
    <h2>Welcome, <?php echo taE($profile['name']); ?></h2>
    <p class="ta-muted">Use this panel to support assigned courses, practice quizzes, Q&amp;A, and doubt sessions.</p>
</div>

<div class="ta-card">
    <h3>Upcoming Doubt Sessions</h3>
    <table class="ta-table">
        <thead><tr><th>Course</th><th>Title</th><th>Scheduled</th><th>Location/Link</th></tr></thead>
        <tbody>
            <?php foreach ($upcomingSessions as $session): ?>
                <tr>
                    <td><?php echo taE($session['course_title']); ?></td>
                    <td><?php echo taE($session['title']); ?></td>
                    <td><?php echo taE($session['scheduled_at']); ?></td>
                    <td><?php echo taE($session['location_or_link']); ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (count($upcomingSessions) === 0): ?>
                <tr><td colspan="4">No upcoming sessions.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="ta-card">
    <h3>Recent Student Questions</h3>
    <table class="ta-table">
        <thead><tr><th>Course</th><th>Student</th><th>Question</th><th>Status</th><th></th></tr></thead>
        <tbody>
            <?php foreach ($recentQuestions as $question): ?>
                <tr>
                    <td><?php echo taE($question['course_title']); ?></td>
                    <td><?php echo taE($question['student_name']); ?></td>
                    <td><?php echo taE($question['title']); ?></td>
                    <td><?php echo (int)$question['is_resolved'] === 1 ? 'Resolved' : 'Open'; ?></td>
                    <td><a href="ta.php?action=qa_detail&id=<?php echo (int)$question['id']; ?>">Open</a></td>
                </tr>
            <?php endforeach; ?>
            <?php if (count($recentQuestions) === 0): ?>
                <tr><td colspan="5">No student questions yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../partials/ta_footer.php'; ?>
