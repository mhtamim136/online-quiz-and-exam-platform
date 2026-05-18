<?php require __DIR__ . '/../partials/ta_header.php'; ?>

<div class="ta-card">
    <h2><?php echo taE($question['title']); ?></h2>
    <p><?php echo taE($question['body']); ?></p>
    <p class="ta-muted">Course: <?php echo taE($question['course_title']); ?> | Student: <?php echo taE($question['student_name']); ?> | Status: <?php echo (int)$question['is_resolved'] === 1 ? 'Resolved' : 'Open'; ?></p>
    <?php if ((int)$question['is_resolved'] === 0): ?>
        <form method="post" action="ta.php?action=resolve_question" class="ta-confirm-form" data-confirm-message="Mark this question as resolved?">
            <input type="hidden" name="question_id" value="<?php echo (int)$question['id']; ?>">
            <button class="ta-button secondary">Mark Resolved</button>
        </form>
    <?php endif; ?>
</div>

<div class="ta-card">
    <h3>Answers</h3>
    <table class="ta-table">
        <thead><tr><th>Author</th><th>Role</th><th>Answer</th><th>Endorsed</th><th></th></tr></thead>
        <tbody>
            <?php foreach ($answers as $answer): ?>
                <tr>
                    <td><?php echo taE($answer['author_name']); ?></td>
                    <td><?php echo taE($answer['author_role']); ?></td>
                    <td><?php echo taE($answer['body']); ?></td>
                    <td><?php echo (int)$answer['is_endorsed'] === 1 ? 'Yes' : 'No'; ?></td>
                    <td>
                        <?php if ((int)$answer['is_endorsed'] === 0): ?>
                            <form method="post" action="ta.php?action=endorse_answer">
                                <input type="hidden" name="answer_id" value="<?php echo (int)$answer['id']; ?>">
                                <input type="hidden" name="question_id" value="<?php echo (int)$question['id']; ?>">
                                <button class="ta-button secondary">Endorse</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (count($answers) === 0): ?>
                <tr><td colspan="5">No answers yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="ta-card">
    <h3>Reply</h3>
    <?php if (!empty($errors)): ?>
        <div class="ta-errors"><?php foreach ($errors as $error): ?><div><?php echo taE($error); ?></div><?php endforeach; ?></div>
    <?php endif; ?>
    <form id="taQaAnswerForm" method="post" action="ta.php?action=qa_detail&id=<?php echo (int)$question['id']; ?>">
        <div id="qaAnswerError" class="ta-inline-error"></div>
        <div class="ta-form-row">
            <label for="body">Answer</label>
            <textarea id="body" name="body"></textarea>
        </div>
        <button class="ta-button">Post Answer</button>
    </form>
</div>

<?php require __DIR__ . '/../partials/ta_footer.php'; ?>
