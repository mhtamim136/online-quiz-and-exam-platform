<?php
function instructorEnrollmentRequests($courseId, $instructorId)
{
    $sql = 'SELECT e.id, e.status, e.enrolled_at, u.id AS student_id, u.name AS student_name, u.email AS student_email
            FROM enrollments e
            INNER JOIN users u ON u.id = e.student_id
            INNER JOIN courses c ON c.id = e.course_id
            WHERE e.course_id = ? AND c.instructor_id = ? AND e.status = ?
            ORDER BY e.enrolled_at DESC';
    return instructorDbFetchAll($sql, 'iis', array($courseId, $instructorId, 'pending'));
}

function instructorEnrollmentStudents($courseId, $instructorId)
{
    $sql = 'SELECT e.id, e.status, e.enrolled_at, u.name AS student_name, u.email AS student_email
            FROM enrollments e
            INNER JOIN users u ON u.id = e.student_id
            INNER JOIN courses c ON c.id = e.course_id
            WHERE e.course_id = ? AND c.instructor_id = ? AND e.status = ?
            ORDER BY u.name ASC';
    return instructorDbFetchAll($sql, 'iis', array($courseId, $instructorId, 'active'));
}

function instructorEnrollmentUpdateStatus($enrollmentId, $instructorId, $status)
{
    $sql = 'UPDATE enrollments e
            INNER JOIN courses c ON c.id = e.course_id
            SET e.status = ?
            WHERE e.id = ? AND c.instructor_id = ?';
    return instructorDbExecute($sql, 'sii', array($status, $enrollmentId, $instructorId));
}
