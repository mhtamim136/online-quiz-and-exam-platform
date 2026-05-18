<div class="instructor-grid">
    <div class="instructor-stat"><span>Total Courses</span><strong><?php echo instructorE($stats['total_courses']['total']); ?></strong></div>
    <div class="instructor-stat"><span>Total Students</span><strong><?php echo instructorE($stats['total_students']['total']); ?></strong></div>
    <div class="instructor-stat"><span>Active Quizzes</span><strong><?php echo instructorE($stats['active_quizzes']['total']); ?></strong></div>
    <div class="instructor-stat"><span>Pending Enrollments</span><strong><?php echo instructorE($stats['pending_enrollments']['total']); ?></strong></div>
</div>
<div class="instructor-card">
    <h2>Recent Quiz Attempts</h2>
    <table class="instructor-table">
        <tr><th>Student</th><th>Quiz</th><th>Score</th><th>Completed</th></tr>
        <?php foreach ($recentAttempts as $attempt) { ?>
            <tr>
                <td><?php echo instructorE($attempt['student_name']); ?></td>
                <td><?php echo instructorE($attempt['quiz_title']); ?></td>
                <td><?php echo instructorE($attempt['score']); ?></td>
                <td><?php echo instructorE($attempt['completed_at']); ?></td>
            </tr>
        <?php } ?>
    </table>
</div>
