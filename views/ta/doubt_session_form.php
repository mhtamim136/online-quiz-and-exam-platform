<?php require __DIR__ . '/../partials/ta_header.php'; ?>

<div class="ta-card">
    <h2><?php echo taE($course['title']); ?></h2>
    <?php if (!empty($errors)): ?>
        <div class="ta-errors"><?php foreach ($errors as $error): ?><div><?php echo taE($error); ?></div><?php endforeach; ?></div>
    <?php endif; ?>
    <form id="taDoubtSessionForm" method="post" action="<?php echo $editing ? 'ta.php?action=edit_doubt_session&id=' . (int)$session['id'] : 'ta.php?action=create_doubt_session&course_id=' . (int)$course['id']; ?>">
        <div id="doubtSessionError" class="ta-inline-error"></div>
        <div class="ta-form-grid">
            <div class="ta-form-row">
                <label for="title">Title</label>
                <input id="title" name="title" type="text" value="<?php echo taE($old['title']); ?>">
            </div>
            <div class="ta-form-row">
                <label for="scheduled_at">Scheduled At</label>
                <input id="scheduled_at" name="scheduled_at" type="text" value="<?php echo taE($old['scheduled_at']); ?>" placeholder="2026-06-01 16:00:00">
            </div>
            <div class="ta-form-row">
                <label for="duration_minutes">Duration Minutes</label>
                <input id="duration_minutes" name="duration_minutes" type="text" value="<?php echo taE($old['duration_minutes']); ?>">
            </div>
            <div class="ta-form-row">
                <label for="max_attendees">Max Attendees</label>
                <input id="max_attendees" name="max_attendees" type="text" value="<?php echo taE($old['max_attendees']); ?>">
            </div>
            <div class="ta-form-row full">
                <label for="location_or_link">Location or Meeting Link</label>
                <input id="location_or_link" name="location_or_link" type="text" value="<?php echo taE($old['location_or_link']); ?>">
            </div>
        </div>
        <button class="ta-button"><?php echo $editing ? 'Update Session' : 'Create Session'; ?></button>
        <a class="ta-button secondary" href="ta.php?action=doubt_sessions&course_id=<?php echo (int)$course['id']; ?>">Back</a>
    </form>
</div>

<?php require __DIR__ . '/../partials/ta_footer.php'; ?>
