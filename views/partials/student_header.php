<?php
require_once __DIR__ . '/../../models/PlatformAnnouncementModel.php';
global $conn;
$studentPlatformAnnouncements = isset($conn) ? platformAnnouncementGetVisibleForRole($conn, 'student', 5) : array();
$studentCourseAnnouncements = function_exists('studentGetHeaderCourseAnnouncements') ? studentGetHeaderCourseAnnouncements(studentCurrentId(), 5) : array();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo studentE($studentPageTitle); ?> | Student Panel</title>
    <link rel="stylesheet" href="views/assets/css/student.css">
    <script src="views/assets/js/student.js" defer></script>
</head>
<body>
<header class="student-topbar">
    <div>
        <strong>Online Quiz & Exam Platform</strong>
        <span>Student Role</span>
    </div>
    <div class="student-topbar-user">
        <div class="announcement-menu">
            <button class="announcement-toggle student-announcement-toggle" type="button" aria-label="Announcement" title="Announcement">&#128276; <span>Announcement</span></button>
            <div class="announcement-dropdown">
                <strong>Announcement</strong>
                <?php if (empty($studentPlatformAnnouncements) && empty($studentCourseAnnouncements)): ?>
                    <p class="announcement-empty">No announcements.</p>
                <?php endif; ?>
                <?php foreach ($studentPlatformAnnouncements as $announcement): ?>
                    <div class="announcement-item">
                        <b><?php echo studentE($announcement['title']); ?></b>
                        <p><?php echo studentE($announcement['body']); ?></p>
                    </div>
                <?php endforeach; ?>
                <?php foreach ($studentCourseAnnouncements as $announcement): ?>
                    <div class="announcement-item">
                        <b><?php echo studentE($announcement['course_title']); ?> - <?php echo studentE($announcement['title']); ?></b>
                        <p><?php echo studentE($announcement['body']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <a class="student-user-link" href="student.php?action=profile"><?php echo studentE($_SESSION['name'] ?? ($_SESSION['student_name'] ?? 'Student')); ?></a>
        <a class="student-logout-link" href="logout.php">Logout</a>
    </div>
</header>
<div class="student-layout">
