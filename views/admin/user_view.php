<?php require __DIR__ . '/../partials/admin_header.php'; ?>

<div class="panel">
    <h2>User Information</h2>
    <table>
        <tr><th>ID</th><td><?php echo e($user['id']); ?></td></tr>
        <tr><th>Name</th><td><?php echo e($user['name']); ?></td></tr>
        <tr><th>Username</th><td><?php echo e($user['username']); ?></td></tr>
        <tr><th>Email</th><td><?php echo e($user['email']); ?></td></tr>
        <tr><th>Phone</th><td><?php echo e($user['phone']); ?></td></tr>
        <tr><th>Role</th><td><?php echo e($user['role']); ?></td></tr>
        <tr><th>Student ID</th><td><?php echo e($user['student_id']); ?></td></tr>
        <tr><th>Program</th><td><?php echo e($user['program']); ?></td></tr>
        <tr><th>Department</th><td><?php echo e($user['department']); ?></td></tr>
        <tr><th>Bio</th><td><?php echo e($user['bio']); ?></td></tr>
        <tr><th>Approval Status</th><td><?php echo e($user['approval_status']); ?></td></tr>
        <tr>
            <th>Active</th>
            <td><?php echo (int)$user['is_active'] === 1 ? 'Active' : 'Inactive'; ?></td>
        </tr>
        <tr><th>Created At</th><td><?php echo e($user['created_at']); ?></td></tr>
    </table>
</div>

<?php if ($academicSummary): ?>
    <div class="grid-cards">
        <div class="card">
            <div class="card-label">Total Enrolled Courses</div>
            <div class="card-value"><?php echo e($academicSummary['enrolled_course_count']); ?></div>
        </div>
        <div class="card">
            <div class="card-label">Total Quiz Attempts</div>
            <div class="card-value"><?php echo e($academicSummary['attempt_count']); ?></div>
        </div>
        <div class="card">
            <div class="card-label">Average Score</div>
            <div class="card-value"><?php echo $academicSummary['average_score'] === null ? '0.00' : e(number_format((float)$academicSummary['average_score'], 2)); ?></div>
        </div>
    </div>
<?php endif; ?>

<div class="panel">
    <h2>Admin Actions</h2>
    <div class="actions">
        <?php if ((int)$user['is_active'] === 1): ?>
            <form class="inline-form confirm-form" data-confirm="Deactivate this user?" method="post" action="admin.php?action=update_user_status">
                <input type="hidden" name="user_id" value="<?php echo e($user['id']); ?>">
                <input type="hidden" name="is_active" value="0">
                <button class="danger">Deactivate</button>
            </form>
        <?php else: ?>
            <form class="inline-form confirm-form" data-confirm="Activate this user?" method="post" action="admin.php?action=update_user_status">
                <input type="hidden" name="user_id" value="<?php echo e($user['id']); ?>">
                <input type="hidden" name="is_active" value="1">
                <button>Activate</button>
            </form>
        <?php endif; ?>

        <form class="inline-form confirm-form" data-confirm="Change this user's role?" method="post" action="admin.php?action=change_user_role">
            <input type="hidden" name="user_id" value="<?php echo e($user['id']); ?>">
            <select name="role">
                <option value="student" <?php echo $user['role'] === 'student' ? 'selected' : ''; ?>>student</option>
                <option value="instructor" <?php echo $user['role'] === 'instructor' ? 'selected' : ''; ?>>instructor</option>
                <option value="ta" <?php echo $user['role'] === 'ta' ? 'selected' : ''; ?>>ta</option>
                <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>admin</option>
            </select>
            <button>Save Role</button>
        </form>
        <a class="btn" href="admin.php?action=users">Back to Users</a>
    </div>
</div>

<?php require __DIR__ . '/../partials/admin_footer.php'; ?>
