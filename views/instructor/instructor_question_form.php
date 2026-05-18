<?php include __DIR__ . '/instructor_messages.php'; ?>
<div class="instructor-card">
    <form method="post" class="instructor-form" data-instructor-validate>
        <label>Question Text</label>
        <textarea name="question_text" data-instructor-label="Question text"><?php echo instructorE($question['question_text']); ?></textarea>
        <label>Marks</label>
        <input name="marks" value="<?php echo instructorE($question['marks']); ?>" data-instructor-label="Marks">
        <?php for ($i = 0; $i < 4; $i++) { ?>
            <label class="instructor-option-row">
                Option <?php echo instructorE($i + 1); ?>
                <input name="option_<?php echo instructorE($i + 1); ?>" value="<?php echo instructorE($question['options'][$i]); ?>" data-instructor-label="Option <?php echo instructorE($i + 1); ?>">
                <input class="instructor-correct-radio" type="radio" name="correct_index" value="<?php echo instructorE($i); ?>" <?php echo (int) $question['correct_index'] === $i ? 'checked' : ''; ?>> Correct answer
            </label>
        <?php } ?>
        <div class="instructor-actions"><button type="submit">Save Question</button></div>
    </form>
</div>
