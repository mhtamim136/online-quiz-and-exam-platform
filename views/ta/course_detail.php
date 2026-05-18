<?php require __DIR__ . '/../partials/ta_header.php'; ?>

<div class="ta-card">
    <h2><?php echo taE($course['title']); ?></h2>
    <p><?php echo taE($course['description']); ?></p>
    <p class="ta-muted">Subject: <?php echo taE($course['subject_name']); ?> | Instructor: <?php echo taE($course['instructor_name']); ?> | Status: <?php echo taE($course['status']); ?></p>
    <div class="ta-actions">
        <a class="ta-button secondary" href="ta.php?action=enrolled_students&course_id=<?php echo (int)$course['id']; ?>">Students</a>
        <a class="ta-button secondary" href="ta.php?action=quizzes&course_id=<?php echo (int)$course['id']; ?>">Quizzes</a>
        <a class="ta-button secondary" href="ta.php?action=announcements&course_id=<?php echo (int)$course['id']; ?>">Announcements</a>
        <a class="ta-button secondary" href="ta.php?action=materials&course_id=<?php echo (int)$course['id']; ?>">Materials</a>
        <a class="ta-button secondary" href="ta.php?action=qa_board&course_id=<?php echo (int)$course['id']; ?>">Q&amp;A</a>
        <a class="ta-button secondary" href="ta.php?action=course_summary_report&course_id=<?php echo (int)$course['id']; ?>">Report</a>
    </div>
</div>

<div class="ta-grid">
    <div class="ta-stat"><span>Students</span><strong><?php echo taE($stats['students']['total']); ?></strong></div>
    <div class="ta-stat"><span>Quizzes</span><strong><?php echo taE($stats['quizzes']['total']); ?></strong></div>
    <div class="ta-stat"><span>Materials</span><strong><?php echo taE($stats['materials']['total']); ?></strong></div>
    <div class="ta-stat"><span>Q&amp;A Questions</span><strong><?php echo taE($stats['questions']['total']); ?></strong></div>
</div>

<div class="ta-card">
    <h3>Recent Quizzes</h3>
    <table class="ta-table">
        <thead><tr><th>Title</th><th>Type</th><th>Status</th><th>Attempts</th></tr></thead>
        <tbody>
            <?php foreach ($quizzes as $quiz): ?>
                <tr><td><?php echo taE($quiz['title']); ?></td><td><?php echo taE($quiz['quiz_type']); ?></td><td><?php echo taE($quiz['status']); ?></td><td><?php echo taE($quiz['attempt_count']); ?></td></tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../partials/ta_footer.php'; ?>
