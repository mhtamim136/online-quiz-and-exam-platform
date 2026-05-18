<?php require __DIR__ . '/../partials/ta_header.php'; ?>

<div class="ta-card">
    <h2><?php echo taE($quiz['title']); ?></h2>
    <div class="ta-actions">
        <a class="ta-button" href="ta.php?action=add_question&quiz_id=<?php echo (int)$quiz['id']; ?>">Add Question</a>
        <a class="ta-button secondary" href="ta.php?action=question_bank&course_id=<?php echo (int)$quiz['course_id']; ?>">Question Bank</a>
        <a class="ta-button secondary" href="ta.php?action=quizzes&course_id=<?php echo (int)$quiz['course_id']; ?>">Back to Quizzes</a>
    </div>
</div>

<div class="ta-card">
    <table class="ta-table">
        <thead><tr><th>Order</th><th>Question</th><th>Marks</th><th>Created By</th><th>Actions</th></tr></thead>
        <tbody>
            <?php foreach ($questions as $question): ?>
                <tr>
                    <td><?php echo taE($question['order_index']); ?></td>
                    <td><?php echo taE($question['question_text']); ?></td>
                    <td><?php echo taE($question['marks']); ?></td>
                    <td><?php echo taE($question['created_by_name']); ?></td>
                    <td>
                        <?php if ((int)$question['created_by'] === taCurrentId()): ?>
                            <a href="ta.php?action=edit_question&id=<?php echo (int)$question['id']; ?>">Edit</a>
                            <form class="ta-confirm-form" data-confirm-message="Delete this question?" method="post" action="ta.php?action=delete_question" style="display:inline;">
                                <input type="hidden" name="question_id" value="<?php echo (int)$question['id']; ?>">
                                <button class="ta-button danger">Delete</button>
                            </form>
                        <?php else: ?>
                            View only
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (count($questions) === 0): ?>
                <tr><td colspan="5">No questions found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../partials/ta_footer.php'; ?>
