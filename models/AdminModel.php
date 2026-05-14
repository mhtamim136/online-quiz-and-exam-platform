<?php
function adminGetDashboardStats($conn)
{
    $stats = array();
    $stats['users_by_role'] = dbSelectAll($conn, "SELECT role, COUNT(*) AS total FROM users GROUP BY role ORDER BY role ASC");
    $stats['active_courses'] = dbSelectOne($conn, "SELECT COUNT(*) AS total FROM courses WHERE status = 'active'");
    $stats['attempts_today'] = dbSelectOne($conn, "SELECT COUNT(*) AS total FROM attempts WHERE DATE(started_at) = CURDATE()");
    $stats['pending_instructors'] = dbSelectOne($conn, "SELECT COUNT(*) AS total FROM users WHERE role = 'instructor' AND approval_status = 'pending'");
    $stats['subjects'] = dbSelectOne($conn, "SELECT COUNT(*) AS total FROM subjects");
    $stats['quizzes'] = dbSelectOne($conn, "SELECT COUNT(*) AS total FROM quizzes");
    $stats['open_reports'] = dbSelectOne($conn, "SELECT COUNT(*) AS total FROM integrity_reports WHERE status IN ('open', 'under_review')");
    $stats['recent_logs'] = dbSelectAll(
        $conn,
        "SELECT audit_logs.*, users.name AS admin_name
         FROM audit_logs
         LEFT JOIN users ON users.id = audit_logs.admin_id
         ORDER BY audit_logs.created_at DESC
         LIMIT 8"
    );
    return $stats;
}

function adminGetStudentEnrollments($conn, $student_id)
{
    $sql = "SELECT enrollments.*, courses.title AS course_title, courses.status AS course_status,
                   subjects.name AS subject_name
            FROM enrollments
            INNER JOIN courses ON courses.id = enrollments.course_id
            INNER JOIN subjects ON subjects.id = courses.subject_id
            WHERE enrollments.student_id = ?
            ORDER BY enrollments.enrolled_at DESC";
    return dbSelectAll($conn, $sql, 'i', array((int)$student_id));
}

function adminGetStudentAttemptSummary($conn, $student_id)
{
    $sql = "SELECT COUNT(*) AS attempt_count, AVG(score) AS average_score
            FROM attempts
            WHERE student_id = ?";
    return dbSelectOne($conn, $sql, 'i', array((int)$student_id));
}

function adminGetStudentAttempts($conn, $student_id)
{
    $sql = "SELECT attempts.*, quizzes.title AS quiz_title, quizzes.pass_mark,
                   courses.title AS course_title
            FROM attempts
            INNER JOIN quizzes ON quizzes.id = attempts.quiz_id
            INNER JOIN courses ON courses.id = quizzes.course_id
            WHERE attempts.student_id = ?
            ORDER BY attempts.started_at DESC";
    return dbSelectAll($conn, $sql, 'i', array((int)$student_id));
}

function adminGetInstitutionalTotals($conn)
{
    return array(
        'users' => dbSelectOne($conn, "SELECT COUNT(*) AS total FROM users"),
        'active_courses' => dbSelectOne($conn, "SELECT COUNT(*) AS total FROM courses WHERE status = 'active'"),
        'quizzes' => dbSelectOne($conn, "SELECT COUNT(*) AS total FROM quizzes"),
        'attempts' => dbSelectOne($conn, "SELECT COUNT(*) AS total FROM attempts")
    );
}

function adminGetPassRatesPerSubject($conn)
{
    $sql = "SELECT subjects.name AS subject_name,
                   COUNT(attempts.id) AS total_attempts,
                   SUM(CASE WHEN attempts.score >= quizzes.pass_mark THEN 1 ELSE 0 END) AS passed_attempts,
                   ROUND(
                       CASE WHEN COUNT(attempts.id) = 0 THEN 0
                       ELSE (SUM(CASE WHEN attempts.score >= quizzes.pass_mark THEN 1 ELSE 0 END) / COUNT(attempts.id)) * 100
                       END,
                       2
                   ) AS pass_rate
            FROM subjects
            LEFT JOIN courses ON courses.subject_id = subjects.id
            LEFT JOIN quizzes ON quizzes.course_id = courses.id
            LEFT JOIN attempts ON attempts.quiz_id = quizzes.id
            GROUP BY subjects.id, subjects.name
            ORDER BY subjects.name ASC";
    return dbSelectAll($conn, $sql);
}

function adminGetEnrollmentsPerSubject($conn)
{
    $sql = "SELECT subjects.name AS subject_name, COUNT(enrollments.id) AS total_enrollments
            FROM subjects
            LEFT JOIN courses ON courses.subject_id = subjects.id
            LEFT JOIN enrollments ON enrollments.course_id = courses.id
            GROUP BY subjects.id, subjects.name
            ORDER BY subjects.name ASC";
    return dbSelectAll($conn, $sql);
}

function adminGetMostActiveInstructors($conn)
{
    $sql = "SELECT users.id, users.name, users.email,
                   COUNT(DISTINCT courses.id) AS course_count,
                   COUNT(DISTINCT quizzes.id) AS quiz_count,
                   COUNT(attempts.id) AS attempt_count
            FROM users
            LEFT JOIN courses ON courses.instructor_id = users.id
            LEFT JOIN quizzes ON quizzes.course_id = courses.id
            LEFT JOIN attempts ON attempts.quiz_id = quizzes.id
            WHERE users.role = 'instructor'
            GROUP BY users.id, users.name, users.email
            ORDER BY attempt_count DESC, quiz_count DESC, course_count DESC
            LIMIT 10";
    return dbSelectAll($conn, $sql);
}
