<?php
function instructorAnnouncementAll($courseId, $instructorId)
{
    $sql = 'SELECT a.*
            FROM announcements a
            INNER JOIN courses c ON c.id = a.course_id
            WHERE a.course_id = ? AND c.instructor_id = ?
            ORDER BY a.created_at DESC';
    return instructorDbFetchAll($sql, 'ii', array($courseId, $instructorId));
}

function instructorAnnouncementFind($announcementId, $instructorId)
{
    $sql = 'SELECT a.*
            FROM announcements a
            INNER JOIN courses c ON c.id = a.course_id
            WHERE a.id = ? AND c.instructor_id = ?
            LIMIT 1';
    return instructorDbFetchOne($sql, 'ii', array($announcementId, $instructorId));
}

function instructorAnnouncementCreate($courseId, $instructorId, $title, $body, $attachment)
{
    $course = instructorCourseFind($courseId, $instructorId);
    if (!$course) {
        return 0;
    }
    instructorDbExecute('INSERT INTO announcements (course_id, author_id, title, body, created_at) VALUES (?, ?, ?, ?, NOW())', 'iiss', array($courseId, $instructorId, $title, $body));
    return instructorDbLastId();
}

function instructorAnnouncementUpdate($announcementId, $instructorId, $title, $body, $attachment)
{
    if ($attachment !== '') {
        return instructorDbExecute('UPDATE announcements a INNER JOIN courses c ON c.id = a.course_id SET a.title = ?, a.body = ? WHERE a.id = ? AND c.instructor_id = ?', 'ssii', array($title, $body, $announcementId, $instructorId));
    }
    return instructorDbExecute('UPDATE announcements a INNER JOIN courses c ON c.id = a.course_id SET a.title = ?, a.body = ? WHERE a.id = ? AND c.instructor_id = ?', 'ssii', array($title, $body, $announcementId, $instructorId));
}
