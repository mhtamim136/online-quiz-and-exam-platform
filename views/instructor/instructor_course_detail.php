<div class="instructor-card">
    <h2><?php echo instructorE($course['title']); ?></h2>
    <p><strong>Subject:</strong> <?php echo instructorE($course['subject_name']); ?></p>
    <p><strong>Status:</strong> <?php echo instructorE($course['status']); ?></p>
    <p><?php echo instructorE($course['description']); ?></p>
</div>
<div class="instructor-actions">
    <a class="instructor-btn" href="instructor.php?action=edit_course&id=<?php echo instructorE($course['id']); ?>">Edit</a>
    <a class="instructor-btn" href="instructor.php?action=enrolled_students&course_id=<?php echo instructorE($course['id']); ?>">Students</a>
    <a class="instructor-btn" href="instructor.php?action=enrollment_requests&course_id=<?php echo instructorE($course['id']); ?>">Requests</a>
    <a class="instructor-btn" href="instructor.php?action=assign_ta&course_id=<?php echo instructorE($course['id']); ?>">TA</a>
    <a class="instructor-btn" href="instructor.php?action=quiz_list&course_id=<?php echo instructorE($course['id']); ?>">Quizzes</a>
    <a class="instructor-btn" href="instructor.php?action=announcements&course_id=<?php echo instructorE($course['id']); ?>">Announcements</a>
    <a class="instructor-btn" href="instructor.php?action=materials&course_id=<?php echo instructorE($course['id']); ?>">Materials</a>
    <a class="instructor-btn" href="instructor.php?action=qa_board&course_id=<?php echo instructorE($course['id']); ?>">Q&amp;A</a>
</div>
