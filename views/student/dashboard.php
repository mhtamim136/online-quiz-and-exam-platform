<h1>Student Dashboard</h1>
<p class="student-muted">Welcome, <?php echo studentE($_SESSION['student_name'] ?? 'Student'); ?>.</p>

<section class="student-grid">
    <div class="student-stat">
        <span>Total Enrolled</span>
        <strong><?php echo studentE($stats['total_enrolled']); ?></strong>
    </div>
    <div class="student-stat">
        <span>Active Courses</span>
        <strong><?php echo studentE($stats['active_enrollments']); ?></strong>
    </div>
    <div class="student-stat">
        <span>Pending Requests</span>
        <strong><?php echo studentE($stats['pending_enrollments']); ?></strong>
    </div>
    <div class="student-stat">
        <span>Recent Attempts</span>
        <strong><?php echo count($stats['recent_attempts']); ?></strong>
    </div>
</section>

<section class="student-card">
    <h2>My Enrolled Courses</h2>
    <?php if (empty($enrolledCourses)): ?>
        <p class="student-muted">No course enrollment found yet.</p>
    <?php else: ?>
        <table class="student-table">
            <thead>
            <tr>
                <th>Course</th>
                <th>Subject</th>
                <th>Status</th>
                <th>Next Quiz</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($enrolledCourses as $course): ?>
                <tr>
                    <td><?php echo studentE($course['title']); ?></td>
                    <td><?php echo studentE($course['subject_name']); ?></td>
                    <td><span class="student-badge"><?php echo studentE($course['status']); ?></span></td>
                    <td><?php echo studentE($course['next_quiz'] ?? 'No upcoming quiz'); ?></td>
                    <td><a href="student.php?action=course_detail&id=<?php echo studentE($course['id']); ?>">View</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>

<section class="student-grid two">
    <div class="student-card">
        <h2>Upcoming Quizzes</h2>
        <?php if (empty($stats['upcoming_quizzes'])): ?>
            <p class="student-muted">No quiz is currently available.</p>
        <?php else: ?>
            <table class="student-table">
                <thead>
                <tr>
                    <th>Quiz</th>
                    <th>Course</th>
                    <th>Type</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($stats['upcoming_quizzes'] as $quiz): ?>
                    <tr>
                        <td><a href="student.php?action=quiz_take&id=<?php echo studentE($quiz['id']); ?>"><?php echo studentE($quiz['title']); ?></a></td>
                        <td><?php echo studentE($quiz['course_title']); ?></td>
                        <td><?php echo studentE($quiz['quiz_type']); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <div class="student-card">
        <h2>Recent Attempts</h2>
        <?php if (empty($stats['recent_attempts'])): ?>
            <p class="student-muted">No quiz attempts yet.</p>
        <?php else: ?>
            <table class="student-table">
                <thead>
                <tr>
                    <th>Quiz</th>
                    <th>Score</th>
                    <th>Result</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($stats['recent_attempts'] as $attempt): ?>
                    <tr>
                        <td><?php echo studentE($attempt['quiz_title']); ?></td>
                        <td><?php echo studentE($attempt['score']); ?></td>
                        <td>
                            <?php if ((float)$attempt['score'] >= (float)$attempt['pass_mark']): ?>
                                <span class="student-badge good">Pass</span>
                            <?php else: ?>
                                <span class="student-badge bad">Fail</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>

<section class="student-card">
    <h2>Upcoming Doubt Sessions</h2>
    <?php if (empty($stats['upcoming_doubt_sessions'])): ?>
        <p class="student-muted">No upcoming booked doubt sessions.</p>
    <?php else: ?>
        <table class="student-table">
            <thead>
            <tr>
                <th>Session</th>
                <th>Course</th>
                <th>Schedule</th>
                <th>Location/Link</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($stats['upcoming_doubt_sessions'] as $session): ?>
                <tr>
                    <td><?php echo studentE($session['title']); ?></td>
                    <td><?php echo studentE($session['course_title']); ?></td>
                    <td><?php echo studentE($session['scheduled_at']); ?></td>
                    <td><?php echo studentE($session['location_or_link']); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>
