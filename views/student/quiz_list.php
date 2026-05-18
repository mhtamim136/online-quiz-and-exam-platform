<h1>Quiz List</h1>

<?php
$gradedQuizzes = [];
$practiceQuizzes = [];
foreach ($quizzes as $quiz) {
    if ($quiz['quiz_type'] === 'graded') {
        $gradedQuizzes[] = $quiz;
    } else {
        $practiceQuizzes[] = $quiz;
    }
}
?>

<section class="student-card">
    <h2>Graded Quizzes</h2>
    <?php if (empty($gradedQuizzes)): ?>
        <p class="student-muted">No graded quizzes are available.</p>
    <?php else: ?>
        <table class="student-table">
            <thead>
            <tr>
                <th>Quiz</th>
                <th>Course</th>
                <th>Window</th>
                <th>Limit</th>
                <th>Marks</th>
                <th>Attempts</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($gradedQuizzes as $quiz): ?>
                <tr>
                    <td><?php echo studentE($quiz['title']); ?></td>
                    <td><?php echo studentE($quiz['course_title']); ?></td>
                    <td><?php echo studentE($quiz['available_from']); ?> to <?php echo studentE($quiz['available_until']); ?></td>
                    <td><?php echo studentE($quiz['time_limit_minutes']); ?> minutes</td>
                    <td><?php echo studentE($quiz['total_marks']); ?> / Pass <?php echo studentE($quiz['pass_mark']); ?></td>
                    <td><?php echo studentE($quiz['my_attempt_count']); ?></td>
                    <td><a href="student.php?action=quiz_take&id=<?php echo studentE($quiz['id']); ?>">Take</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>

<section class="student-card">
    <h2>Practice Quizzes</h2>
    <?php if (empty($practiceQuizzes)): ?>
        <p class="student-muted">No practice quizzes are available.</p>
    <?php else: ?>
        <table class="student-table">
            <thead>
            <tr>
                <th>Quiz</th>
                <th>Course</th>
                <th>Window</th>
                <th>Limit</th>
                <th>Marks</th>
                <th>Attempts</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($practiceQuizzes as $quiz): ?>
                <tr>
                    <td><?php echo studentE($quiz['title']); ?></td>
                    <td><?php echo studentE($quiz['course_title']); ?></td>
                    <td><?php echo studentE($quiz['available_from']); ?> to <?php echo studentE($quiz['available_until']); ?></td>
                    <td><?php echo studentE($quiz['time_limit_minutes']); ?> minutes</td>
                    <td><?php echo studentE($quiz['total_marks']); ?></td>
                    <td><?php echo studentE($quiz['my_attempt_count']); ?></td>
                    <td><a href="student.php?action=quiz_take&id=<?php echo studentE($quiz['id']); ?>">Practice</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>
