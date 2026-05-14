<?php require __DIR__ . '/../partials/admin_header.php'; ?>

<?php
$roleCounts = array('admin' => 0, 'student' => 0, 'instructor' => 0, 'ta' => 0);
foreach ($stats['users_by_role'] as $row) {
    $roleCounts[$row['role']] = (int)$row['total'];
}
?>

<div class="grid-cards">
    <div class="card">
        <div class="card-label">Admins</div>
        <div class="card-value"><?php echo e($roleCounts['admin']); ?></div>
    </div>
    <div class="card">
        <div class="card-label">Students</div>
        <div class="card-value"><?php echo e($roleCounts['student']); ?></div>
    </div>
    <div class="card">
        <div class="card-label">Instructors</div>
        <div class="card-value"><?php echo e($roleCounts['instructor']); ?></div>
    </div>
    <div class="card">
        <div class="card-label">Teaching Assistants</div>
        <div class="card-value"><?php echo e($roleCounts['ta']); ?></div>
    </div>
    <div class="card">
        <div class="card-label">Active Courses</div>
        <div class="card-value"><?php echo e($stats['active_courses']['total']); ?></div>
    </div>
    <div class="card">
        <div class="card-label">Attempts Today</div>
        <div class="card-value"><?php echo e($stats['attempts_today']['total']); ?></div>
    </div>
    <div class="card">
        <div class="card-label">Pending Instructors</div>
        <div class="card-value"><?php echo e($stats['pending_instructors']['total']); ?></div>
    </div>
    <div class="card">
        <div class="card-label">Subjects</div>
        <div class="card-value"><?php echo e($stats['subjects']['total']); ?></div>
    </div>
    <div class="card">
        <div class="card-label">Quizzes</div>
        <div class="card-value"><?php echo e($stats['quizzes']['total']); ?></div>
    </div>
    <div class="card">
        <div class="card-label">Open Integrity Reports</div>
        <div class="card-value"><?php echo e($stats['open_reports']['total']); ?></div>
    </div>
</div>

<div class="panel">
    <h2>Recent Audit Logs</h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Admin</th>
                    <th>Action</th>
                    <th>Entity</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($stats['recent_logs'])): ?>
                    <tr><td colspan="5">No audit logs found.</td></tr>
                <?php endif; ?>
                <?php foreach ($stats['recent_logs'] as $log): ?>
                    <tr>
                        <td><?php echo e($log['created_at']); ?></td>
                        <td><?php echo e($log['admin_name']); ?></td>
                        <td><?php echo e($log['action']); ?></td>
                        <td><?php echo e($log['entity_type']); ?> #<?php echo e($log['entity_id']); ?></td>
                        <td><?php echo e($log['details']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/../partials/admin_footer.php'; ?>
