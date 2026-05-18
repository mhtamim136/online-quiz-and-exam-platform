<table class="instructor-table">
    <tr><th>Course</th><th>Status/Code</th><th>Students</th><th>Quizzes</th><th>Average Score</th></tr>
    <?php foreach ($reports as $report) { ?>
        <tr>
            <td><?php echo instructorE($report['title']); ?></td>
            <td><?php echo instructorE($report['status']); ?></td>
            <td><?php echo instructorE($report['students']); ?></td>
            <td><?php echo instructorE($report['quizzes']); ?></td>
            <td><?php echo instructorE(round((float) $report['average_score'], 2)); ?></td>
        </tr>
    <?php } ?>
</table>
