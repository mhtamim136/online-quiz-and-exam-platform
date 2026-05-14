<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Online Quiz &amp; Exam Platform</title>
    <link rel="stylesheet" href="views/assets/css/style.css">
</head>
<body class="login-page">
    <div class="login-panel">
        <h1>Login</h1>
        <p>Use the same login page for Admin, Student, Instructor, and TA accounts.</p>

        <div id="formError" class="form-error"></div>

        <?php if (!empty($errors)): ?>
            <div class="form-error server-errors">
                <?php foreach ($errors as $error): ?>
                    <div><?php echo e($error); ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form id="loginForm" method="post" action="login.php">
            <div class="form-row">
                <label for="email">Email</label>
                <input id="email" name="email" type="text" value="<?php echo isset($old['email']) ? e($old['email']) : ''; ?>">
            </div>

            <div class="form-row">
                <label for="password">Password</label>
                <input id="password" name="password" type="password">
            </div>

            <button class="primary">Login</button>
        </form>

        <p class="muted">Admin sample: admin@example.com / 136</p>
    </div>

    <script src="views/assets/js/admin.js"></script>
</body>
</html>
