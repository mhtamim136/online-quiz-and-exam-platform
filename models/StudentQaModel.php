<?php
function studentGetQaQuestions($courseId, $studentId)
{
    global $studentConn;

    $sql = "SELECT q.id, q.title, q.body, q.is_resolved, q.created_at,
                   u.name AS student_name,
                   COUNT(a.id) AS answer_count,
                   SUM(CASE WHEN a.is_endorsed = 1 THEN 1 ELSE 0 END) AS endorsed_count
            FROM qa_questions q
            INNER JOIN users u ON u.id = q.student_id
            INNER JOIN enrollments e ON e.course_id = q.course_id
            LEFT JOIN qa_answers a ON a.qa_question_id = q.id
            WHERE q.course_id = ?
              AND e.student_id = ?
              AND e.status = 'active'
            GROUP BY q.id, q.title, q.body, q.is_resolved, q.created_at, u.name
            ORDER BY q.created_at DESC";
    $stmt = mysqli_prepare($studentConn, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $courseId, $studentId);
    return studentFetchAll($stmt);
}

function studentCreateQaQuestion($courseId, $studentId, $title, $body)
{
    global $studentConn;

    $sql = "INSERT INTO qa_questions (course_id, student_id, title, body, is_resolved, created_at)
            VALUES (?, ?, ?, ?, 0, NOW())";
    $stmt = mysqli_prepare($studentConn, $sql);
    mysqli_stmt_bind_param($stmt, 'iiss', $courseId, $studentId, $title, $body);
    return mysqli_stmt_execute($stmt);
}

function studentGetQaQuestionById($questionId, $studentId)
{
    global $studentConn;

    $sql = "SELECT q.id, q.course_id, q.student_id, q.title, q.body, q.is_resolved, q.created_at,
                   u.name AS student_name, c.title AS course_title
            FROM qa_questions q
            INNER JOIN users u ON u.id = q.student_id
            INNER JOIN courses c ON c.id = q.course_id
            INNER JOIN enrollments e ON e.course_id = q.course_id
            WHERE q.id = ?
              AND e.student_id = ?
              AND e.status = 'active'
            LIMIT 1";
    $stmt = mysqli_prepare($studentConn, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $questionId, $studentId);
    return studentFetchOne($stmt);
}

function studentGetQaAnswers($questionId)
{
    global $studentConn;

    $sql = "SELECT a.id, a.body, a.is_endorsed, a.created_at, u.name AS author_name, u.role AS author_role
            FROM qa_answers a
            INNER JOIN users u ON u.id = a.author_id
            WHERE a.qa_question_id = ?
            ORDER BY a.is_endorsed DESC, a.created_at ASC";
    $stmt = mysqli_prepare($studentConn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $questionId);
    return studentFetchAll($stmt);
}

function studentMarkQaResolved($questionId, $studentId)
{
    global $studentConn;

    $sql = "UPDATE qa_questions
            SET is_resolved = 1
            WHERE id = ? AND student_id = ?";
    $stmt = mysqli_prepare($studentConn, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $questionId, $studentId);
    return mysqli_stmt_execute($stmt);
}
?>
