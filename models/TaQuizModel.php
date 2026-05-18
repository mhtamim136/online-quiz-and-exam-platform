<?php
function taGetQuizzes($conn, $taId, $courseId, $status, $quizType)
{
    $sql = "SELECT quizzes.*, courses.title AS course_title, users.name AS created_by_name,
                   COUNT(attempts.id) AS attempt_count
            FROM quizzes
            INNER JOIN courses ON courses.id = quizzes.course_id
            INNER JOIN users ON users.id = quizzes.created_by
            INNER JOIN course_tas ON course_tas.course_id = quizzes.course_id
            LEFT JOIN attempts ON attempts.quiz_id = quizzes.id
            WHERE course_tas.ta_id = ?";
    $types = 'i';
    $params = array((int)$taId);

    if ($courseId > 0) {
        $sql .= " AND quizzes.course_id = ?";
        $types .= 'i';
        $params[] = (int)$courseId;
    }
    if ($status !== '') {
        $sql .= " AND quizzes.status = ?";
        $types .= 's';
        $params[] = $status;
    }
    if ($quizType !== '') {
        $sql .= " AND quizzes.quiz_type = ?";
        $types .= 's';
        $params[] = $quizType;
    }

    $sql .= " GROUP BY quizzes.id, courses.title, users.name
              ORDER BY quizzes.created_at DESC";

    return dbSelectAll($conn, $sql, $types, $params);
}

function taGetQuiz($conn, $quizId, $taId)
{
    $sql = "SELECT quizzes.*, courses.title AS course_title
            FROM quizzes
            INNER JOIN courses ON courses.id = quizzes.course_id
            INNER JOIN course_tas ON course_tas.course_id = quizzes.course_id
            WHERE quizzes.id = ? AND course_tas.ta_id = ?
            LIMIT 1";
    return dbSelectOne($conn, $sql, 'ii', array((int)$quizId, (int)$taId));
}

function taCreatePracticeQuiz($conn, $data)
{
    $sql = "INSERT INTO quizzes
            (course_id, created_by, title, description, time_limit_minutes, total_marks, pass_mark, quiz_type, status, available_from, available_until)
            VALUES (?, ?, ?, ?, ?, ?, ?, 'practice', 'draft', ?, ?)";
    return dbExecute(
        $conn,
        $sql,
        'iissiddss',
        array(
            (int)$data['course_id'],
            (int)$data['created_by'],
            $data['title'],
            $data['description'],
            (int)$data['time_limit_minutes'],
            (float)$data['total_marks'],
            (float)$data['pass_mark'],
            $data['available_from'],
            $data['available_until']
        )
    );
}

function taUpdatePracticeQuiz($conn, $quizId, $taId, $data)
{
    $sql = "UPDATE quizzes
            SET title = ?, description = ?, time_limit_minutes = ?, total_marks = ?, pass_mark = ?,
                available_from = ?, available_until = ?
            WHERE id = ? AND created_by = ? AND quiz_type = 'practice'";
    return dbExecute(
        $conn,
        $sql,
        'ssiddssii',
        array(
            $data['title'],
            $data['description'],
            (int)$data['time_limit_minutes'],
            (float)$data['total_marks'],
            (float)$data['pass_mark'],
            $data['available_from'],
            $data['available_until'],
            (int)$quizId,
            (int)$taId
        )
    );
}

function taGetPracticeQuizzesForCourse($conn, $courseId, $taId)
{
    $sql = "SELECT quizzes.id, quizzes.title
            FROM quizzes
            INNER JOIN course_tas ON course_tas.course_id = quizzes.course_id
            WHERE quizzes.course_id = ?
              AND course_tas.ta_id = ?
              AND quizzes.quiz_type = 'practice'
            ORDER BY quizzes.created_at DESC";
    return dbSelectAll($conn, $sql, 'ii', array((int)$courseId, (int)$taId));
}
