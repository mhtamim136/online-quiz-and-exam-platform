<?php
$flash = taGetFlash();
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
