<?php
$flash = getFlashMessage();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($pageTitle); ?> - Admin</title>
    <link rel="stylesheet" href="views/assets/css/style.css">
</head>
<body>
    <header class="top-header">
        <div>
            <strong>Online Quiz &amp; Exam Platform</strong>
            <span>Admin Panel</span>
        </div>
        <div class="top-user">
            <span><?php echo e($_SESSION['name']); ?></span>
            <a href="logout.php">Logout</a>
        </div>
    </header>

    <div class="admin-shell">
        <?php require __DIR__ . '/admin_sidebar.php'; ?>
        <main class="admin-main">
            <div class="page-title-row">
                <h1><?php echo e($pageTitle); ?></h1>
            </div>

            <?php if ($flash): ?>
                <div class="message <?php echo e($flash['type']); ?>">
                    <?php echo e($flash['message']); ?>
                </div>
            <?php endif; ?>
