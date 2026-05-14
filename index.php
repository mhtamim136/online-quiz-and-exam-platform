<?php
require_once __DIR__ . '/config/helpers.php';

if (!isset($_SESSION['user_id'])) {
    redirect('login.php');
}

if ($_SESSION['role'] === 'admin') {
    redirect('admin.php?action=dashboard');
}

$role_name = ucfirst($_SESSION['role']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($role_name); ?> Area</title>
    <link rel="stylesheet" href="views/assets/css/style.css">
</head>
<body class="login-page">
    <div class="login-panel">
        <h1><?php echo e($role_name); ?> Dashboard</h1>
        <p>This shared login works. The <?php echo e($role_name); ?> dashboard will be built later by the assigned teammate.</p>
        <p><a class="btn primary" href="logout.php">Logout</a></p>
    </div>
</body>
</html>
