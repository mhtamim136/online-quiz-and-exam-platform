<?php require __DIR__ . '/../partials/admin_header.php'; ?>

<div class="panel">
    <h2>Filter Audit Logs</h2>
    <form method="get" action="admin.php" class="form-grid">
        <input type="hidden" name="action" value="audit_logs">
        <div class="form-row">
            <label for="action_filter">Action</label>
            <select id="action_filter" name="action_filter">
                <option value="">All actions</option>
                <?php foreach ($actions as $actionRow): ?>
                    <option value="<?php echo e($actionRow['action']); ?>" <?php echo $action_filter === $actionRow['action'] ? 'selected' : ''; ?>>
                        <?php echo e($actionRow['action']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-row">
            <label for="admin_id">Admin</label>
            <select id="admin_id" name="admin_id">
                <option value="0">All admins</option>
                <?php foreach ($admins as $admin): ?>
                    <option value="<?php echo e($admin['id']); ?>" <?php echo (int)$admin_id === (int)$admin['id'] ? 'selected' : ''; ?>>
                        <?php echo e($admin['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-row">
            <label>&nbsp;</label>
            <button class="primary">Apply Filter</button>
            <a class="btn" href="admin.php?action=audit_logs">Reset</a>
        </div>
    </form>
</div>

<div class="panel">
    <h2>Audit Logs</h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Created At</th>
                    <th>Admin</th>
                    <th>Action</th>
                    <th>Entity Type</th>
                    <th>Entity ID</th>
                    <th>Details</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($logs)): ?>
                    <tr><td colspan="7">No audit logs found.</td></tr>
                <?php endif; ?>
                <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><?php echo e($log['created_at']); ?></td>
                        <td><?php echo e($log['admin_name']); ?></td>
                        <td><?php echo e($log['action']); ?></td>
                        <td><?php echo e($log['entity_type']); ?></td>
                        <td><?php echo e($log['entity_id']); ?></td>
                        <td><?php echo e($log['details']); ?></td>
                        <td><?php echo e($log['ip_address']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/../partials/admin_footer.php'; ?>
