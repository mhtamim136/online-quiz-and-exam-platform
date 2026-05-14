<?php
function quizGetAll($conn, $course_id, $status, $quiz_type)
{
    $sql = "SELECT quizzes.*, courses.title AS course_title, users.name AS creator_name,
                   (SELECT COUNT(*) FROM attempts WHERE attempts.quiz_id = quizzes.id) AS attempt_count
            FROM quizzes
            INNER JOIN courses ON courses.id = quizzes.course_id
            INNER JOIN users ON users.id = quizzes.created_by";
    $where = array();
    $types = '';
    $params = array();

    if ($course_id > 0) {
        $where[] = "quizzes.course_id = ?";
        $types .= 'i';
        $params[] = (int)$course_id;
    }

    if ($status !== '') {
        $where[] = "quizzes.status = ?";
        $types .= 's';
        $params[] = $status;
    }

    if ($quiz_type !== '') {
        $where[] = "quizzes.quiz_type = ?";
        $types .= 's';
        $params[] = $quiz_type;
    }

    if (count($where) > 0) {
        $sql .= ' WHERE ' . implode(' AND ', $where);
    }

    $sql .= " ORDER BY quizzes.created_at DESC, quizzes.id DESC";

    return dbSelectAll($conn, $sql, $types, $params);
}
