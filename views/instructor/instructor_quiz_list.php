<div class="instructor-actions">
    <a class="instructor-btn" href="instructor.php?action=create_quiz&course_id=<?php echo instructorE($course['id']); ?>">Create Quiz</a>
    <a class="instructor-btn secondary" href="instructor.php?action=question_bank&course_id=<?php echo instructorE($course['id']); ?>">Question Bank</a>
</div>
<table class="instructor-table">
    <tr><th>Title</th><th>Type</th><th>Status</th><th>Time</th><th>Pass Mark</th><th>Action</th></tr>
    <?php foreach ($quizzes as $quiz) { ?>
        <tr>
            <td><?php echo instructorE($quiz['title']); ?></td>
            <td><?php echo instructorE($quiz['quiz_type']); ?></td>
            <td><?php echo instructorE($quiz['status']); ?></td>
            <td><?php echo instructorE($quiz['time_limit_minutes']); ?> min</td>
            <td><?php echo instructorE($quiz['pass_mark']); ?></td>
            <td>
                <a href="instructor.php?action=edit_quiz&id=<?php echo instructorE($quiz['id']); ?>">Edit</a> |
                <a href="instructor.php?action=toggle_quiz&id=<?php echo instructorE($quiz['id']); ?>">Publish/Unpublish</a> |
                <a href="instructor.php?action=quiz_questions&quiz_id=<?php echo instructorE($quiz['id']); ?>">Questions</a> |
                <a href="instructor.php?action=quiz_attempts&quiz_id=<?php echo instructorE($quiz['id']); ?>">Attempts</a> |
                <a href="instructor.php?action=quiz_analytics&quiz_id=<?php echo instructorE($quiz['id']); ?>">Analytics</a>
            </td>
        </tr>
    <?php } ?>
</table>
