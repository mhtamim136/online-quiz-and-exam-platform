<h1>Announcements: <?php echo studentE($course['title']); ?></h1>

<?php if (empty($announcements)): ?>
    <div class="student-card">
        <p class="student-muted">No announcements have been posted for this course.</p>
    </div>
<?php else: ?>
    <?php foreach ($announcements as $announcement): ?>
        <article class="student-card">
            <h2><?php echo studentE($announcement['title']); ?></h2>
            <p class="student-muted">
                <?php echo studentE($announcement['author_name']); ?> |
                <?php echo studentE($announcement['created_at']); ?>
            </p>
            <p><?php echo nl2br(studentE($announcement['body'])); ?></p>
        </article>
    <?php endforeach; ?>
<?php endif; ?>
