<?php require __DIR__ . '/../partials/admin_header.php'; ?>

<div class="panel">
    <h2><?php echo $editAnnouncement ? 'Edit Announcement' : 'Create Announcement'; ?></h2>
    <div id="formError" class="form-error"></div>

    <?php if (!empty($errors)): ?>
        <div class="form-error server-errors">
            <?php foreach ($errors as $error): ?>
                <div><?php echo e($error); ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form id="announcementForm" method="post" action="admin.php?action=platform_announcements">
        <input type="hidden" name="announcement_action" value="<?php echo $editAnnouncement ? 'edit' : 'create'; ?>">
        <?php if ($editAnnouncement): ?>
            <input type="hidden" name="announcement_id" value="<?php echo e($editAnnouncement['id']); ?>">
        <?php endif; ?>

        <div class="form-grid">
            <div class="form-row">
                <label for="title">Title</label>
                <input id="title" name="title" type="text" value="<?php echo $editAnnouncement ? e($editAnnouncement['title']) : ''; ?>">
            </div>
            <div class="form-row">
                <label for="audience">Audience</label>
                <select id="audience" name="audience">
                    <?php $audienceValue = $editAnnouncement ? $editAnnouncement['audience'] : 'all'; ?>
                    <option value="all" <?php echo $audienceValue === 'all' ? 'selected' : ''; ?>>All</option>
                    <option value="students" <?php echo $audienceValue === 'students' ? 'selected' : ''; ?>>Students</option>
                    <option value="instructors" <?php echo $audienceValue === 'instructors' ? 'selected' : ''; ?>>Instructors</option>
                    <option value="tas" <?php echo $audienceValue === 'tas' ? 'selected' : ''; ?>>TAs</option>
                </select>
            </div>
            <div class="form-row">
                <label for="status">Status</label>
                <?php $statusValue = $editAnnouncement ? $editAnnouncement['status'] : 'draft'; ?>
                <select id="status" name="status">
                    <option value="draft" <?php echo $statusValue === 'draft' ? 'selected' : ''; ?>>Draft</option>
                    <option value="published" <?php echo $statusValue === 'published' ? 'selected' : ''; ?>>Published</option>
                    <option value="archived" <?php echo $statusValue === 'archived' ? 'selected' : ''; ?>>Archived</option>
                </select>
            </div>
            <div class="form-row">
                <label for="publish_from">Publish From</label>
                <input id="publish_from" name="publish_from" type="text" value="<?php echo $editAnnouncement ? e($editAnnouncement['publish_from']) : ''; ?>" placeholder="YYYY-MM-DD HH:MM:SS">
            </div>
            <div class="form-row">
                <label for="publish_until">Publish Until</label>
                <input id="publish_until" name="publish_until" type="text" value="<?php echo $editAnnouncement ? e($editAnnouncement['publish_until']) : ''; ?>" placeholder="YYYY-MM-DD HH:MM:SS">
            </div>
        </div>

        <div class="form-row">
            <label for="body">Body</label>
            <textarea id="body" name="body"><?php echo $editAnnouncement ? e($editAnnouncement['body']) : ''; ?></textarea>
        </div>

        <button class="primary"><?php echo $editAnnouncement ? 'Update Announcement' : 'Create Announcement'; ?></button>
        <?php if ($editAnnouncement): ?>
            <a class="btn" href="admin.php?action=platform_announcements">Cancel</a>
        <?php endif; ?>
    </form>
</div>

<div class="panel">
    <h2>Platform Announcements</h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Audience</th>
                    <th>Status</th>
                    <th>Author</th>
                    <th>Publish Window</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($announcements)): ?>
                    <tr><td colspan="7">No platform announcements found.</td></tr>
                <?php endif; ?>
                <?php foreach ($announcements as $announcement): ?>
                    <tr>
                        <td><?php echo e($announcement['title']); ?></td>
                        <td><?php echo e($announcement['audience']); ?></td>
                        <td><?php echo e($announcement['status']); ?></td>
                        <td><?php echo e($announcement['author_name']); ?></td>
                        <td><?php echo e($announcement['publish_from']); ?> to <?php echo e($announcement['publish_until']); ?></td>
                        <td><?php echo e($announcement['created_at']); ?></td>
                        <td>
                            <a class="btn small" href="admin.php?action=platform_announcements&edit_id=<?php echo e($announcement['id']); ?>">Edit</a>
                            <?php if ($announcement['status'] !== 'archived'): ?>
                                <form class="inline-form confirm-form" data-confirm="Archive this announcement?" method="post" action="admin.php?action=platform_announcements">
                                    <input type="hidden" name="announcement_action" value="archive">
                                    <input type="hidden" name="announcement_id" value="<?php echo e($announcement['id']); ?>">
                                    <button class="small">Archive</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/../partials/admin_footer.php'; ?>
