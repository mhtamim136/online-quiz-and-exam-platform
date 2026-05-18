<?php require __DIR__ . '/../partials/ta_header.php'; ?>

<div class="ta-card">
    <h2><?php echo taE($quiz['title']); ?></h2>
    <?php if (!empty($errors)): ?>
        <div class="ta-errors"><?php foreach ($errors as $error): ?><div><?php echo taE($error); ?></div><?php endforeach; ?></div>
    <?php endif; ?>
    <form id="taQuestionForm" method="post" action="<?php echo $editing ? 'ta.php?action=edit_question&id=' . (int)$question['id'] : 'ta.php?action=add_question&quiz_id=' . (int)$quiz['id']; ?>">
        <div id="questionError" class="ta-inline-error"></div>
        <div class="ta-form-row">
            <label for="question_text">Question Text</label>
            <textarea id="question_text" name="question_text"><?php echo taE($old['question_text']); ?></textarea>
        </div>
        <div class="ta-form-row">
            <label for="marks">Marks</label>
            <input id="marks" name="marks" type="text" value="<?php echo taE($old['marks']); ?>">
        </div>
        <div class="ta-form-grid">
            <?php for ($i = 0; $i < 4; $i++): ?>
                <div class="ta-form-row">
                    <label for="option<?php echo $i; ?>">Option <?php echo $i + 1; ?></label>
                    <input id="option<?php echo $i; ?>" name="options[]" type="text" value="<?php echo taE($old['options'][$i]); ?>">
                    <label><input name="correct_option" type="radio" value="<?php echo $i; ?>" <?php echo (string)$old['correct_option'] === (string)$i ? 'checked' : ''; ?>> Correct</label>
                </div>
            <?php endfor; ?>
        </div>
        <button class="ta-button"><?php echo $editing ? 'Update Question' : 'Add Question'; ?></button>
        <a class="ta-button secondary" href="ta.php?action=questions&quiz_id=<?php echo (int)$quiz['id']; ?>">Back</a>
    </form>
</div>

<?php require __DIR__ . '/../partials/ta_footer.php'; ?>
