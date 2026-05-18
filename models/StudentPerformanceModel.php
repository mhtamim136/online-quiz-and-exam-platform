<?php
function studentGetPerformanceSummary($studentId)
{
    global $studentConn;

    $sql = "SELECT COUNT(a.id) AS total_attempts,
                   AVG(a.score) AS average_score,
                   SUM(CASE WHEN a.score >= q.pass_mark THEN 1 ELSE 0 END) AS passed_count
            FROM attempts a
            INNER JOIN quizzes q ON q.id = a.quiz_id
            WHERE a.student_id = ? AND a.completed_at IS NOT NULL";
    $stmt = mysqli_prepare($studentConn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $studentId);
    $row = studentFetchOne($stmt);

    $total = $row ? (int)$row['total_attempts'] : 0;
    $passed = $row ? (int)$row['passed_count'] : 0;

    return [
        'total_attempts' => $total,
        'average_score' => $row && $row['average_score'] !== null ? round((float)$row['average_score'], 2) : 0,
        'pass_rate' => $total > 0 ? round(($passed / $total) * 100, 2) : 0
    ];
}

function studentGetScoresOverTime($studentId)
{
    global $studentConn;

    $sql = "SELECT a.score, a.completed_at, q.title AS quiz_title, q.total_marks, c.title AS course_title
            FROM attempts a
            INNER JOIN quizzes q ON q.id = a.quiz_id
            INNER JOIN courses c ON c.id = q.course_id
            WHERE a.student_id = ? AND a.completed_at IS NOT NULL
            ORDER BY a.completed_at ASC";
    $stmt = mysqli_prepare($studentConn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $studentId);
    return studentFetchAll($stmt);
}

function studentGetAverageScoreBySubject($studentId)
{
    global $studentConn;

    $sql = "SELECT s.name AS subject_name, AVG(a.score) AS average_score, COUNT(a.id) AS attempt_count
            FROM attempts a
            INNER JOIN quizzes q ON q.id = a.quiz_id
            INNER JOIN courses c ON c.id = q.course_id
            INNER JOIN subjects s ON s.id = c.subject_id
            WHERE a.student_id = ? AND a.completed_at IS NOT NULL
            GROUP BY s.id, s.name
            ORDER BY s.name ASC";
    $stmt = mysqli_prepare($studentConn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $studentId);
    return studentFetchAll($stmt);
}

function studentGetClassComparison($studentId)
{
    global $studentConn;

    $sql = "SELECT q.title AS quiz_title, c.title AS course_title,
                   MAX(CASE WHEN a.student_id = ? THEN a.score ELSE NULL END) AS my_best_score,
                   AVG(a.score) AS class_average
            FROM attempts a
            INNER JOIN quizzes q ON q.id = a.quiz_id
            INNER JOIN courses c ON c.id = q.course_id
            INNER JOIN enrollments e ON e.course_id = c.id
            WHERE e.student_id = ?
              AND e.status = 'active'
              AND a.completed_at IS NOT NULL
            GROUP BY q.id, q.title, c.title
            HAVING my_best_score IS NOT NULL
            ORDER BY q.title ASC";
    $stmt = mysqli_prepare($studentConn, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $studentId, $studentId);
    return studentFetchAll($stmt);
}
?>
