<?php
function studentGetQuizList($studentId, $courseId)
{
    global $studentConn;

    $sql = "SELECT q.id, q.title, q.description, q.time_limit_minutes, q.total_marks, q.pass_mark,
                   q.quiz_type, q.available_from, q.available_until,
                   c.id AS course_id, c.title AS course_title,
                   (
                       SELECT COUNT(*)
                       FROM attempts a
                       WHERE a.quiz_id = q.id AND a.student_id = ?
                   ) AS my_attempt_count
            FROM quizzes q
            INNER JOIN courses c ON c.id = q.course_id
            INNER JOIN enrollments e ON e.course_id = c.id
            WHERE e.student_id = ?
              AND e.status = 'active'
              AND q.status = 'published'";

    $types = 'ii';
    $params = [$studentId, $studentId];

    if ($courseId > 0) {
        $sql .= " AND q.course_id = ?";
        $types .= 'i';
        $params[] = $courseId;
    }

    $sql .= " ORDER BY q.quiz_type ASC, q.available_until IS NULL, q.available_until ASC, q.title ASC";

    $stmt = mysqli_prepare($studentConn, $sql);
    studentBindParams($stmt, $types, $params);
    return studentFetchAll($stmt);
}

function studentGetAvailableQuizById($quizId, $studentId)
{
    global $studentConn;

    $sql = "SELECT q.id, q.course_id, q.title, q.description, q.time_limit_minutes, q.total_marks,
                   q.pass_mark, q.quiz_type, q.available_from, q.available_until, c.title AS course_title
            FROM quizzes q
            INNER JOIN courses c ON c.id = q.course_id
            INNER JOIN enrollments e ON e.course_id = c.id
            WHERE q.id = ?
              AND e.student_id = ?
              AND e.status = 'active'
              AND q.status = 'published'
              AND (q.available_from IS NULL OR q.available_from <= NOW())
              AND (q.available_until IS NULL OR q.available_until >= NOW())
            LIMIT 1";
    $stmt = mysqli_prepare($studentConn, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $quizId, $studentId);
    return studentFetchOne($stmt);
}

function studentGetQuizByIdForStudent($quizId, $studentId)
{
    global $studentConn;

    $sql = "SELECT q.id, q.course_id, q.title, q.description, q.time_limit_minutes, q.total_marks,
                   q.pass_mark, q.quiz_type, q.available_from, q.available_until, c.title AS course_title
            FROM quizzes q
            INNER JOIN courses c ON c.id = q.course_id
            INNER JOIN enrollments e ON e.course_id = c.id
            WHERE q.id = ?
              AND e.student_id = ?
              AND e.status = 'active'
              AND q.status = 'published'
            LIMIT 1";
    $stmt = mysqli_prepare($studentConn, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $quizId, $studentId);
    return studentFetchOne($stmt);
}

function studentGetQuizQuestions($quizId)
{
    global $studentConn;

    $sql = "SELECT id, question_text, marks, order_index
            FROM questions
            WHERE quiz_id = ?
            ORDER BY order_index ASC";
    $stmt = mysqli_prepare($studentConn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $quizId);
    $questionRows = studentFetchAll($stmt);

    $questions = [];
    foreach ($questionRows as $question) {
        $question['options'] = [];
        $questions[$question['id']] = $question;
    }

    if (empty($questions)) {
        return [];
    }

    $sql = "SELECT o.id, o.question_id, o.option_text, o.is_correct
            FROM options o
            INNER JOIN questions q ON q.id = o.question_id
            WHERE q.quiz_id = ?
            ORDER BY o.id ASC";
    $stmt = mysqli_prepare($studentConn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $quizId);
    $options = studentFetchAll($stmt);

    foreach ($options as $option) {
        if (isset($questions[$option['question_id']])) {
            $questions[$option['question_id']]['options'][] = $option;
        }
    }

    return array_values($questions);
}

function studentHasCompletedGradedAttempt($studentId, $quizId)
{
    global $studentConn;

    $sql = "SELECT a.id
            FROM attempts a
            INNER JOIN quizzes q ON q.id = a.quiz_id
            WHERE a.student_id = ?
              AND a.quiz_id = ?
              AND q.quiz_type = 'graded'
              AND a.completed_at IS NOT NULL
            LIMIT 1";
    $stmt = mysqli_prepare($studentConn, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $studentId, $quizId);
    $row = studentFetchOne($stmt);
    return $row ? true : false;
}

function studentCreateAttemptWithAnswers($quizId, $studentId, $score, $isGraded, $answers)
{
    global $studentConn;

    mysqli_begin_transaction($studentConn);

    $sql = "INSERT INTO attempts (quiz_id, student_id, score, started_at, completed_at, is_graded)
            VALUES (?, ?, ?, NOW(), NOW(), ?)";
    $stmt = mysqli_prepare($studentConn, $sql);
    mysqli_stmt_bind_param($stmt, 'iidi', $quizId, $studentId, $score, $isGraded);

    if (!mysqli_stmt_execute($stmt)) {
        mysqli_rollback($studentConn);
        return 0;
    }

    $attemptId = mysqli_insert_id($studentConn);

    $sql = "INSERT INTO answers (attempt_id, question_id, selected_option_id, created_at)
            VALUES (?, ?, ?, NOW())";
    $stmt = mysqli_prepare($studentConn, $sql);

    foreach ($answers as $questionId => $optionId) {
        $questionId = (int)$questionId;
        $optionId = (int)$optionId;
        mysqli_stmt_bind_param($stmt, 'iii', $attemptId, $questionId, $optionId);

        if (!mysqli_stmt_execute($stmt)) {
            mysqli_rollback($studentConn);
            return 0;
        }
    }

    mysqli_commit($studentConn);
    return $attemptId;
}

function studentGetAttemptResult($attemptId, $studentId)
{
    global $studentConn;

    $sql = "SELECT a.id, a.score, a.started_at, a.completed_at, a.is_graded,
                   q.id AS quiz_id, q.title AS quiz_title, q.total_marks, q.pass_mark, q.quiz_type,
                   c.title AS course_title
            FROM attempts a
            INNER JOIN quizzes q ON q.id = a.quiz_id
            INNER JOIN courses c ON c.id = q.course_id
            WHERE a.id = ? AND a.student_id = ?
            LIMIT 1";
    $stmt = mysqli_prepare($studentConn, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $attemptId, $studentId);
    return studentFetchOne($stmt);
}

function studentGetAttemptBreakdown($attemptId, $studentId)
{
    global $studentConn;

    $sql = "SELECT q.question_text, q.marks,
                   selected.option_text AS selected_text,
                   correct.option_text AS correct_text,
                   selected.is_correct AS selected_is_correct
            FROM attempts a
            INNER JOIN answers ans ON ans.attempt_id = a.id
            INNER JOIN questions q ON q.id = ans.question_id
            LEFT JOIN options selected ON selected.id = ans.selected_option_id
            LEFT JOIN options correct ON correct.question_id = q.id AND correct.is_correct = 1
            WHERE a.id = ? AND a.student_id = ?
            ORDER BY q.order_index ASC";
    $stmt = mysqli_prepare($studentConn, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $attemptId, $studentId);
    return studentFetchAll($stmt);
}

function studentGetAttemptHistory($studentId)
{
    global $studentConn;

    $sql = "SELECT a.id, a.score, a.started_at, a.completed_at,
                   q.title AS quiz_title, q.total_marks, q.pass_mark, q.quiz_type,
                   c.title AS course_title
            FROM attempts a
            INNER JOIN quizzes q ON q.id = a.quiz_id
            INNER JOIN courses c ON c.id = q.course_id
            WHERE a.student_id = ?
            ORDER BY a.completed_at DESC, a.started_at DESC";
    $stmt = mysqli_prepare($studentConn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $studentId);
    return studentFetchAll($stmt);
}

function studentGetLeaderboardQuizChoices($studentId)
{
    global $studentConn;

    $sql = "SELECT DISTINCT q.id, q.title, c.title AS course_title
            FROM quizzes q
            INNER JOIN courses c ON c.id = q.course_id
            INNER JOIN enrollments e ON e.course_id = c.id
            WHERE e.student_id = ?
              AND e.status = 'active'
              AND q.status = 'published'
            ORDER BY c.title ASC, q.title ASC";
    $stmt = mysqli_prepare($studentConn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $studentId);
    return studentFetchAll($stmt);
}

function studentGetLeaderboard($quizId, $studentId)
{
    global $studentConn;

    $allowed = studentGetQuizByIdForStudent($quizId, $studentId);
    if (!$allowed) {
        return [];
    }

    $sql = "SELECT u.name, u.student_id, MAX(a.score) AS best_score, COUNT(a.id) AS attempt_count,
                   MAX(a.completed_at) AS last_completed_at
            FROM attempts a
            INNER JOIN users u ON u.id = a.student_id
            WHERE a.quiz_id = ?
              AND a.completed_at IS NOT NULL
              AND u.role = 'student'
            GROUP BY u.id, u.name, u.student_id
            ORDER BY best_score DESC, last_completed_at ASC
            LIMIT 10";
    $stmt = mysqli_prepare($studentConn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $quizId);
    return studentFetchAll($stmt);
}
?>
