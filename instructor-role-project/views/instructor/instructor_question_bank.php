<div class="instructor-card"><h2><?php echo instructorE($course['title']); ?></h2></div>
<table class="instructor-table">
    <tr><th>Quiz</th><th>Question</th><th>Marks</th></tr>
    <?php foreach ($questions as $question) { ?><tr><td><?php echo instructorE($question['quiz_title']); ?></td><td><?php echo instructorE($question['question_text']); ?></td><td><?php echo instructorE($question['marks']); ?></td></tr><?php } ?>
</table>
