<h1><?php echo studentE($question['title']); ?></h1>
<p class="student-muted">
    Course: <?php echo studentE($question['course_title']); ?> |
    Asked by <?php echo studentE($question['student_name']); ?> |
    <?php echo studentE($question['created_at']); ?>
</p>

<section class="student-card">
    <p><?php echo nl2br(studentE($question['body'])); ?></p>
    <p>
        Status:
        <?php if ((int)$question['is_resolved'] === 1): ?>
            <span class="student-badge good">Resolved</span>
        <?php else: ?>
            <span class="student-badge warn">Open</span>
        <?php endif; ?>
    </p>

    <?php if ((int)$question['student_id'] === studentCurrentId() && (int)$question['is_resolved'] === 0): ?>
        <form method="post" action="student.php?action=qa_detail&id=<?php echo studentE($question['id']); ?>">
            <button type="submit" class="student-btn primary">Mark as Resolved</button>
        </form>
    <?php endif; ?>
</section>

<section class="student-card">
    <h2>Answers</h2>
    <?php if (empty($answers)): ?>
        <p class="student-muted">No answers have been posted yet.</p>
    <?php else: ?>
        <?php foreach ($answers as $answer): ?>
            <article>
                <p><?php echo nl2br(studentE($answer['body'])); ?></p>
                <p class="student-muted">
                    <?php echo studentE($answer['author_name']); ?> (<?php echo studentE($answer['author_role']); ?>) |
                    <?php echo studentE($answer['created_at']); ?>
                    <?php if ((int)$answer['is_endorsed'] === 1): ?>
                        | <span class="student-badge good">Endorsed</span>
                    <?php endif; ?>
                </p>
            </article>
            <hr>
        <?php endforeach; ?>
    <?php endif; ?>
</section>
