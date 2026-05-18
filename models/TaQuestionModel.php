<?php
function taGetQuizQuestions($conn, $quizId, $taId)
{
    $sql = "SELECT questions.*, users.name AS created_by_name, quizzes.title AS quiz_title
            FROM questions
            INNER JOIN quizzes ON quizzes.id = questions.quiz_id
            INNER JOIN users ON users.id = questions.created_by
            INNER JOIN course_tas ON course_tas.course_id = quizzes.course_id
            WHERE questions.quiz_id = ? AND course_tas.ta_id = ?
            ORDER BY questions.order_index ASC, questions.id ASC";
    return dbSelectAll($conn, $sql, 'ii', array((int)$quizId, (int)$taId));
}

function taGetQuestion($conn, $questionId, $taId)
{
    $sql = "SELECT questions.*, quizzes.title AS quiz_title, quizzes.course_id
            FROM questions
            INNER JOIN quizzes ON quizzes.id = questions.quiz_id
            INNER JOIN course_tas ON course_tas.course_id = quizzes.course_id
            WHERE questions.id = ? AND course_tas.ta_id = ?
            LIMIT 1";
    return dbSelectOne($conn, $sql, 'ii', array((int)$questionId, (int)$taId));
}

function taGetQuestionOptions($conn, $questionId)
{
    $sql = "SELECT * FROM options WHERE question_id = ? ORDER BY id ASC";
    return dbSelectAll($conn, $sql, 'i', array((int)$questionId));
}

function taGetNextQuestionOrder($conn, $quizId)
{
    $row = dbSelectOne($conn, "SELECT COALESCE(MAX(order_index), 0) + 1 AS next_order FROM questions WHERE quiz_id = ?", 'i', array((int)$quizId));
    return (int)$row['next_order'];
}

function taCreateQuestion($conn, $quizId, $taId, $data)
{
    $order = taGetNextQuestionOrder($conn, $quizId);
    $sql = "INSERT INTO questions (quiz_id, question_text, marks, order_index, created_by)
            VALUES (?, ?, ?, ?, ?)";
    return dbExecute($conn, $sql, 'isdii', array((int)$quizId, $data['question_text'], (float)$data['marks'], $order, (int)$taId));
}

function taUpdateQuestion($conn, $questionId, $taId, $data)
{
    $sql = "UPDATE questions
            SET question_text = ?, marks = ?
            WHERE id = ? AND created_by = ?";
    return dbExecute($conn, $sql, 'sdii', array($data['question_text'], (float)$data['marks'], (int)$questionId, (int)$taId));
}

function taReplaceOptions($conn, $questionId, $options)
{
    dbExecute($conn, "DELETE FROM options WHERE question_id = ?", 'i', array((int)$questionId));

    foreach ($options as $option) {
        dbExecute(
            $conn,
            "INSERT INTO options (question_id, option_text, is_correct) VALUES (?, ?, ?)",
            'isi',
            array((int)$questionId, $option['option_text'], (int)$option['is_correct'])
        );
    }
}

function taDeleteQuestion($conn, $questionId, $taId)
{
    $sql = "DELETE FROM questions WHERE id = ? AND created_by = ?";
    return dbExecute($conn, $sql, 'ii', array((int)$questionId, (int)$taId));
}

function taGetQuestionBank($conn, $courseId, $taId)
{
    $sql = "SELECT questions.*, quizzes.title AS quiz_title, users.name AS created_by_name
            FROM questions
            INNER JOIN quizzes ON quizzes.id = questions.quiz_id
            INNER JOIN users ON users.id = questions.created_by
            INNER JOIN course_tas ON course_tas.course_id = quizzes.course_id
            WHERE quizzes.course_id = ? AND course_tas.ta_id = ?
            ORDER BY questions.created_at DESC";
    return dbSelectAll($conn, $sql, 'ii', array((int)$courseId, (int)$taId));
}

function taCopyQuestionToQuiz($conn, $sourceQuestionId, $targetQuizId, $taId)
{
    $source = taGetQuestion($conn, $sourceQuestionId, $taId);
    if (!$source || !taCanAccessQuiz($conn, $targetQuizId, $taId)) {
        return false;
    }

    $newId = taCreateQuestion(
        $conn,
        $targetQuizId,
        $taId,
        array(
            'question_text' => $source['question_text'],
            'marks' => $source['marks']
        )
    );

    $options = taGetQuestionOptions($conn, $sourceQuestionId);
    foreach ($options as $option) {
        dbExecute(
            $conn,
            "INSERT INTO options (question_id, option_text, is_correct) VALUES (?, ?, ?)",
            'isi',
            array((int)$newId, $option['option_text'], (int)$option['is_correct'])
        );
    }

    return true;
}
