<?php require __DIR__ . '/../partials/ta_header.php'; ?>

<div class="ta-card">
    <h2><?php echo taE($course['title']); ?></h2>
    <?php if (!empty($errors)): ?>
        <div class="ta-errors"><?php foreach ($errors as $error): ?><div><?php echo taE($error); ?></div><?php endforeach; ?></div>
    <?php endif; ?>
    <form id="taAnnouncementForm" method="post" action="ta.php?action=announcements&course_id=<?php echo (int)$course['id']; ?>">
        <input type="hidden" name="announcement_id" value="<?php echo (int)$old['announcement_id']; ?>">
        <div id="announcementError" class="ta-inline-error"></div>
        <div class="ta-form-row">
            <label for="title">Title</label>
            <input id="title" name="title" type="text" value="<?php echo taE($old['title']); ?>">
        </div>
        <div class="ta-form-row">
            <label for="body">Body</label>
            <textarea id="body" name="body"><?php echo taE($old['body']); ?></textarea>
        </div>
        <button class="ta-button"><?php echo $editingAnnouncement ? 'Update Announcement' : 'Post Announcement'; ?></button>
        <?php if ($editingAnnouncement): ?>
            <a class="ta-button secondary" href="ta.php?action=announcements&course_id=<?php echo (int)$course['id']; ?>">Cancel Edit</a>
        <?php endif; ?>
    </form>
</div>

<div class="ta-card">
    <h3>Past Announcements</h3>
    <table class="ta-table">
        <thead><tr><th>Title</th><th>Author</th><th>Created</th><th>Actions</th></tr></thead>
        <tbody>
            <?php foreach ($announcements as $announcement): ?>
                <tr>
                    <td><?php echo taE($announcement['title']); ?></td>
                    <td><?php echo taE($announcement['author_name']); ?></td>
                    <td><?php echo taE($announcement['created_at']); ?></td>
                    <td>
                        <?php if ((int)$announcement['author_id'] === taCurrentId()): ?>
                            <a href="ta.php?action=announcements&course_id=<?php echo (int)$course['id']; ?>&edit_id=<?php echo (int)$announcement['id']; ?>">Edit</a>
                            <form class="ta-confirm-form" data-confirm-message="Delete this announcement?" method="post" action="ta.php?action=delete_announcement" style="display:inline;">
                                <input type="hidden" name="announcement_id" value="<?php echo (int)$announcement['id']; ?>">
                                <input type="hidden" name="course_id" value="<?php echo (int)$course['id']; ?>">
                                <button class="ta-button danger">Delete</button>
                            </form>
                        <?php else: ?>
                            View only
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (count($announcements) === 0): ?>
                <tr><td colspan="4">No announcements found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../partials/ta_footer.php'; ?>
