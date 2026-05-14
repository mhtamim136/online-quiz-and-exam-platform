<?php require __DIR__ . '/../partials/admin_header.php'; ?>

<div class="panel">
    <h2>Filter Quizzes</h2>
    <form method="get" action="admin.php" class="form-grid">
        <input type="hidden" name="action" value="quizzes">
        <div class="form-row">
            <label for="course_id">Course</label>
            <select id="course_id" name="course_id">
                <option value="0">All courses</option>
                <?php foreach ($courses as $course): ?>
                    <option value="<?php echo e($course['id']); ?>" <?php echo (int)$course_id === (int)$course['id'] ? 'selected' : ''; ?>>
                        <?php echo e($course['title']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-row">
            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="">All statuses</option>
                <option value="draft" <?php echo $status === 'draft' ? 'selected' : ''; ?>>Draft</option>
                <option value="published" <?php echo $status === 'published' ? 'selected' : ''; ?>>Published</option>
            </select>
        </div>
        <div class="form-row">
            <label for="quiz_type">Quiz Type</label>
            <select id="quiz_type" name="quiz_type">
                <option value="">All types</option>
                <option value="graded" <?php echo $quiz_type === 'graded' ? 'selected' : ''; ?>>Graded</option>
                <option value="practice" <?php echo $quiz_type === 'practice' ? 'selected' : ''; ?>>Practice</option>
            </select>
        </div>
        <div class="form-row">
            <label>&nbsp;</label>
            <button class="primary">Apply Filter</button>
            <a class="btn" href="admin.php?action=quizzes">Reset</a>
        </div>
    </form>
</div>

<div class="panel">
    <h2>All Quizzes</h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Quiz</th>
                    <th>Course</th>
                    <th>Created By</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Total Marks</th>
                    <th>Pass Mark</th>
                    <th>Time Limit</th>
                    <th>Attempts</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($quizzes)): ?>
                    <tr><td colspan="9">No quizzes found.</td></tr>
                <?php endif; ?>
                <?php foreach ($quizzes as $quiz): ?>
                    <tr>
                        <td><?php echo e($quiz['title']); ?></td>
                        <td><?php echo e($quiz['course_title']); ?></td>
                        <td><?php echo e($quiz['creator_name']); ?></td>
                        <td><?php echo e($quiz['quiz_type']); ?></td>
                        <td><?php echo e($quiz['status']); ?></td>
                        <td><?php echo e($quiz['total_marks']); ?></td>
                        <td><?php echo e($quiz['pass_mark']); ?></td>
                        <td><?php echo e($quiz['time_limit_minutes']); ?> minutes</td>
                        <td><?php echo e($quiz['attempt_count']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/../partials/admin_footer.php'; ?>
