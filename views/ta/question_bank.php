<?php require __DIR__ . '/../partials/ta_header.php'; ?>

<div class="ta-card">
    <h2><?php echo taE($course['title']); ?></h2>
    <p class="ta-muted">Copy an existing question into one of your course practice quizzes.</p>
</div>

<div class="ta-card">
    <table class="ta-table">
        <thead><tr><th>Question</th><th>Source Quiz</th><th>Created By</th><th>Copy To</th></tr></thead>
        <tbody>
            <?php foreach ($questions as $question): ?>
                <tr>
                    <td><?php echo taE($question['question_text']); ?></td>
                    <td><?php echo taE($question['quiz_title']); ?></td>
                    <td><?php echo taE($question['created_by_name']); ?></td>
                    <td>
                        <form method="post" action="ta.php?action=question_bank&course_id=<?php echo (int)$course['id']; ?>">
                            <input type="hidden" name="source_question_id" value="<?php echo (int)$question['id']; ?>">
                            <select name="target_quiz_id">
                                <?php foreach ($practiceQuizzes as $quiz): ?>
                                    <option value="<?php echo (int)$quiz['id']; ?>"><?php echo taE($quiz['title']); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button class="ta-button">Copy</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (count($questions) === 0): ?>
                <tr><td colspan="4">No questions found for this course.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../partials/ta_footer.php'; ?>
