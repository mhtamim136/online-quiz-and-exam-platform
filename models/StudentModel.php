<?php
function studentFetchOne($stmt)
{
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

function studentFetchAll($stmt)
{
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rows = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
}

function studentFindByEmail($email)
{
    global $studentConn;

    $sql = "SELECT id, name, email, password_hash, phone, role, profile_pic, student_id, program, is_active
            FROM users
            WHERE email = ?
            LIMIT 1";
    $stmt = mysqli_prepare($studentConn, $sql);
    mysqli_stmt_bind_param($stmt, 's', $email);
    return studentFetchOne($stmt);
}

function studentFindById($studentId)
{
    global $studentConn;

    $studentId = (int)$studentId;
    if ($studentId <= 0 && isset($_SESSION['user_id'])) {
        $studentId = (int)$_SESSION['user_id'];
    }

    $sql = "SELECT *
            FROM users
            WHERE id = ? AND role = 'student'
            LIMIT 1";
    $stmt = mysqli_prepare($studentConn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $studentId);
    return studentFetchOne($stmt);
}

function studentEmailExists($email, $excludeId)
{
    global $studentConn;

    $sql = "SELECT id FROM users WHERE email = ? AND id <> ? LIMIT 1";
    $stmt = mysqli_prepare($studentConn, $sql);
    mysqli_stmt_bind_param($stmt, 'si', $email, $excludeId);
    $row = studentFetchOne($stmt);
    return $row ? true : false;
}

function studentStudentIdExists($studentNumber, $excludeId)
{
    global $studentConn;

    $sql = "SELECT id FROM users WHERE student_id = ? AND id <> ? LIMIT 1";
    $stmt = mysqli_prepare($studentConn, $sql);
    mysqli_stmt_bind_param($stmt, 'si', $studentNumber, $excludeId);
    $row = studentFetchOne($stmt);
    return $row ? true : false;
}

function studentCreateAccount($data)
{
    global $studentConn;

    $role = 'student';
    $isActive = 1;
    $approvalStatus = 'approved';
    $profilePic = null;
    $username = null;

    $sql = "INSERT INTO users
            (username, name, email, password_hash, phone, role, profile_pic, student_id, program, approval_status, is_active, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt = mysqli_prepare($studentConn, $sql);
    mysqli_stmt_bind_param(
        $stmt,
        'ssssssssssi',
        $username,
        $data['name'],
        $data['email'],
        $data['password_hash'],
        $data['phone'],
        $role,
        $profilePic,
        $data['student_id'],
        $data['program'],
        $approvalStatus,
        $isActive
    );

    return mysqli_stmt_execute($stmt);
}

function studentUpdateProfile($studentId, $name, $phone, $program)
{
    global $studentConn;

    $sql = "UPDATE users
            SET name = ?, phone = ?, program = ?
            WHERE id = ? AND role = 'student'";
    $stmt = mysqli_prepare($studentConn, $sql);
    mysqli_stmt_bind_param($stmt, 'sssi', $name, $phone, $program, $studentId);
    return mysqli_stmt_execute($stmt);
}

function studentUpdateProfilePicture($studentId, $profilePic)
{
    global $studentConn;

    $sql = "UPDATE users SET profile_pic = ? WHERE id = ? AND role = 'student'";
    $stmt = mysqli_prepare($studentConn, $sql);
    mysqli_stmt_bind_param($stmt, 'si', $profilePic, $studentId);
    return mysqli_stmt_execute($stmt);
}

function studentUpdatePassword($studentId, $passwordHash)
{
    global $studentConn;

    $sql = "UPDATE users SET password_hash = ? WHERE id = ? AND role = 'student'";
    $stmt = mysqli_prepare($studentConn, $sql);
    mysqli_stmt_bind_param($stmt, 'si', $passwordHash, $studentId);
    return mysqli_stmt_execute($stmt);
}

function studentGetDashboardStats($studentId)
{
    global $studentConn;

    $stats = [
        'total_enrolled' => 0,
        'pending_enrollments' => 0,
        'active_enrollments' => 0,
        'recent_attempts' => [],
        'upcoming_quizzes' => [],
        'upcoming_doubt_sessions' => []
    ];

    $sql = "SELECT
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) AS active_count,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS pending_count,
                COUNT(*) AS total_count
            FROM enrollments
            WHERE student_id = ? AND status <> 'dropped'";
    $stmt = mysqli_prepare($studentConn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $studentId);
    $row = studentFetchOne($stmt);

    if ($row) {
        $stats['total_enrolled'] = (int)$row['total_count'];
        $stats['pending_enrollments'] = (int)$row['pending_count'];
        $stats['active_enrollments'] = (int)$row['active_count'];
    }

    $sql = "SELECT q.id, q.title, q.quiz_type, q.available_until, c.title AS course_title
            FROM quizzes q
            INNER JOIN courses c ON c.id = q.course_id
            INNER JOIN enrollments e ON e.course_id = c.id
            WHERE e.student_id = ?
              AND e.status = 'active'
              AND q.status = 'published'
              AND (q.available_from IS NULL OR q.available_from <= NOW())
              AND (q.available_until IS NULL OR q.available_until >= NOW())
            ORDER BY q.available_until IS NULL, q.available_until ASC, q.created_at DESC
            LIMIT 5";
    $stmt = mysqli_prepare($studentConn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $studentId);
    $stats['upcoming_quizzes'] = studentFetchAll($stmt);

    $sql = "SELECT a.id, a.score, a.completed_at, q.title AS quiz_title, q.pass_mark, c.title AS course_title
            FROM attempts a
            INNER JOIN quizzes q ON q.id = a.quiz_id
            INNER JOIN courses c ON c.id = q.course_id
            WHERE a.student_id = ?
            ORDER BY a.completed_at DESC, a.started_at DESC
            LIMIT 5";
    $stmt = mysqli_prepare($studentConn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $studentId);
    $stats['recent_attempts'] = studentFetchAll($stmt);

    $sql = "SELECT ds.id, ds.title, ds.scheduled_at, ds.location_or_link, c.title AS course_title
            FROM doubt_session_bookings b
            INNER JOIN doubt_sessions ds ON ds.id = b.doubt_session_id
            INNER JOIN courses c ON c.id = ds.course_id
            WHERE b.student_id = ?
              AND b.booking_status = 'booked'
              AND ds.status = 'scheduled'
              AND ds.scheduled_at >= NOW()
            ORDER BY ds.scheduled_at ASC
            LIMIT 5";
    $stmt = mysqli_prepare($studentConn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $studentId);
    $stats['upcoming_doubt_sessions'] = studentFetchAll($stmt);

    return $stats;
}
?>
