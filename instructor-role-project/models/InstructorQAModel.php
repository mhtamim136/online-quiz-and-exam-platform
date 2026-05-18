<?php
function instructorQAQuestions($courseId, $instructorId)
{
    $sql = 'SELECT qq.*, u.name AS student_name
            FROM qa_questions qq
            INNER JOIN courses c ON c.id = qq.course_id
            INNER JOIN users u ON u.id = qq.student_id
            WHERE qq.course_id = ? AND c.instructor_id = ?
            ORDER BY qq.created_at DESC';
    return instructorDbFetchAll($sql, 'ii', array($courseId, $instructorId));
}

function instructorQAQuestionFind($questionId, $instructorId)
{
    $sql = 'SELECT qq.*, u.name AS student_name
            FROM qa_questions qq
            INNER JOIN courses c ON c.id = qq.course_id
            INNER JOIN users u ON u.id = qq.student_id
            WHERE qq.id = ? AND c.instructor_id = ?
            LIMIT 1';
    return instructorDbFetchOne($sql, 'ii', array($questionId, $instructorId));
}

function instructorQAAnswers($questionId)
{
    $sql = 'SELECT qa.*, u.name AS user_name
            FROM qa_answers qa
            INNER JOIN users u ON u.id = qa.author_id
            WHERE qa.qa_question_id = ?
            ORDER BY qa.created_at ASC';
    return instructorDbFetchAll($sql, 'i', array($questionId));
}

function instructorQAAnswerCreate($questionId, $instructorId, $answer)
{
    instructorDbExecute('INSERT INTO qa_answers (qa_question_id, author_id, body, is_endorsed, created_at) VALUES (?, ?, ?, 1, NOW())', 'iis', array($questionId, $instructorId, $answer));
    return instructorDbLastId();
}
