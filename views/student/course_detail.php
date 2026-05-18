<h1><?php echo studentE($course['title']); ?></h1>
<p class="student-muted"><?php echo studentE($course['subject_name']); ?> | Instructor: <?php echo studentE($course['instructor_name']); ?></p>

<section class="student-card">
    <h2>Course Information</h2>
    <p><?php echo nl2br(studentE($course['description'])); ?></p>
    <p><strong>Enrollment Type:</strong> <?php echo studentE($course['enrollment_type']); ?></p>
    <p><strong>Enrolled Students:</strong> <?php echo studentE($course['enrolled_count']); ?> / <?php echo studentE($course['max_students']); ?></p>
    <p><strong>Your Status:</strong> <?php echo $course['my_status'] ? studentE($course['my_status']) : 'Not enrolled'; ?></p>

    <div class="student-actions">
        <?php if (!$course['my_status']): ?>
            <form method="post" action="student.php?action=course_detail&id=<?php echo studentE($course['id']); ?>">
                <input type="hidden" name="course_action" value="enroll">
                <button type="submit" class="student-btn primary">Enroll</button>
            </form>
        <?php elseif ($course['my_status'] === 'active'): ?>
            <form method="post" action="student.php?action=course_detail&id=<?php echo studentE($course['id']); ?>">
                <input type="hidden" name="course_action" value="drop">
                <button type="submit" class="student-btn danger">Drop Course</button>
            </form>
            <a class="student-btn secondary" href="student.php?action=qa_board&course_id=<?php echo studentE($course['id']); ?>">Q&A Board</a>
            <a class="student-btn secondary" href="student.php?action=materials&course_id=<?php echo studentE($course['id']); ?>">Materials</a>
            <a class="student-btn secondary" href="student.php?action=announcements&course_id=<?php echo studentE($course['id']); ?>">Announcements</a>
        <?php endif; ?>
    </div>
</section>

<section class="student-grid two">
    <div class="student-card">
        <h2>Assigned TA</h2>
        <?php if (empty($tas)): ?>
            <p class="student-muted">No TA assigned yet.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($tas as $ta): ?>
                    <li><?php echo studentE($ta['name']); ?> (<?php echo studentE($ta['email']); ?>)</li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <div class="student-card">
        <h2>Latest Announcements</h2>
        <?php if (empty($announcements)): ?>
            <p class="student-muted">No announcements available.</p>
        <?php else: ?>
            <?php foreach ($announcements as $announcement): ?>
                <p><strong><?php echo studentE($announcement['title']); ?></strong><br><?php echo studentE($announcement['created_at']); ?></p>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<section class="student-grid two">
    <div class="student-card">
        <h2>Materials</h2>
        <?php if (empty($materials)): ?>
            <p class="student-muted">No materials available.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($materials as $material): ?>
                    <li><?php echo studentE($material['title']); ?> <span class="student-badge"><?php echo studentE($material['material_type']); ?></span></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <div class="student-card">
        <h2>Quizzes</h2>
        <?php if (empty($quizzes)): ?>
            <p class="student-muted">No published quizzes available.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($quizzes as $quiz): ?>
                    <li>
                        <a href="student.php?action=quiz_take&id=<?php echo studentE($quiz['id']); ?>"><?php echo studentE($quiz['title']); ?></a>
                        <span class="student-badge"><?php echo studentE($quiz['quiz_type']); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</section>
