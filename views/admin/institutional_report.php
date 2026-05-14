<?php require __DIR__ . '/../partials/admin_header.php'; ?>

<div class="grid-cards">
    <div class="card">
        <div class="card-label">Total Users</div>
        <div class="card-value"><?php echo e($totals['users']['total']); ?></div>
    </div>
    <div class="card">
        <div class="card-label">Active Courses</div>
        <div class="card-value"><?php echo e($totals['active_courses']['total']); ?></div>
    </div>
    <div class="card">
        <div class="card-label">Total Quizzes</div>
        <div class="card-value"><?php echo e($totals['quizzes']['total']); ?></div>
    </div>
    <div class="card">
        <div class="card-label">Total Attempts</div>
        <div class="card-value"><?php echo e($totals['attempts']['total']); ?></div>
    </div>
</div>

<div class="two-column">
    <div class="panel">
        <h2>Pass Rates Per Subject</h2>
        <table>
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Attempts</th>
                    <th>Passed</th>
                    <th>Pass Rate</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($passRates as $row): ?>
                    <tr>
                        <td><?php echo e($row['subject_name']); ?></td>
                        <td><?php echo e($row['total_attempts']); ?></td>
                        <td><?php echo e($row['passed_attempts']); ?></td>
                        <td><?php echo e($row['pass_rate']); ?>%</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="panel">
        <h2>Enrollments Per Subject</h2>
        <table>
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Enrollments</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($enrollments as $row): ?>
                    <tr>
                        <td><?php echo e($row['subject_name']); ?></td>
                        <td><?php echo e($row['total_enrollments']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="panel">
    <h2>Most Active Instructors</h2>
    <table>
        <thead>
            <tr>
                <th>Instructor</th>
                <th>Email</th>
                <th>Courses</th>
                <th>Quizzes</th>
                <th>Attempts</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($activeInstructors as $instructor): ?>
                <tr>
                    <td><?php echo e($instructor['name']); ?></td>
                    <td><?php echo e($instructor['email']); ?></td>
                    <td><?php echo e($instructor['course_count']); ?></td>
                    <td><?php echo e($instructor['quiz_count']); ?></td>
                    <td><?php echo e($instructor['attempt_count']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../partials/admin_footer.php'; ?>
