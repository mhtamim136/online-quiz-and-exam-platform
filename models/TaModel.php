<?php
function taGetProfile($conn, $taId)
{
    $sql = "SELECT id, username, name, email, phone, profile_pic, department, bio, created_at
            FROM users
            WHERE id = ? AND role = 'ta'
            LIMIT 1";
    return dbSelectOne($conn, $sql, 'i', array((int)$taId));
}

function taUpdateProfile($conn, $taId, $data)
{
    $sql = "UPDATE users
            SET name = ?, phone = ?, department = ?, bio = ?
            WHERE id = ? AND role = 'ta'";
    return dbExecute(
        $conn,
        $sql,
        'ssssi',
        array($data['name'], $data['phone'], $data['department'], $data['bio'], (int)$taId)
    );
}

function taUpdateProfilePic($conn, $taId, $path)
{
    $sql = "UPDATE users SET profile_pic = ? WHERE id = ? AND role = 'ta'";
    return dbExecute($conn, $sql, 'si', array($path, (int)$taId));
}

function taUpdatePassword($conn, $taId, $hash)
{
    $sql = "UPDATE users SET password_hash = ? WHERE id = ? AND role = 'ta'";
    return dbExecute($conn, $sql, 'si', array($hash, (int)$taId));
}

function taGetAtRiskThreshold($conn)
{
    $row = dbSelectOne(
        $conn,
        "SELECT setting_value FROM platform_settings WHERE setting_key = 'at_risk_score_threshold' LIMIT 1"
    );

    if (!$row || !is_numeric($row['setting_value'])) {
        return 40;
    }

    return (float)$row['setting_value'];
}

function taGetDashboardStats($conn, $taId)
{
    $threshold = taGetAtRiskThreshold($conn);
    $stats = array();

    $stats['assigned_courses'] = dbSelectOne(
        $conn,
        "SELECT COUNT(*) AS total FROM course_tas WHERE ta_id = ?",
        'i',
        array((int)$taId)
    );
    $stats['enrolled_students'] = dbSelectOne(
        $conn,
        "SELECT COUNT(DISTINCT enrollments.student_id) AS total
         FROM course_tas
         INNER JOIN enrollments ON enrollments.course_id = course_tas.course_id
         WHERE course_tas.ta_id = ? AND enrollments.status = 'active'",
        'i',
        array((int)$taId)
    );
    $stats['quizzes'] = dbSelectOne(
        $conn,
        "SELECT COUNT(*) AS total
         FROM quizzes
         INNER JOIN course_tas ON course_tas.course_id = quizzes.course_id
         WHERE course_tas.ta_id = ?",
        'i',
        array((int)$taId)
    );
    $stats['practice_quizzes'] = dbSelectOne(
        $conn,
        "SELECT COUNT(*) AS total
         FROM quizzes
         WHERE created_by = ? AND quiz_type = 'practice'",
        'i',
        array((int)$taId)
    );
    $stats['upcoming_sessions'] = dbSelectOne(
        $conn,
        "SELECT COUNT(*) AS total
         FROM doubt_sessions
         WHERE ta_id = ? AND scheduled_at >= NOW() AND status = 'scheduled'",
        'i',
        array((int)$taId)
    );
    $stats['open_questions'] = dbSelectOne(
        $conn,
        "SELECT COUNT(*) AS total
         FROM qa_questions
         INNER JOIN course_tas ON course_tas.course_id = qa_questions.course_id
         WHERE course_tas.ta_id = ? AND qa_questions.is_resolved = 0",
        'i',
        array((int)$taId)
    );
    $stats['at_risk_students'] = dbSelectOne(
        $conn,
        "SELECT COUNT(DISTINCT attempts.student_id) AS total
         FROM attempts
         INNER JOIN quizzes ON quizzes.id = attempts.quiz_id
         INNER JOIN course_tas ON course_tas.course_id = quizzes.course_id
         WHERE course_tas.ta_id = ?
           AND (CASE WHEN quizzes.total_marks > 0 THEN (attempts.score / quizzes.total_marks) * 100 ELSE attempts.score END) < ?",
        'id',
        array((int)$taId, $threshold)
    );
    $stats['threshold'] = $threshold;

    return $stats;
}

function taGetRecentQuestions($conn, $taId)
{
    $sql = "SELECT qa_questions.*, courses.title AS course_title, users.name AS student_name
            FROM qa_questions
            INNER JOIN courses ON courses.id = qa_questions.course_id
            INNER JOIN users ON users.id = qa_questions.student_id
            INNER JOIN course_tas ON course_tas.course_id = qa_questions.course_id
            WHERE course_tas.ta_id = ?
            ORDER BY qa_questions.created_at DESC
            LIMIT 6";
    return dbSelectAll($conn, $sql, 'i', array((int)$taId));
}

function taGetUpcomingDoubtSessions($conn, $taId)
{
    $sql = "SELECT doubt_sessions.*, courses.title AS course_title
            FROM doubt_sessions
            INNER JOIN courses ON courses.id = doubt_sessions.course_id
            WHERE doubt_sessions.ta_id = ?
              AND doubt_sessions.scheduled_at >= NOW()
              AND doubt_sessions.status = 'scheduled'
            ORDER BY doubt_sessions.scheduled_at ASC
            LIMIT 6";
    return dbSelectAll($conn, $sql, 'i', array((int)$taId));
}
