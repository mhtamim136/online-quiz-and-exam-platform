<?php include __DIR__ . '/instructor_messages.php'; ?>
<div class="instructor-card">
    <form method="post" class="instructor-form" data-instructor-validate>
        <label>Quiz Title</label>
        <input name="title" value="<?php echo instructorE($quiz['title']); ?>" data-instructor-label="Quiz title">
        <label>Description</label>
        <textarea name="description"><?php echo instructorE($quiz['description']); ?></textarea>
        <label>Quiz Type</label>
        <select name="quiz_type"><option value="graded" <?php echo $quiz['quiz_type'] === 'graded' ? 'selected' : ''; ?>>Graded</option><option value="practice" <?php echo $quiz['quiz_type'] === 'practice' ? 'selected' : ''; ?>>Practice</option></select>
        <label>Time Limit Minutes</label>
        <input name="time_limit_minutes" value="<?php echo instructorE($quiz['time_limit_minutes']); ?>" data-instructor-label="Time limit">
        <label>Total Marks</label>
        <input name="total_marks" value="<?php echo instructorE($quiz['total_marks']); ?>" data-instructor-label="Total marks">
        <label>Pass Mark</label>
        <input name="pass_mark" value="<?php echo instructorE($quiz['pass_mark']); ?>" data-instructor-label="Pass mark">
        <label>Available From</label>
        <input name="available_from" value="<?php echo instructorE($quiz['available_from']); ?>" placeholder="YYYY-MM-DD HH:MM:SS">
        <label>Available Until</label>
        <input name="available_until" value="<?php echo instructorE($quiz['available_until']); ?>" placeholder="YYYY-MM-DD HH:MM:SS">
        <div class="instructor-actions"><button type="submit">Save Quiz</button></div>
    </form>
</div>
