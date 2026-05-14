<?php require __DIR__ . '/../partials/admin_header.php'; ?>

<div class="panel">
    <h2>Filter Reports</h2>
    <form method="get" action="admin.php" class="form-grid">
        <input type="hidden" name="action" value="integrity_reports">
        <div class="form-row">
            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="">All statuses</option>
                <option value="open" <?php echo $status === 'open' ? 'selected' : ''; ?>>Open</option>
                <option value="under_review" <?php echo $status === 'under_review' ? 'selected' : ''; ?>>Under Review</option>
                <option value="resolved" <?php echo $status === 'resolved' ? 'selected' : ''; ?>>Resolved</option>
                <option value="escalated" <?php echo $status === 'escalated' ? 'selected' : ''; ?>>Escalated</option>
                <option value="dismissed" <?php echo $status === 'dismissed' ? 'selected' : ''; ?>>Dismissed</option>
            </select>
        </div>
        <div class="form-row">
            <label for="severity">Severity</label>
            <select id="severity" name="severity">
                <option value="">All severities</option>
                <option value="low" <?php echo $severity === 'low' ? 'selected' : ''; ?>>Low</option>
                <option value="medium" <?php echo $severity === 'medium' ? 'selected' : ''; ?>>Medium</option>
                <option value="high" <?php echo $severity === 'high' ? 'selected' : ''; ?>>High</option>
                <option value="critical" <?php echo $severity === 'critical' ? 'selected' : ''; ?>>Critical</option>
            </select>
        </div>
        <div class="form-row">
            <label>&nbsp;</label>
            <button class="primary">Apply Filter</button>
            <a class="btn" href="admin.php?action=integrity_reports">Reset</a>
        </div>
    </form>
</div>

<div id="formError" class="form-error"></div>
<?php if (!empty($errors)): ?>
    <div class="form-error server-errors">
        <?php foreach ($errors as $error): ?>
            <div><?php echo e($error); ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="two-column">
    <div class="panel">
        <h2>Reports</h2>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Student</th>
                        <th>Severity</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($reports)): ?>
                        <tr><td colspan="6">No integrity reports found.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($reports as $report): ?>
                        <tr>
                            <td><?php echo e($report['id']); ?></td>
                            <td><?php echo e($report['title']); ?></td>
                            <td><?php echo e($report['student_name']); ?></td>
                            <td><?php echo e($report['severity']); ?></td>
                            <td><?php echo e($report['status']); ?></td>
                            <td><a class="btn small" href="admin.php?action=integrity_reports&report_id=<?php echo e($report['id']); ?>">View</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="panel">
        <h2>Report Details</h2>
        <?php if (!$selectedReport): ?>
            <p class="muted">Select a report to review details and update its status.</p>
        <?php else: ?>
            <table>
                <tr><th>Title</th><td><?php echo e($selectedReport['title']); ?></td></tr>
                <tr><th>Type</th><td><?php echo e($selectedReport['report_type']); ?></td></tr>
                <tr><th>Student</th><td><?php echo e($selectedReport['student_name']); ?></td></tr>
                <tr><th>Reporter</th><td><?php echo e($selectedReport['reporter_name']); ?></td></tr>
                <tr><th>Quiz</th><td><?php echo e($selectedReport['quiz_title']); ?></td></tr>
                <tr><th>Severity</th><td><?php echo e($selectedReport['severity']); ?></td></tr>
                <tr><th>Status</th><td><?php echo e($selectedReport['status']); ?></td></tr>
                <tr><th>Description</th><td><?php echo e($selectedReport['description']); ?></td></tr>
                <tr><th>Resolution Notes</th><td><?php echo e($selectedReport['resolution_notes']); ?></td></tr>
                <tr><th>Resolved By</th><td><?php echo e($selectedReport['resolved_by_name']); ?></td></tr>
                <tr><th>Resolved At</th><td><?php echo e($selectedReport['resolved_at']); ?></td></tr>
            </table>

            <form id="integrityForm" method="post" action="admin.php?action=integrity_reports">
                <input type="hidden" name="report_id" value="<?php echo e($selectedReport['id']); ?>">
                <div class="form-row">
                    <label for="status_update">New Status</label>
                    <select id="status_update" name="status">
                        <option value="resolved">Resolved</option>
                        <option value="escalated">Escalated</option>
                    </select>
                </div>
                <div class="form-row">
                    <label for="resolution_notes">Resolution Notes</label>
                    <textarea id="resolution_notes" name="resolution_notes"></textarea>
                </div>
                <button class="primary">Save Report Update</button>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../partials/admin_footer.php'; ?>
