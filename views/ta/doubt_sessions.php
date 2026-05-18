<?php require __DIR__ . '/../partials/ta_header.php'; ?>

<div class="ta-card">
    <form method="get" action="ta.php" class="ta-filter-row">
        <input type="hidden" name="action" value="doubt_sessions">
        <select name="course_id">
            <option value="0">All assigned courses</option>
            <?php foreach ($courses as $course): ?>
                <option value="<?php echo (int)$course['id']; ?>" <?php echo (int)$courseId === (int)$course['id'] ? 'selected' : ''; ?>><?php echo taE($course['title']); ?></option>
            <?php endforeach; ?>
        </select>
        <button class="ta-button">Filter</button>
        <?php if ((int)$courseId > 0): ?>
            <a class="ta-button secondary" href="ta.php?action=create_doubt_session&course_id=<?php echo (int)$courseId; ?>">Create Session</a>
        <?php endif; ?>
    </form>
</div>

<div class="ta-card">
    <table class="ta-table">
        <thead><tr><th>Course</th><th>Title</th><th>Scheduled</th><th>Duration</th><th>Seats</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            <?php foreach ($sessions as $session): ?>
                <tr>
                    <td><?php echo taE($session['course_title']); ?></td>
                    <td><?php echo taE($session['title']); ?></td>
                    <td><?php echo taE($session['scheduled_at']); ?></td>
                    <td><?php echo taE($session['duration_minutes']); ?> min</td>
                    <td><?php echo taE($session['booked_count']); ?> / <?php echo taE($session['max_attendees']); ?></td>
                    <td><?php echo taE($session['status']); ?></td>
                    <td>
                        <a href="ta.php?action=doubt_session_bookings&id=<?php echo (int)$session['id']; ?>">Bookings</a> |
                        <a href="ta.php?action=edit_doubt_session&id=<?php echo (int)$session['id']; ?>">Edit</a>
                        <?php if ($session['status'] === 'scheduled'): ?>
                            <form class="ta-confirm-form" data-confirm-message="Cancel this session?" method="post" action="ta.php?action=cancel_doubt_session" style="display:inline;">
                                <input type="hidden" name="session_id" value="<?php echo (int)$session['id']; ?>">
                                <button class="ta-button danger">Cancel</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (count($sessions) === 0): ?>
                <tr><td colspan="7">No doubt sessions found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../partials/ta_footer.php'; ?>
