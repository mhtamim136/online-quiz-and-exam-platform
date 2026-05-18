<?php
function instructorDashboardStats($instructorId)
{
    return array(
        'total_courses' => instructorDbFetchOne('SELECT COUNT(*) AS total FROM courses WHERE instructor_id = ? AND status <> ?', 'is', array($instructorId, 'archived')),
        'total_students' => instructorDbFetchOne('SELECT COUNT(DISTINCT e.student_id) AS total FROM enrollments e INNER JOIN courses c ON c.id = e.course_id WHERE c.instructor_id = ? AND e.status = ?', 'is', array($instructorId, 'active')),
        'active_quizzes' => instructorDbFetchOne('SELECT COUNT(*) AS total FROM quizzes q INNER JOIN courses c ON c.id = q.course_id WHERE c.instructor_id = ? AND q.status = ?', 'is', array($instructorId, 'published')),
        'pending_enrollments' => instructorDbFetchOne('SELECT COUNT(*) AS total FROM enrollments e INNER JOIN courses c ON c.id = e.course_id WHERE c.instructor_id = ? AND e.status = ?', 'is', array($instructorId, 'pending'))
    );
}

function instructorDashboardRecentAttempts($instructorId)
{
    $sql = 'SELECT a.id, a.score, a.completed_at, u.name AS student_name, q.title AS quiz_title
            FROM attempts a
            INNER JOIN quizzes q ON q.id = a.quiz_id
            INNER JOIN courses c ON c.id = q.course_id
            INNER JOIN users u ON u.id = a.student_id
            WHERE c.instructor_id = ?
            ORDER BY a.completed_at DESC
            LIMIT 8';
    return instructorDbFetchAll($sql, 'i', array($instructorId));
}
