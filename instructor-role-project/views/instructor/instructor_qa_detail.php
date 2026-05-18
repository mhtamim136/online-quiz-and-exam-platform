<?php include __DIR__ . '/instructor_messages.php'; ?>
<div class="instructor-card">
    <h2><?php echo instructorE($question['title']); ?></h2>
    <p><strong>Student:</strong> <?php echo instructorE($question['student_name']); ?></p>
    <p><?php echo instructorE($question['body']); ?></p>
</div>
<div class="instructor-card">
    <h2>Answers</h2>
    <?php foreach ($answers as $answer) { ?>
        <p><strong><?php echo instructorE($answer['user_name']); ?>:</strong> <?php echo instructorE($answer['body']); ?></p>
    <?php } ?>
    <form method="post" class="instructor-form" data-instructor-validate>
        <label>Your Answer</label>
        <textarea name="answer" data-instructor-label="Answer"></textarea>
        <div class="instructor-actions"><button type="submit">Post Answer</button></div>
    </form>
</div>
