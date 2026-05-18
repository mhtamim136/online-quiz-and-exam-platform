<h1>Q&A Board: <?php echo studentE($course['title']); ?></h1>

<section class="student-card">
    <h2>Post a Question</h2>
    <div id="studentQaErrors" class="student-js-errors"></div>

    <?php if (!empty($errors)): ?>
        <div class="student-alert error">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo studentE($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form id="studentQaForm" method="post" action="student.php?action=qa_board&course_id=<?php echo studentE($course['id']); ?>" novalidate>
        <label for="qa_title">Title</label>
        <input id="qa_title" name="title" type="text">

        <label for="qa_body">Body</label>
        <textarea id="qa_body" name="body"></textarea>

        <button type="submit" class="student-btn primary">Post Question</button>
    </form>
</section>

<section class="student-card">
    <h2>Questions</h2>
    <?php if (empty($questions)): ?>
        <p class="student-muted">No questions have been posted yet.</p>
    <?php else: ?>
        <?php foreach ($questions as $question): ?>
            <article class="student-question">
                <h3><a href="student.php?action=qa_detail&id=<?php echo studentE($question['id']); ?>"><?php echo studentE($question['title']); ?></a></h3>
                <p class="student-muted">
                    Asked by <?php echo studentE($question['student_name']); ?> |
                    <?php echo studentE($question['created_at']); ?> |
                    Answers: <?php echo studentE($question['answer_count']); ?>
                    <?php if ((int)$question['endorsed_count'] > 0): ?>
                        | <span class="student-badge good">Endorsed answer</span>
                    <?php endif; ?>
                    <?php if ((int)$question['is_resolved'] === 1): ?>
                        | <span class="student-badge good">Resolved</span>
                    <?php else: ?>
                        | <span class="student-badge warn">Open</span>
                    <?php endif; ?>
                </p>
                <p><?php echo nl2br(studentE($question['body'])); ?></p>
            </article>
            <hr>
        <?php endforeach; ?>
    <?php endif; ?>
</section>
