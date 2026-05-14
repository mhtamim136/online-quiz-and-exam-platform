<?php require __DIR__ . '/../partials/admin_header.php'; ?>

<div class="panel">
    <h2>Pending Instructor Requests</h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Department</th>
                    <th>Bio</th>
                    <th>Requested At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($requests)): ?>
                    <tr><td colspan="7">No pending instructor requests.</td></tr>
                <?php endif; ?>
                <?php foreach ($requests as $request): ?>
                    <tr>
                        <td><?php echo e($request['name']); ?></td>
                        <td><?php echo e($request['email']); ?></td>
                        <td><?php echo e($request['phone']); ?></td>
                        <td><?php echo e($request['department']); ?></td>
                        <td><?php echo e($request['bio']); ?></td>
                        <td><?php echo e($request['created_at']); ?></td>
                        <td>
                            <form class="inline-form confirm-form" data-confirm="Approve this instructor?" method="post" action="admin.php?action=approve_instructor">
                                <input type="hidden" name="user_id" value="<?php echo e($request['id']); ?>">
                                <button class="small primary">Approve</button>
                            </form>
                            <form class="inline-form confirm-form" data-confirm="Reject this instructor?" method="post" action="admin.php?action=reject_instructor">
                                <input type="hidden" name="user_id" value="<?php echo e($request['id']); ?>">
                                <button class="small danger">Reject</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/../partials/admin_footer.php'; ?>
