<?php
function taGetAtRiskStudents($conn, $taId, $courseId, $threshold)
{
    $sql = "SELECT users.id AS student_user_id, users.name AS student_name, users.email,
                   users.student_id, courses.id AS course_id, courses.title AS course_title,
                   quizzes.title AS quiz_title, attempts.score, quizzes.total_marks,
                   ROUND(CASE WHEN quizzes.total_marks > 0 THEN (attempts.score / quizzes.total_marks) * 100 ELSE attempts.score END, 2) AS percentage_score,
                   ROUND(avg_scores.average_score, 2) AS average_score
            FROM attempts
            INNER JOIN quizzes ON quizzes.id = attempts.quiz_id
            INNER JOIN courses ON courses.id = quizzes.course_id
            INNER JOIN users ON users.id = attempts.student_id
            INNER JOIN course_tas ON course_tas.course_id = quizzes.course_id
            LEFT JOIN (
                SELECT attempts.student_id, quizzes.course_id,
                       AVG(CASE WHEN quizzes.total_marks > 0 THEN (attempts.score / quizzes.total_marks) * 100 ELSE attempts.score END) AS average_score
                FROM attempts
                INNER JOIN quizzes ON quizzes.id = attempts.quiz_id
                GROUP BY attempts.student_id, quizzes.course_id
            ) avg_scores ON avg_scores.student_id = attempts.student_id AND avg_scores.course_id = quizzes.course_id
            WHERE course_tas.ta_id = ?";
    $types = 'i';
    $params = array((int)$taId);

    if ($courseId > 0) {
        $sql .= " AND quizzes.course_id = ?";
        $types .= 'i';
        $params[] = (int)$courseId;
    }

    $sql .= " AND (CASE WHEN quizzes.total_marks > 0 THEN (attempts.score / quizzes.total_marks) * 100 ELSE attempts.score END) < ?
              ORDER BY percentage_score ASC, users.name ASC";
    $types .= 'd';
    $params[] = (float)$threshold;

    return dbSelectAll($conn, $sql, $types, $params);
}
