<h1>Browse Courses</h1>

<form class="student-search" method="get" action="student.php">
    <input type="hidden" name="action" value="courses">
    <div class="student-form-grid">
        <div>
            <label for="course_keyword">Search Keyword</label>
            <input id="course_keyword" name="keyword" type="text" value="<?php echo studentE($keyword); ?>">
        </div>
        <div>
            <label for="course_subject">Subject</label>
            <select id="course_subject" name="subject_id">
                <option value="">All subjects</option>
                <?php foreach ($subjects as $subject): ?>
                    <option value="<?php echo studentE($subject['id']); ?>" <?php echo (int)$subjectId === (int)$subject['id'] ? 'selected' : ''; ?>>
                        <?php echo studentE($subject['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <button type="submit" class="student-btn primary">Search</button>
    <a class="student-btn secondary" href="student.php?action=courses">Clear</a>
</form>

<?php if (empty($courses)): ?>
    <div class="student-card">
        <p class="student-muted">No active courses found.</p>
    </div>
<?php else: ?>
    <table class="student-table">
        <thead>
        <tr>
            <th>Course</th>
            <th>Subject</th>
            <th>Instructor</th>
            <th>Students</th>
            <th>Enrollment</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($courses as $course): ?>
            <tr>
                <td>
                    <strong><?php echo studentE($course['title']); ?></strong><br>
                    <span class="student-muted"><?php echo studentE($course['description']); ?></span>
                </td>
                <td><?php echo studentE($course['subject_name']); ?></td>
                <td><?php echo studentE($course['instructor_name']); ?></td>
                <td><?php echo studentE($course['enrolled_count']); ?> / <?php echo studentE($course['max_students']); ?></td>
                <td><?php echo studentE($course['enrollment_type']); ?></td>
                <td><?php echo $course['my_status'] ? studentE($course['my_status']) : 'Not enrolled'; ?></td>
                <td>
                    <div class="student-actions">
                        <a href="student.php?action=course_detail&id=<?php echo studentE($course['id']); ?>">Detail</a>
                        <?php if (!$course['my_status']): ?>
                            <form method="post" action="student.php?action=courses">
                                <input type="hidden" name="course_id" value="<?php echo studentE($course['id']); ?>">
                                <input type="hidden" name="course_action" value="enroll">
                                <button type="submit" class="student-btn small-btn primary">Enroll</button>
                            </form>
                        <?php elseif ($course['my_status'] === 'active'): ?>
                            <form method="post" action="student.php?action=courses">
                                <input type="hidden" name="course_id" value="<?php echo studentE($course['id']); ?>">
                                <input type="hidden" name="course_action" value="drop">
                                <button type="submit" class="student-btn small-btn danger">Drop</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
