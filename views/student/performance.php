<h1>Performance Dashboard</h1>

<section class="student-grid">
    <div class="student-stat">
        <span>Total Attempts</span>
        <strong><?php echo studentE($summary['total_attempts']); ?></strong>
    </div>
    <div class="student-stat">
        <span>Average Score</span>
        <strong><?php echo studentE($summary['average_score']); ?></strong>
    </div>
    <div class="student-stat">
        <span>Pass Rate</span>
        <strong><?php echo studentE($summary['pass_rate']); ?>%</strong>
    </div>
    <div class="student-stat">
        <span>Subjects</span>
        <strong><?php echo count($subjectAverages); ?></strong>
    </div>
</section>

<section class="student-card">
    <h2>Scores Over Time</h2>
    <?php if (empty($scoresOverTime)): ?>
        <p class="student-muted">No completed attempts yet.</p>
    <?php else: ?>
        <table class="student-table">
            <thead>
            <tr>
                <th>Date</th>
                <th>Course</th>
                <th>Quiz</th>
                <th>Score</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($scoresOverTime as $score): ?>
                <tr>
                    <td><?php echo studentE($score['completed_at']); ?></td>
                    <td><?php echo studentE($score['course_title']); ?></td>
                    <td><?php echo studentE($score['quiz_title']); ?></td>
                    <td><?php echo studentE($score['score']); ?> / <?php echo studentE($score['total_marks']); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>

<section class="student-grid two">
    <div class="student-card">
        <h2>Average Score Per Subject</h2>
        <?php if (empty($subjectAverages)): ?>
            <p class="student-muted">No subject performance found.</p>
        <?php else: ?>
            <table class="student-table">
                <thead>
                <tr>
                    <th>Subject</th>
                    <th>Average Score</th>
                    <th>Attempts</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($subjectAverages as $subject): ?>
                    <tr>
                        <td><?php echo studentE($subject['subject_name']); ?></td>
                        <td><?php echo studentE(round((float)$subject['average_score'], 2)); ?></td>
                        <td><?php echo studentE($subject['attempt_count']); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <div class="student-card">
        <h2>Class Average Comparison</h2>
        <?php if (empty($classComparison)): ?>
            <p class="student-muted">No comparison data found.</p>
        <?php else: ?>
            <table class="student-table">
                <thead>
                <tr>
                    <th>Quiz</th>
                    <th>My Best</th>
                    <th>Class Average</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($classComparison as $row): ?>
                    <tr>
                        <td><?php echo studentE($row['quiz_title']); ?><br><span class="student-muted"><?php echo studentE($row['course_title']); ?></span></td>
                        <td><?php echo studentE($row['my_best_score']); ?></td>
                        <td><?php echo studentE(round((float)$row['class_average'], 2)); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>
