<?php require __DIR__ . '/../partials/admin_header.php'; ?>

<div class="panel">
    <h2>Filter Courses</h2>
    <form method="get" action="admin.php" class="form-grid">
        <input type="hidden" name="action" value="courses">
        <div class="form-row">
            <label for="subject_id">Subject</label>
            <select id="subject_id" name="subject_id">
                <option value="0">All subjects</option>
                <?php foreach ($subjects as $subject): ?>
                    <option value="<?php echo e($subject['id']); ?>" <?php echo (int)$subject_id === (int)$subject['id'] ? 'selected' : ''; ?>>
                        <?php echo e($subject['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-row">
            <label for="instructor_id">Instructor</label>
            <select id="instructor_id" name="instructor_id">
                <option value="0">All instructors</option>
                <?php foreach ($instructors as $instructor): ?>
                    <option value="<?php echo e($instructor['id']); ?>" <?php echo (int)$instructor_id === (int)$instructor['id'] ? 'selected' : ''; ?>>
                        <?php echo e($instructor['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-row">
            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="">All statuses</option>
                <option value="draft" <?php echo $status === 'draft' ? 'selected' : ''; ?>>Draft</option>
                <option value="active" <?php echo $status === 'active' ? 'selected' : ''; ?>>Active</option>
                <option value="archived" <?php echo $status === 'archived' ? 'selected' : ''; ?>>Archived</option>
            </select>
        </div>
        <div class="form-row">
            <label>&nbsp;</label>
            <button class="primary">Apply Filter</button>
            <a class="btn" href="admin.php?action=courses">Reset</a>
        </div>
    </form>
</div>

<div class="panel">
    <h2>All Courses</h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Instructor</th>
                    <th>Subject</th>
                    <th>Enrollment</th>
                    <th>Max Students</th>
                    <th>Enrolled</th>
                    <th>Status</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($courses)): ?>
                    <tr><td colspan="8">No courses found.</td></tr>
                <?php endif; ?>
                <?php foreach ($courses as $course): ?>
                    <tr>
                        <td><?php echo e($course['title']); ?></td>
                        <td><?php echo e($course['instructor_name']); ?></td>
                        <td><?php echo e($course['subject_name']); ?></td>
                        <td><?php echo e($course['enrollment_type']); ?></td>
                        <td><?php echo e($course['max_students']); ?></td>
                        <td><?php echo e($course['enrolled_count']); ?></td>
                        <td><?php echo e($course['status']); ?></td>
                        <td><?php echo e($course['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/../partials/admin_footer.php'; ?>
