<?php
function taGetQaQuestions($conn, $courseId, $taId)
{
    $sql = "SELECT qa_questions.*, users.name AS student_name, courses.title AS course_title,
                   COUNT(qa_answers.id) AS answer_count
            FROM qa_questions
            INNER JOIN users ON users.id = qa_questions.student_id
            INNER JOIN courses ON courses.id = qa_questions.course_id
            INNER JOIN course_tas ON course_tas.course_id = qa_questions.course_id
            LEFT JOIN qa_answers ON qa_answers.qa_question_id = qa_questions.id
            WHERE course_tas.ta_id = ?";
    $types = 'i';
    $params = array((int)$taId);

    if ($courseId > 0) {
        $sql .= " AND qa_questions.course_id = ?";
        $types .= 'i';
        $params[] = (int)$courseId;
    }

    $sql .= " GROUP BY qa_questions.id, users.name, courses.title
              ORDER BY qa_questions.created_at DESC";

    return dbSelectAll($conn, $sql, $types, $params);
}

function taGetQaQuestion($conn, $questionId, $taId)
{
    $sql = "SELECT qa_questions.*, users.name AS student_name, courses.title AS course_title
            FROM qa_questions
            INNER JOIN users ON users.id = qa_questions.student_id
            INNER JOIN courses ON courses.id = qa_questions.course_id
            INNER JOIN course_tas ON course_tas.course_id = qa_questions.course_id
            WHERE qa_questions.id = ? AND course_tas.ta_id = ?
            LIMIT 1";
    return dbSelectOne($conn, $sql, 'ii', array((int)$questionId, (int)$taId));
}

function taGetQaAnswers($conn, $questionId)
{
    $sql = "SELECT qa_answers.*, users.name AS author_name, users.role AS author_role
            FROM qa_answers
            INNER JOIN users ON users.id = qa_answers.author_id
            WHERE qa_answers.qa_question_id = ?
            ORDER BY qa_answers.created_at ASC";
    return dbSelectAll($conn, $sql, 'i', array((int)$questionId));
}

function taCreateQaAnswer($conn, $questionId, $taId, $body)
{
    $sql = "INSERT INTO qa_answers (qa_question_id, author_id, body)
            VALUES (?, ?, ?)";
    return dbExecute($conn, $sql, 'iis', array((int)$questionId, (int)$taId, $body));
}

function taEndorseQaAnswer($conn, $answerId, $taId)
{
    $sql = "UPDATE qa_answers
            INNER JOIN qa_questions ON qa_questions.id = qa_answers.qa_question_id
            INNER JOIN course_tas ON course_tas.course_id = qa_questions.course_id
            SET qa_answers.is_endorsed = 1
            WHERE qa_answers.id = ? AND course_tas.ta_id = ?";
    return dbExecute($conn, $sql, 'ii', array((int)$answerId, (int)$taId));
}

function taMarkQaResolved($conn, $questionId, $taId)
{
    $sql = "UPDATE qa_questions
            INNER JOIN course_tas ON course_tas.course_id = qa_questions.course_id
            SET qa_questions.is_resolved = 1
            WHERE qa_questions.id = ? AND course_tas.ta_id = ?";
    return dbExecute($conn, $sql, 'ii', array((int)$questionId, (int)$taId));
}
