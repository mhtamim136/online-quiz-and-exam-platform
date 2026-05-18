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
        <a class="student-user-link" href="student.php?action=profile"><?php echo studentE($_SESSION['name'] ?? ($_SESSION['student_name'] ?? 'Student')); ?></a>
        <a class="student-logout-link" href="logout.php">Logout</a>
    </div>
</header>
<div class="student-layout">
