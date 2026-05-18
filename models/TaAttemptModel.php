<?php
function taGetAttemptResults($conn, $taId, $courseId, $quizId)
{
    $sql = "SELECT attempts.*, users.name AS student_name, users.email AS student_email,
                   quizzes.title AS quiz_title, quizzes.pass_mark, quizzes.total_marks,
                   courses.title AS course_title
            FROM attempts
            INNER JOIN quizzes ON quizzes.id = attempts.quiz_id
            INNER JOIN courses ON courses.id = quizzes.course_id
            INNER JOIN users ON users.id = attempts.student_id
            INNER JOIN course_tas ON course_tas.course_id = quizzes.course_id
            WHERE course_tas.ta_id = ?";
    $types = 'i';
    $params = array((int)$taId);

    if ($courseId > 0) {
        $sql .= " AND quizzes.course_id = ?";
        $types .= 'i';
        $params[] = (int)$courseId;
    }
    if ($quizId > 0) {
        $sql .= " AND attempts.quiz_id = ?";
        $types .= 'i';
        $params[] = (int)$quizId;
    }

    $sql .= " ORDER BY attempts.started_at DESC";
    return dbSelectAll($conn, $sql, $types, $params);
}
