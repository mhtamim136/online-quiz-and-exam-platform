<h1><?php echo studentE($quiz['title']); ?></h1>
<p class="student-muted">
    Course: <?php echo studentE($quiz['course_title']); ?> |
    Type: <?php echo studentE($quiz['quiz_type']); ?> |
    Total Marks: <?php echo studentE($quiz['total_marks']); ?> |
    Pass Mark: <?php echo studentE($quiz['pass_mark']); ?>
</p>

<div id="studentQuizCountdown" class="student-countdown" data-minutes="<?php echo studentE($quiz['time_limit_minutes']); ?>">
    Time left: <?php echo studentE($quiz['time_limit_minutes']); ?>m 0s
</div>

<?php if (!empty($errors)): ?>
    <div class="student-alert error">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo studentE($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form id="studentQuizForm" method="post" action="student.php?action=quiz_take&id=<?php echo studentE($quiz['id']); ?>" novalidate>
    <?php foreach ($questions as $index => $question): ?>
        <section class="student-card">
            <h2>Question <?php echo studentE($index + 1); ?></h2>
            <p><?php echo nl2br(studentE($question['question_text'])); ?></p>
            <p class="student-muted">Marks: <?php echo studentE($question['marks']); ?></p>

            <?php foreach ($question['options'] as $option): ?>
                <label class="student-option">
                    <input
                        type="radio"
                        name="answers[<?php echo studentE($question['id']); ?>]"
                        value="<?php echo studentE($option['id']); ?>"
                        <?php echo isset($selectedAnswers[$question['id']]) && (int)$selectedAnswers[$question['id']] === (int)$option['id'] ? 'checked' : ''; ?>
                    >
                    <?php echo studentE($option['option_text']); ?>
                </label>
            <?php endforeach; ?>
        </section>
    <?php endforeach; ?>

    <button type="submit" class="student-btn primary">Submit Quiz</button>
</form>
