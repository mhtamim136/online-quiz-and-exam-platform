<?php include __DIR__ . '/instructor_messages.php'; ?>
<div class="instructor-card">
    <form method="post" enctype="multipart/form-data" class="instructor-form" data-instructor-validate>
        <label>Title</label>
        <input name="title" value="<?php echo instructorE($announcement['title']); ?>" data-instructor-label="Title">
        <label>Body</label>
        <textarea name="body" data-instructor-label="Body"><?php echo instructorE($announcement['body']); ?></textarea>
        <label>Attachment</label>
        <input name="attachment" type="file">
        <div class="instructor-actions"><button type="submit">Save Announcement</button></div>
    </form>
</div>
