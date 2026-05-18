<?php
function instructorTAUsers()
{
    return instructorDbFetchAll('SELECT id, name, email FROM users WHERE role = ? AND is_active = 1 ORDER BY name ASC', 's', array('ta'));
}

function instructorTAAssigned($courseId, $instructorId)
{
    $sql = 'SELECT ct.id, u.name, u.email
            FROM course_tas ct
            INNER JOIN users u ON u.id = ct.ta_id
            INNER JOIN courses c ON c.id = ct.course_id
            WHERE ct.course_id = ? AND c.instructor_id = ?
            ORDER BY u.name ASC';
    return instructorDbFetchAll($sql, 'ii', array($courseId, $instructorId));
}

function instructorTAAssign($courseId, $instructorId, $taId)
{
    $course = instructorCourseFind($courseId, $instructorId);
    if (!$course) {
        return 0;
    }
    $sql = 'INSERT IGNORE INTO course_tas (course_id, ta_id, assigned_at) VALUES (?, ?, NOW())';
    return instructorDbExecute($sql, 'ii', array($courseId, $taId));
}
