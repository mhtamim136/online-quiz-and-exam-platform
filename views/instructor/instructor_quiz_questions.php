<div class="instructor-actions">
    <a class="instructor-btn" href="instructor.php?action=add_question&quiz_id=<?php echo instructorE($quiz['id']); ?>">Add Question</a>
    <a class="instructor-btn secondary" href="instructor.php?action=quiz_list&course_id=<?php echo instructorE($quiz['course_id']); ?>">Back to Quizzes</a>
</div>
<table class="instructor-table">
    <tr><th>Question</th><th>Marks</th><th>Order</th><th>Action</th></tr>
    <?php foreach ($questions as $question) { ?>
        <tr><td><?php echo instructorE($question['question_text']); ?></td><td><?php echo instructorE($question['marks']); ?></td><td><?php echo instructorE($question['order_index']); ?></td><td><a href="instructor.php?action=edit_question&id=<?php echo instructorE($question['id']); ?>">Edit</a></td></tr>
    <?php } ?>
</table>
