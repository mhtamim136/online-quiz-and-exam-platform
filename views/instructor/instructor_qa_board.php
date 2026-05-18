<div class="instructor-card"><h2><?php echo instructorE($course['title']); ?></h2></div>
<table class="instructor-table">
    <tr><th>Student</th><th>Question</th><th>Status</th><th>Created</th><th>Action</th></tr>
    <?php foreach ($questions as $question) { ?>
        <tr><td><?php echo instructorE($question['student_name']); ?></td><td><?php echo instructorE($question['title']); ?></td><td><?php echo (int) $question['is_resolved'] === 1 ? 'Resolved' : 'Open'; ?></td><td><?php echo instructorE($question['created_at']); ?></td><td><a href="instructor.php?action=qa_detail&id=<?php echo instructorE($question['id']); ?>">Answer</a></td></tr>
    <?php } ?>
</table>
