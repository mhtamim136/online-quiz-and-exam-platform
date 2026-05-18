<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Online Quiz &amp; Exam Platform</title>
    <link rel="stylesheet" href="views/assets/css/style.css?v=<?php echo filemtime(__DIR__ . '/../assets/css/style.css'); ?>">
</head>
<body class="login-page">
    <div class="login-panel auth-panel-compact">
        <div class="auth-heading">
            <h1>Welcome Back</h1>
            <p class="auth-muted">Login to continue to your dashboard.</p>
        </div>

        <div id="formError" class="form-error"></div>

        <?php $flash = getFlashMessage(); ?>
        <?php if ($flash): ?>
            <div class="message <?php echo e($flash['type']); ?>">
                <?php echo e($flash['message']); ?>
            </div>
        <?php endif; ?>

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
                <div class="password-field">
                    <input id="password" name="password" type="password">
                    <button class="password-toggle" type="button" data-target="password" aria-label="Show password" title="Show password">
                        <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6z" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            <circle cx="12" cy="12" r="3" fill="none" stroke="currentColor" stroke-width="2"></circle>
                        </svg>
                    </button>
                </div>
            </div>

            <button class="primary full-button">Login</button>
        </form>

        <div class="auth-links">
            <span>New to the platform?</span>
            <a href="register.php">Register</a>
        </div>
    </div>

    <script src="views/assets/js/admin.js?v=<?php echo filemtime(__DIR__ . '/../assets/js/admin.js'); ?>"></script>
</body>
</html>
