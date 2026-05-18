<?php require __DIR__ . '/../partials/ta_header.php'; ?>

<div class="ta-card">
    <h2><?php echo taE($course['title']); ?></h2>
    <?php if (!empty($errors)): ?>
        <div class="ta-errors"><?php foreach ($errors as $error): ?><div><?php echo taE($error); ?></div><?php endforeach; ?></div>
    <?php endif; ?>
    <form id="taQuizForm" method="post" action="<?php echo $editing ? 'ta.php?action=edit_practice_quiz&id=' . (int)$quiz['id'] : 'ta.php?action=create_practice_quiz&course_id=' . (int)$course['id']; ?>">
        <div id="quizError" class="ta-inline-error"></div>
        <div class="ta-form-grid">
            <div class="ta-form-row">
                <label for="title">Title</label>
                <input id="title" name="title" type="text" value="<?php echo taE($old['title']); ?>">
            </div>
            <div class="ta-form-row">
                <label for="time_limit_minutes">Time Limit Minutes</label>
                <input id="time_limit_minutes" name="time_limit_minutes" type="text" value="<?php echo taE($old['time_limit_minutes']); ?>">
            </div>
            <div class="ta-form-row">
                <label for="total_marks">Total Marks</label>
                <input id="total_marks" name="total_marks" type="text" value="<?php echo taE($old['total_marks']); ?>">
            </div>
            <div class="ta-form-row">
                <label for="pass_mark">Pass Mark</label>
                <input id="pass_mark" name="pass_mark" type="text" value="<?php echo taE($old['pass_mark']); ?>">
            </div>
            <div class="ta-form-row">
                <label for="available_from">Available From</label>
                <input id="available_from" name="available_from" type="text" value="<?php echo taE($old['available_from']); ?>" placeholder="2026-06-01 09:00:00">
            </div>
            <div class="ta-form-row">
                <label for="available_until">Available Until</label>
                <input id="available_until" name="available_until" type="text" value="<?php echo taE($old['available_until']); ?>" placeholder="2026-06-30 23:59:00">
            </div>
            <div class="ta-form-row full">
                <label for="description">Description</label>
                <textarea id="description" name="description"><?php echo taE($old['description']); ?></textarea>
            </div>
        </div>
        <button class="ta-button"><?php echo $editing ? 'Update Quiz' : 'Create Draft Practice Quiz'; ?></button>
        <a class="ta-button secondary" href="ta.php?action=quizzes&course_id=<?php echo (int)$course['id']; ?>">Back</a>
    </form>
</div>

<?php require __DIR__ . '/../partials/ta_footer.php'; ?>
