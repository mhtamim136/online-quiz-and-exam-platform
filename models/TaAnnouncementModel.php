<?php
function taGetCourseAnnouncements($conn, $courseId, $taId)
{
    $sql = "SELECT announcements.*, users.name AS author_name
            FROM announcements
            INNER JOIN users ON users.id = announcements.author_id
            INNER JOIN course_tas ON course_tas.course_id = announcements.course_id
            WHERE announcements.course_id = ? AND course_tas.ta_id = ?
            ORDER BY announcements.created_at DESC";
    return dbSelectAll($conn, $sql, 'ii', array((int)$courseId, (int)$taId));
}

function taGetOwnAnnouncement($conn, $announcementId, $taId, $courseId = 0)
{
    $sql = "SELECT * FROM announcements WHERE id = ? AND author_id = ?";
    $types = 'ii';
    $params = array((int)$announcementId, (int)$taId);

    if ($courseId > 0) {
        $sql .= " AND course_id = ?";
        $types .= 'i';
        $params[] = (int)$courseId;
    }

    $sql .= " LIMIT 1";
    return dbSelectOne($conn, $sql, $types, $params);
}

function taCreateAnnouncement($conn, $data)
{
    $sql = "INSERT INTO announcements (course_id, author_id, title, body)
            VALUES (?, ?, ?, ?)";
    return dbExecute($conn, $sql, 'iiss', array((int)$data['course_id'], (int)$data['author_id'], $data['title'], $data['body']));
}

function taUpdateAnnouncement($conn, $announcementId, $taId, $data)
{
    $sql = "UPDATE announcements
            SET title = ?, body = ?
            WHERE id = ? AND author_id = ? AND course_id = ?";
    return dbExecute(
        $conn,
        $sql,
        'ssiii',
        array($data['title'], $data['body'], (int)$announcementId, (int)$taId, (int)$data['course_id'])
    );
}

function taDeleteAnnouncement($conn, $announcementId, $taId, $courseId)
{
    $sql = "DELETE FROM announcements WHERE id = ? AND author_id = ? AND course_id = ?";
    return dbExecute($conn, $sql, 'iii', array((int)$announcementId, (int)$taId, (int)$courseId));
}
