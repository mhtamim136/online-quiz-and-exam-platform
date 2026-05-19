<?php require __DIR__ . '/../partials/admin_header.php'; ?>

<div class="panel">
    <h2>Search Student</h2>
    <form id="studentReportSearchForm" method="get" action="admin.php" class="form-grid">
        <input type="hidden" name="action" value="student_report">
        <div class="form-row">
            <label for="keyword">Student Name, ID, or Email</label>
            <input id="keyword" name="keyword" type="text" value="<?php echo e($keyword); ?>">
        </div>
        <div class="form-row">
            <label>&nbsp;</label>
            <button class="primary">Search</button>
        </div>
    </form>
</div>

<?php if ($keyword === '' && !$student): ?>
    <div id="recentStudentsPanel" class="panel">
        <h2>Recent Students</h2>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Student ID</th>
                        <th>Program</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recentStudents)): ?>
                        <tr><td colspan="6">No students found.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($recentStudents as $item): ?>
                        <tr>
                            <td><?php echo e($item['name']); ?></td>
                            <td><?php echo e($item['email']); ?></td>
                            <td><?php echo e($item['student_id']); ?></td>
                            <td><?php echo e($item['program']); ?></td>
                            <td><?php echo (int)$item['is_active'] === 1 ? 'Active' : 'Inactive'; ?></td>
                            <td>
                                <a class="btn small" href="admin.php?action=student_report&student_id=<?php echo e($item['id']); ?>">View Report</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<?php $showSearchResults = $keyword !== '' || !empty($students); ?>
<div id="studentSearchResultsPanel" class="panel" style="<?php echo $showSearchResults ? '' : 'display: none;'; ?>">
    <h2>Search Results</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Student ID</th>
                <th>Program</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="studentSearchResultsBody">
            <?php if (empty($students)): ?>
                <tr><td colspan="5">No students matched your search.</td></tr>
            <?php endif; ?>
            <?php foreach ($students as $item): ?>
                <tr>
                    <td><?php echo e($item['name']); ?></td>
                    <td><?php echo e($item['email']); ?></td>
                    <td><?php echo e($item['student_id']); ?></td>
                    <td><?php echo e($item['program']); ?></td>
                    <td>
                        <a class="btn small" href="admin.php?action=student_report&keyword=<?php echo e(urlencode($keyword)); ?>&student_id=<?php echo e($item['id']); ?>">View Report</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php if ($student): ?>
    <div class="grid-cards">
        <div class="card">
            <div class="card-label">Student</div>
            <div class="card-value"><?php echo e($student['name']); ?></div>
        </div>
        <div class="card">
            <div class="card-label">Attempt Count</div>
            <div class="card-value"><?php echo e($summary['attempt_count']); ?></div>
        </div>
        <div class="card">
            <div class="card-label">Average Score</div>
            <div class="card-value"><?php echo $summary['average_score'] === null ? '0.00' : e(number_format((float)$summary['average_score'], 2)); ?></div>
        </div>
    </div>

    <div class="panel">
        <h2>Student Information</h2>
        <table>
            <tr><th>Name</th><td><?php echo e($student['name']); ?></td></tr>
            <tr><th>Email</th><td><?php echo e($student['email']); ?></td></tr>
            <tr><th>Student ID</th><td><?php echo e($student['student_id']); ?></td></tr>
            <tr><th>Program</th><td><?php echo e($student['program']); ?></td></tr>
            <tr><th>Status</th><td><?php echo (int)$student['is_active'] === 1 ? 'Active' : 'Inactive'; ?></td></tr>
        </table>
    </div>

    <div class="panel">
        <h2>Enrolled Courses</h2>
        <table>
            <thead>
                <tr>
                    <th>Course</th>
                    <th>Subject</th>
                    <th>Enrollment Status</th>
                    <th>Course Status</th>
                    <th>Enrolled At</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($enrollments)): ?>
                    <tr><td colspan="5">No enrollments found.</td></tr>
                <?php endif; ?>
                <?php foreach ($enrollments as $enrollment): ?>
                    <tr>
                        <td><?php echo e($enrollment['course_title']); ?></td>
                        <td><?php echo e($enrollment['subject_name']); ?></td>
                        <td><?php echo e($enrollment['status']); ?></td>
                        <td><?php echo e($enrollment['course_status']); ?></td>
                        <td><?php echo e($enrollment['enrolled_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="panel">
        <h2>Quiz Attempts</h2>
        <table>
            <thead>
                <tr>
                    <th>Quiz</th>
                    <th>Course</th>
                    <th>Score</th>
                    <th>Pass Mark</th>
                    <th>Started At</th>
                    <th>Completed At</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($attempts)): ?>
                    <tr><td colspan="6">No attempts found.</td></tr>
                <?php endif; ?>
                <?php foreach ($attempts as $attempt): ?>
                    <tr>
                        <td><?php echo e($attempt['quiz_title']); ?></td>
                        <td><?php echo e($attempt['course_title']); ?></td>
                        <td><?php echo e($attempt['score']); ?></td>
                        <td><?php echo e($attempt['pass_mark']); ?></td>
                        <td><?php echo e($attempt['started_at']); ?></td>
                        <td><?php echo e($attempt['completed_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/../partials/admin_footer.php'; ?>
