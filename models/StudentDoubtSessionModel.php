<?php
function studentGetUpcomingDoubtSessions($studentId)
{
    global $studentConn;

    $sql = "SELECT ds.id, ds.title, ds.scheduled_at, ds.duration_minutes, ds.location_or_link,
                   ds.max_attendees, c.title AS course_title, u.name AS ta_name,
                   COUNT(CASE WHEN b.booking_status = 'booked' THEN 1 END) AS booked_count,
                   my_b.booking_status AS my_booking_status
            FROM doubt_sessions ds
            INNER JOIN courses c ON c.id = ds.course_id
            INNER JOIN users u ON u.id = ds.ta_id
            INNER JOIN enrollments e ON e.course_id = ds.course_id
            LEFT JOIN doubt_session_bookings b ON b.doubt_session_id = ds.id
            LEFT JOIN doubt_session_bookings my_b ON my_b.doubt_session_id = ds.id AND my_b.student_id = ?
            WHERE e.student_id = ?
              AND e.status = 'active'
              AND ds.status = 'scheduled'
              AND ds.scheduled_at >= NOW()
            GROUP BY ds.id, ds.title, ds.scheduled_at, ds.duration_minutes, ds.location_or_link,
                     ds.max_attendees, c.title, u.name, my_b.booking_status
            ORDER BY ds.scheduled_at ASC";
    $stmt = mysqli_prepare($studentConn, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $studentId, $studentId);
    return studentFetchAll($stmt);
}

function studentBookDoubtSession($sessionId, $studentId)
{
    global $studentConn;

    $sql = "SELECT ds.id, ds.max_attendees,
                   COUNT(CASE WHEN b.booking_status = 'booked' THEN 1 END) AS booked_count
            FROM doubt_sessions ds
            INNER JOIN enrollments e ON e.course_id = ds.course_id
            LEFT JOIN doubt_session_bookings b ON b.doubt_session_id = ds.id
            WHERE ds.id = ?
              AND e.student_id = ?
              AND e.status = 'active'
              AND ds.status = 'scheduled'
              AND ds.scheduled_at >= NOW()
            GROUP BY ds.id, ds.max_attendees
            LIMIT 1";
    $stmt = mysqli_prepare($studentConn, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $sessionId, $studentId);
    $session = studentFetchOne($stmt);

    if (!$session) {
        return ['success' => false, 'message' => 'Doubt session was not found or is not available.'];
    }

    $sql = "SELECT id, booking_status
            FROM doubt_session_bookings
            WHERE doubt_session_id = ? AND student_id = ?
            LIMIT 1";
    $stmt = mysqli_prepare($studentConn, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $sessionId, $studentId);
    $existing = studentFetchOne($stmt);

    if ($existing && $existing['booking_status'] === 'booked') {
        return ['success' => false, 'message' => 'You already booked this doubt session.'];
    }

    if ((int)$session['booked_count'] >= (int)$session['max_attendees']) {
        return ['success' => false, 'message' => 'This doubt session is already full.'];
    }

    if ($existing && $existing['booking_status'] === 'cancelled') {
        $sql = "UPDATE doubt_session_bookings
                SET booking_status = 'booked', booked_at = NOW()
                WHERE id = ?";
        $stmt = mysqli_prepare($studentConn, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $existing['id']);
        $done = mysqli_stmt_execute($stmt);
    } else {
        $sql = "INSERT INTO doubt_session_bookings (doubt_session_id, student_id, booking_status, booked_at)
                VALUES (?, ?, 'booked', NOW())";
        $stmt = mysqli_prepare($studentConn, $sql);
        mysqli_stmt_bind_param($stmt, 'ii', $sessionId, $studentId);
        $done = mysqli_stmt_execute($stmt);
    }

    return [
        'success' => $done,
        'message' => $done ? 'Doubt session booked successfully.' : 'Booking could not be saved.'
    ];
}

function studentGetMyBookings($studentId)
{
    global $studentConn;

    $sql = "SELECT b.id, b.booking_status, b.booked_at,
                   ds.title, ds.scheduled_at, ds.duration_minutes, ds.location_or_link, ds.status AS session_status,
                   c.title AS course_title, u.name AS ta_name
            FROM doubt_session_bookings b
            INNER JOIN doubt_sessions ds ON ds.id = b.doubt_session_id
            INNER JOIN courses c ON c.id = ds.course_id
            INNER JOIN users u ON u.id = ds.ta_id
            WHERE b.student_id = ?
            ORDER BY ds.scheduled_at DESC";
    $stmt = mysqli_prepare($studentConn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $studentId);
    return studentFetchAll($stmt);
}
?>
