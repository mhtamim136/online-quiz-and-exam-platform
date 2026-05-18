<?php
function instructorAnalyticsAttempts($quizId, $instructorId)
{
    $sql = 'SELECT a.*, u.name AS student_name, u.email AS student_email
            FROM attempts a
            INNER JOIN quizzes q ON q.id = a.quiz_id
            INNER JOIN courses c ON c.id = q.course_id
            INNER JOIN users u ON u.id = a.student_id
            WHERE a.quiz_id = ? AND c.instructor_id = ?
            ORDER BY a.completed_at DESC';
    return instructorDbFetchAll($sql, 'ii', array($quizId, $instructorId));
}

function instructorAnalyticsSummary($quizId, $instructorId)
{
    $sql = 'SELECT AVG(a.score) AS class_average, MAX(a.score) AS highest_score, MIN(a.score) AS lowest_score,
            SUM(CASE WHEN a.score >= q.pass_mark THEN 1 ELSE 0 END) AS passed,
            COUNT(*) AS total_attempts
            FROM attempts a
            INNER JOIN quizzes q ON q.id = a.quiz_id
            INNER JOIN courses c ON c.id = q.course_id
            WHERE a.quiz_id = ? AND c.instructor_id = ?';
    return instructorDbFetchOne($sql, 'ii', array($quizId, $instructorId));
}

function instructorReportsByCourse($instructorId)
{
    $sql = 'SELECT c.id, c.title, c.status,
            COUNT(DISTINCT e.student_id) AS students,
            COUNT(DISTINCT q.id) AS quizzes,
            AVG(a.score) AS average_score
            FROM courses c
            LEFT JOIN enrollments e ON e.course_id = c.id AND e.status = ?
            LEFT JOIN quizzes q ON q.course_id = c.id
            LEFT JOIN attempts a ON a.quiz_id = q.id
            WHERE c.instructor_id = ?
            GROUP BY c.id, c.title, c.status
            ORDER BY c.title ASC';
    return instructorDbFetchAll($sql, 'si', array('active', $instructorId));
}
