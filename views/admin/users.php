<?php require __DIR__ . '/../partials/admin_header.php'; ?>

<div class="panel">
    <h2>Search and Filter Users</h2>
    <form method="get" action="admin.php" class="form-grid">
        <input type="hidden" name="action" value="users">
        <div class="form-row">
            <label for="liveUserSearch">Live Search</label>
            <input id="liveUserSearch" name="keyword" type="text" value="<?php echo e($keyword); ?>" placeholder="Name, email, username, student ID, phone">
        </div>
        <div class="form-row">
            <label for="roleFilter">Role</label>
            <select id="roleFilter" name="role">
                <option value="">All roles</option>
                <option value="student" <?php echo $role === 'student' ? 'selected' : ''; ?>>Student</option>
                <option value="instructor" <?php echo $role === 'instructor' ? 'selected' : ''; ?>>Instructor</option>
                <option value="ta" <?php echo $role === 'ta' ? 'selected' : ''; ?>>TA</option>
                <option value="admin" <?php echo $role === 'admin' ? 'selected' : ''; ?>>Admin</option>
            </select>
        </div>
        <div class="form-row">
            <label for="activeFilter">Status</label>
            <select id="activeFilter" name="active">
                <option value="">All statuses</option>
                <option value="1" <?php echo $active === '1' ? 'selected' : ''; ?>>Active</option>
                <option value="0" <?php echo $active === '0' ? 'selected' : ''; ?>>Inactive</option>
            </select>
        </div>
        <div class="form-row">
            <label>&nbsp;</label>
            <button class="primary">Apply Filter</button>
            <a class="btn" href="admin.php?action=users">Reset</a>
        </div>
    </form>
</div>

<div class="panel">
    <h2>All Users</h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Student ID</th>
                    <th>Approval</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="userTableBody">
                <?php if (empty($users)): ?>
                    <tr><td colspan="9">No users found.</td></tr>
                <?php endif; ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo e($user['id']); ?></td>
                        <td>
                            <strong><?php echo e($user['name']); ?></strong><br>
                            <span class="muted"><?php echo e($user['username']); ?></span>
                        </td>
                        <td><?php echo e($user['email']); ?></td>
                        <td><?php echo e($user['phone']); ?></td>
                        <td><?php echo e($user['role']); ?></td>
                        <td><?php echo e($user['student_id']); ?></td>
                        <td><?php echo e($user['approval_status']); ?></td>
                        <td>
                            <?php if ((int)$user['is_active'] === 1): ?>
                                <span class="status good">Active</span>
                            <?php else: ?>
                                <span class="status bad">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="actions">
                                <a class="btn small" href="admin.php?action=user_view&id=<?php echo e($user['id']); ?>">View</a>

                                <?php if ((int)$user['is_active'] === 1): ?>
                                    <form class="inline-form confirm-form" data-confirm="Deactivate this user?" method="post" action="admin.php?action=update_user_status">
                                        <input type="hidden" name="user_id" value="<?php echo e($user['id']); ?>">
                                        <input type="hidden" name="is_active" value="0">
                                        <button class="small danger">Deactivate</button>
                                    </form>
                                <?php else: ?>
                                    <form class="inline-form confirm-form" data-confirm="Activate this user?" method="post" action="admin.php?action=update_user_status">
                                        <input type="hidden" name="user_id" value="<?php echo e($user['id']); ?>">
                                        <input type="hidden" name="is_active" value="1">
                                        <button class="small">Activate</button>
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
                                    <button class="small">Save Role</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/../partials/admin_footer.php'; ?>
