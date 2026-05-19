<?php
require_once __DIR__ . '/../../models/PlatformAnnouncementModel.php';
global $conn;
$flash = taGetFlash();
$taPlatformAnnouncements = isset($conn) ? platformAnnouncementGetVisibleForRole($conn, 'ta', 5) : array();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo taE($pageTitle); ?> - TA</title>
    <link rel="stylesheet" href="views/assets/css/ta.css">
</head>
<body>
    <header class="ta-top-header">
        <div>
            <strong>Online Quiz &amp; Exam Platform</strong>
            <span>Teaching Assistant Panel</span>
        </div>
        <div class="ta-top-user">
            <div class="announcement-menu">
                <button class="announcement-toggle" type="button" aria-label="Platform announcements" title="Platform announcements">&#128276;</button>
                <div class="announcement-dropdown">
                    <strong>Platform Announcements</strong>
                    <?php if (empty($taPlatformAnnouncements)): ?>
                        <p class="announcement-empty">No platform announcements.</p>
                    <?php endif; ?>
                    <?php foreach ($taPlatformAnnouncements as $announcement): ?>
                        <div class="announcement-item">
                            <b><?php echo taE($announcement['title']); ?></b>
                            <p><?php echo taE($announcement['body']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <a class="ta-user-link" href="ta.php?action=profile"><?php echo taE($_SESSION['name']); ?></a>
            <a href="logout.php">Logout</a>
        </div>
    </header>
    <div class="ta-shell">
        <?php require __DIR__ . '/ta_sidebar.php'; ?>
        <main class="ta-main">
            <div class="ta-page-title">
                <h1><?php echo taE($pageTitle); ?></h1>
            </div>
            <?php if ($flash): ?>
                <div class="ta-message <?php echo taE($flash['type']); ?>"><?php echo taE($flash['message']); ?></div>
            <?php endif; ?>
