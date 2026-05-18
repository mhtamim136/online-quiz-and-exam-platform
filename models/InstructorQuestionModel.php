<?php
function instructorQuestionByQuiz($quizId, $instructorId)
{
    $sql = 'SELECT qu.*
            FROM questions qu
            INNER JOIN quizzes q ON q.id = qu.quiz_id
            INNER JOIN courses c ON c.id = q.course_id
            WHERE qu.quiz_id = ? AND c.instructor_id = ?
            ORDER BY qu.id ASC';
    return instructorDbFetchAll($sql, 'ii', array($quizId, $instructorId));
}

function instructorQuestionFind($questionId, $instructorId)
{
    $sql = 'SELECT qu.*
            FROM questions qu
            INNER JOIN quizzes q ON q.id = qu.quiz_id
            INNER JOIN courses c ON c.id = q.course_id
            WHERE qu.id = ? AND c.instructor_id = ?
            LIMIT 1';
    return instructorDbFetchOne($sql, 'ii', array($questionId, $instructorId));
}

function instructorQuestionOptions($questionId)
{
    return instructorDbFetchAll('SELECT id, option_text, is_correct FROM options WHERE question_id = ? ORDER BY id ASC', 'i', array($questionId));
}

function instructorQuestionCreate($quizId, $instructorId, $questionText, $marks, $optionTexts, $correctIndex)
{
    $quiz = instructorQuizFind($quizId, $instructorId);
    if (!$quiz) {
        return 0;
    }
    $last = instructorDbFetchOne('SELECT COALESCE(MAX(order_index), 0) + 1 AS next_order FROM questions WHERE quiz_id = ?', 'i', array($quizId));
    $orderIndex = (int) $last['next_order'];
    instructorDbExecute('INSERT INTO questions (quiz_id, question_text, marks, order_index, created_by, created_at) VALUES (?, ?, ?, ?, ?, NOW())', 'isdii', array($quizId, $questionText, $marks, $orderIndex, $instructorId));
    $questionId = instructorDbLastId();
    for ($i = 0; $i < 4; $i++) {
        $isCorrect = ($i === $correctIndex) ? 1 : 0;
        instructorDbExecute('INSERT INTO options (question_id, option_text, is_correct) VALUES (?, ?, ?)', 'isi', array($questionId, $optionTexts[$i], $isCorrect));
    }
    instructorQuestionRefreshQuizMarks($quizId);
    return $questionId;
}

function instructorQuestionUpdate($questionId, $instructorId, $questionText, $marks, $optionTexts, $correctIndex)
{
    $question = instructorQuestionFind($questionId, $instructorId);
    if (!$question) {
        return 0;
    }
    instructorDbExecute('UPDATE questions SET question_text = ?, marks = ? WHERE id = ?', 'sdi', array($questionText, $marks, $questionId));
    $options = instructorQuestionOptions($questionId);
    for ($i = 0; $i < 4; $i++) {
        $isCorrect = ($i === $correctIndex) ? 1 : 0;
        if (isset($options[$i])) {
            instructorDbExecute('UPDATE options SET option_text = ?, is_correct = ? WHERE id = ? AND question_id = ?', 'siii', array($optionTexts[$i], $isCorrect, $options[$i]['id'], $questionId));
        } else {
            instructorDbExecute('INSERT INTO options (question_id, option_text, is_correct) VALUES (?, ?, ?)', 'isi', array($questionId, $optionTexts[$i], $isCorrect));
        }
    }
    instructorQuestionRefreshQuizMarks($question['quiz_id']);
    return 1;
}

function instructorQuestionRefreshQuizMarks($quizId)
{
    $summary = instructorDbFetchOne('SELECT COALESCE(SUM(marks), 0) AS total_marks FROM questions WHERE quiz_id = ?', 'i', array($quizId));
    return instructorDbExecute('UPDATE quizzes SET total_marks = ? WHERE id = ?', 'di', array($summary['total_marks'], $quizId));
}

function instructorQuestionBankByCourse($courseId, $instructorId)
{
    $sql = 'SELECT qu.*, q.title AS quiz_title
            FROM questions qu
            INNER JOIN quizzes q ON q.id = qu.quiz_id
            INNER JOIN courses c ON c.id = q.course_id
            WHERE q.course_id = ? AND c.instructor_id = ?
            ORDER BY qu.created_at DESC';
    return instructorDbFetchAll($sql, 'ii', array($courseId, $instructorId));
}
