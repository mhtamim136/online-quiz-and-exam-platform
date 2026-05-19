<?php
require_once __DIR__ . '/../../models/PlatformAnnouncementModel.php';
global $conn;
$instructorPlatformAnnouncements = isset($conn) ? platformAnnouncementGetVisibleForRole($conn, 'instructor', 5) : array();
?>
<main class="instructor-main">
    <header class="instructor-topbar">
        <h1><?php echo instructorE($pageTitle); ?></h1>
        <div class="instructor-topbar-user">
            <div class="announcement-menu">
                <button class="announcement-toggle" type="button" aria-label="Platform announcements" title="Platform announcements">&#128276;</button>
                <div class="announcement-dropdown">
                    <strong>Platform Announcements</strong>
                    <?php if (empty($instructorPlatformAnnouncements)): ?>
                        <p class="announcement-empty">No platform announcements.</p>
                    <?php endif; ?>
                    <?php foreach ($instructorPlatformAnnouncements as $announcement): ?>
                        <div class="announcement-item">
                            <b><?php echo instructorE($announcement['title']); ?></b>
                            <p><?php echo instructorE($announcement['body']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <a class="instructor-user-link" href="instructor.php?action=profile"><?php echo instructorE($_SESSION['name'] ?? ($_SESSION['instructor_name'] ?? 'Instructor')); ?></a>
            <a class="instructor-logout-link" href="logout.php">Logout</a>
        </div>
    </header>
    <section class="instructor-content">
