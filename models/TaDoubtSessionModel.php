<?php
function taGetDoubtSessions($conn, $taId, $courseId)
{
    $sql = "SELECT doubt_sessions.*, courses.title AS course_title,
                   COUNT(doubt_session_bookings.id) AS booked_count
            FROM doubt_sessions
            INNER JOIN courses ON courses.id = doubt_sessions.course_id
            LEFT JOIN doubt_session_bookings ON doubt_session_bookings.doubt_session_id = doubt_sessions.id
                AND doubt_session_bookings.booking_status <> 'cancelled'
            WHERE doubt_sessions.ta_id = ?";
    $types = 'i';
    $params = array((int)$taId);

    if ($courseId > 0) {
        $sql .= " AND doubt_sessions.course_id = ?";
        $types .= 'i';
        $params[] = (int)$courseId;
    }

    $sql .= " GROUP BY doubt_sessions.id, courses.title
              ORDER BY doubt_sessions.scheduled_at DESC";

    return dbSelectAll($conn, $sql, $types, $params);
}

function taGetDoubtSession($conn, $sessionId, $taId)
{
    $sql = "SELECT doubt_sessions.*, courses.title AS course_title
            FROM doubt_sessions
            INNER JOIN courses ON courses.id = doubt_sessions.course_id
            WHERE doubt_sessions.id = ? AND doubt_sessions.ta_id = ?
            LIMIT 1";
    return dbSelectOne($conn, $sql, 'ii', array((int)$sessionId, (int)$taId));
}

function taCreateDoubtSession($conn, $data)
{
    $sql = "INSERT INTO doubt_sessions
            (course_id, ta_id, title, scheduled_at, duration_minutes, location_or_link, max_attendees, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, 'scheduled')";
    return dbExecute(
        $conn,
        $sql,
        'iissisi',
        array(
            (int)$data['course_id'],
            (int)$data['ta_id'],
            $data['title'],
            $data['scheduled_at'],
            (int)$data['duration_minutes'],
            $data['location_or_link'],
            (int)$data['max_attendees']
        )
    );
}

function taUpdateDoubtSession($conn, $sessionId, $taId, $data)
{
    $sql = "UPDATE doubt_sessions
            SET title = ?, scheduled_at = ?, duration_minutes = ?, location_or_link = ?, max_attendees = ?
            WHERE id = ? AND ta_id = ?";
    return dbExecute(
        $conn,
        $sql,
        'ssisiii',
        array(
            $data['title'],
            $data['scheduled_at'],
            (int)$data['duration_minutes'],
            $data['location_or_link'],
            (int)$data['max_attendees'],
            (int)$sessionId,
            (int)$taId
        )
    );
}

function taCancelDoubtSession($conn, $sessionId, $taId)
{
    $sql = "UPDATE doubt_sessions SET status = 'cancelled' WHERE id = ? AND ta_id = ?";
    return dbExecute($conn, $sql, 'ii', array((int)$sessionId, (int)$taId));
}

function taGetDoubtSessionBookings($conn, $sessionId, $taId)
{
    $sql = "SELECT doubt_session_bookings.*, users.name AS student_name, users.email,
                   users.student_id, users.program
            FROM doubt_session_bookings
            INNER JOIN doubt_sessions ON doubt_sessions.id = doubt_session_bookings.doubt_session_id
            INNER JOIN users ON users.id = doubt_session_bookings.student_id
            WHERE doubt_session_bookings.doubt_session_id = ? AND doubt_sessions.ta_id = ?
            ORDER BY doubt_session_bookings.booked_at ASC";
    return dbSelectAll($conn, $sql, 'ii', array((int)$sessionId, (int)$taId));
}
