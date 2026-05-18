<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Online Quiz &amp; Exam Platform</title>
    <link rel="stylesheet" href="views/assets/css/style.css?v=<?php echo filemtime(__DIR__ . '/../assets/css/style.css'); ?>">
</head>
<body class="login-page">
    <div class="login-panel auth-panel-wide">
        <div class="auth-heading">
            <h1>Create Account</h1>
            <p class="auth-muted">Choose an account type first. The form will show only the details needed for that role.</p>
        </div>

        <div id="formError" class="form-error"></div>

        <?php if (!empty($errors)): ?>
            <div class="form-error server-errors">
                <?php foreach ($errors as $error): ?>
                    <div><?php echo e($error); ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form id="registerForm" method="post" action="register.php">
            <div class="auth-role-card">
                <label for="role">Account Type</label>
                <select id="role" name="role">
                    <option value="student" <?php echo $old['role'] === 'student' ? 'selected' : ''; ?>>Student</option>
                    <option value="instructor" <?php echo $old['role'] === 'instructor' ? 'selected' : ''; ?>>Instructor</option>
                </select>
                <p id="roleHelp" class="auth-role-help">
                    <?php echo $old['role'] === 'instructor' ? 'Instructor requests stay pending until an admin approves the account.' : 'Student accounts can log in after successful registration.'; ?>
                </p>
            </div>

            <div class="auth-form-grid register-grid">
                <div class="form-row">
                    <label for="name">Full Name</label>
                    <input id="name" class="<?php echo isset($fieldErrors['name']) ? 'input-error' : ''; ?>" name="name" type="text" value="<?php echo e($old['name']); ?>">
                    <div id="nameError" class="field-error"><?php echo isset($fieldErrors['name']) ? e($fieldErrors['name']) : ''; ?></div>
                </div>
                <div class="form-row">
                    <label for="email">Email</label>
                    <input id="email" class="<?php echo isset($fieldErrors['email']) ? 'input-error' : ''; ?>" name="email" type="text" value="<?php echo e($old['email']); ?>">
                    <div id="emailError" class="field-error"><?php echo isset($fieldErrors['email']) ? e($fieldErrors['email']) : ''; ?></div>
                </div>
                <div class="form-row">
                    <label for="username">Username</label>
                    <input id="username" class="<?php echo isset($fieldErrors['username']) ? 'input-error' : ''; ?>" name="username" type="text" value="<?php echo e($old['username']); ?>">
                    <div id="usernameError" class="field-error"><?php echo isset($fieldErrors['username']) ? e($fieldErrors['username']) : ''; ?></div>
                </div>
                <div class="form-row">
                    <label for="phone">Phone</label>
                    <input id="phone" class="<?php echo isset($fieldErrors['phone']) ? 'input-error' : ''; ?>" name="phone" type="text" value="<?php echo e($old['phone']); ?>">
                    <div id="phoneError" class="field-error"><?php echo isset($fieldErrors['phone']) ? e($fieldErrors['phone']) : ''; ?></div>
                </div>
                <div class="form-row student-only" style="<?php echo $old['role'] === 'instructor' ? 'display: none;' : ''; ?>">
                    <label for="student_id">Student ID</label>
                    <input id="student_id" class="<?php echo isset($fieldErrors['student_id']) ? 'input-error' : ''; ?>" name="student_id" type="text" value="<?php echo e($old['student_id']); ?>">
                    <div id="student_idError" class="field-error"><?php echo isset($fieldErrors['student_id']) ? e($fieldErrors['student_id']) : ''; ?></div>
                </div>
                <div class="form-row student-only" style="<?php echo $old['role'] === 'instructor' ? 'display: none;' : ''; ?>">
                    <label for="program">Program</label>
                    <input id="program" class="<?php echo isset($fieldErrors['program']) ? 'input-error' : ''; ?>" name="program" type="text" value="<?php echo e($old['program']); ?>">
                    <div id="programError" class="field-error"><?php echo isset($fieldErrors['program']) ? e($fieldErrors['program']) : ''; ?></div>
                </div>
                <div class="form-row">
                    <label for="department">Department</label>
                    <input id="department" class="<?php echo isset($fieldErrors['department']) ? 'input-error' : ''; ?>" name="department" type="text" value="<?php echo e($old['department']); ?>">
                    <div id="departmentError" class="field-error"><?php echo isset($fieldErrors['department']) ? e($fieldErrors['department']) : ''; ?></div>
                </div>
                <div class="form-row instructor-only" style="<?php echo $old['role'] === 'instructor' ? '' : 'display: none;'; ?>">
                    <label for="bio">Bio <span class="label-note">(optional)</span></label>
                    <textarea id="bio" name="bio"><?php echo e($old['bio']); ?></textarea>
                </div>
                <div class="form-row">
                    <label for="password">Password</label>
                    <div class="password-field">
                        <input id="password" class="<?php echo (isset($fieldErrors['password']) || (isset($fieldErrors['confirm_password']) && $fieldErrors['confirm_password'] === 'Passwords do not match.')) ? 'input-error' : ''; ?>" name="password" type="password" value="<?php echo e($old['password']); ?>">
                        <button class="password-toggle" type="button" data-target="password" aria-label="Show password" title="Show password">
                            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6z" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                <circle cx="12" cy="12" r="3" fill="none" stroke="currentColor" stroke-width="2"></circle>
                            </svg>
                        </button>
                    </div>
                    <div id="passwordError" class="field-error"><?php echo isset($fieldErrors['password']) ? e($fieldErrors['password']) : ''; ?></div>
                </div>
                <div class="form-row">
                    <label for="confirm_password">Confirm Password</label>
                    <div class="password-field">
                        <input id="confirm_password" class="<?php echo isset($fieldErrors['confirm_password']) ? 'input-error' : ''; ?>" name="confirm_password" type="password" value="<?php echo e($old['confirm_password']); ?>">
                        <button class="password-toggle" type="button" data-target="confirm_password" aria-label="Show confirm password" title="Show confirm password">
                            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6z" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                <circle cx="12" cy="12" r="3" fill="none" stroke="currentColor" stroke-width="2"></circle>
                            </svg>
                        </button>
                    </div>
                    <div id="confirm_passwordError" class="field-error"><?php echo isset($fieldErrors['confirm_password']) ? e($fieldErrors['confirm_password']) : ''; ?></div>
                </div>
            </div>

            <button class="primary full-button">Register</button>
        </form>

        <div class="auth-links">
            <span>Already have an account?</span>
            <a href="login.php">Back to login</a>
        </div>
    </div>

    <script src="views/assets/js/admin.js?v=<?php echo filemtime(__DIR__ . '/../assets/js/admin.js'); ?>"></script>
</body>
</html>
