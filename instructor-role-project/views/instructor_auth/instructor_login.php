<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Instructor Login</title>
    <link rel="stylesheet" href="views/assets/css/instructor.css">
</head>
<body class="instructor-login-page">
    <div class="instructor-login-box">
        <h1>Instructor Login</h1>
        <?php if (count($errors) > 0) { ?>
            <div class="instructor-alert error">
                <?php foreach ($errors as $error) { ?>
                    <div><?php echo instructorE($error); ?></div>
                <?php } ?>
            </div>
        <?php } ?>
        <form method="post" class="instructor-form" data-instructor-validate>
            <label>Email</label>
            <input name="email" value="<?php echo instructorE($email); ?>" data-instructor-label="Email">
            <label>Password</label>
            <input name="password" type="password" data-instructor-label="Password">
            <div class="instructor-actions">
                <button type="submit">Login</button>
            </div>
        </form>
        <p>Sample: farhan.instructor@example.com / instructor123</p>
    </div>
    <script src="views/assets/js/instructor_validation.js"></script>
</body>
</html>
