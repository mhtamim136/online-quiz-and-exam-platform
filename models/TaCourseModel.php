<?php
function taGetAssignedCourses($conn, $taId)
{
    $sql = "SELECT courses.*, subjects.name AS subject_name, users.name AS instructor_name,
                   COUNT(DISTINCT enrollments.id) AS enrollment_count
            FROM course_tas
            INNER JOIN courses ON courses.id = course_tas.course_id
            INNER JOIN subjects ON subjects.id = courses.subject_id
            INNER JOIN users ON users.id = courses.instructor_id
            LEFT JOIN enrollments ON enrollments.course_id = courses.id AND enrollments.status = 'active'
            WHERE course_tas.ta_id = ?
            GROUP BY courses.id, subjects.name, users.name
            ORDER BY courses.created_at DESC";
    return dbSelectAll($conn, $sql, 'i', array((int)$taId));
}

function taGetAssignedCourse($conn, $courseId, $taId)
{
    $sql = "SELECT courses.*, subjects.name AS subject_name, users.name AS instructor_name,
                   users.email AS instructor_email
            FROM courses
            INNER JOIN subjects ON subjects.id = courses.subject_id
            INNER JOIN users ON users.id = courses.instructor_id
            INNER JOIN course_tas ON course_tas.course_id = courses.id
            WHERE courses.id = ? AND course_tas.ta_id = ?
            LIMIT 1";
    return dbSelectOne($conn, $sql, 'ii', array((int)$courseId, (int)$taId));
}

function taGetCourseStudents($conn, $courseId)
{
    $sql = "SELECT users.id, users.name, users.email, users.student_id, users.program,
                   enrollments.status, enrollments.enrolled_at
            FROM enrollments
            INNER JOIN users ON users.id = enrollments.student_id
            WHERE enrollments.course_id = ?
            ORDER BY users.name ASC";
    return dbSelectAll($conn, $sql, 'i', array((int)$courseId));
}

function taGetCourseQuickStats($conn, $courseId, $taId)
{
    return array(
        'students' => dbSelectOne($conn, "SELECT COUNT(*) AS total FROM enrollments WHERE course_id = ? AND status = 'active'", 'i', array((int)$courseId)),
        'quizzes' => dbSelectOne($conn, "SELECT COUNT(*) AS total FROM quizzes WHERE course_id = ?", 'i', array((int)$courseId)),
        'materials' => dbSelectOne($conn, "SELECT COUNT(*) AS total FROM course_materials WHERE course_id = ?", 'i', array((int)$courseId)),
        'questions' => dbSelectOne($conn, "SELECT COUNT(*) AS total FROM qa_questions WHERE course_id = ?", 'i', array((int)$courseId)),
        'sessions' => dbSelectOne($conn, "SELECT COUNT(*) AS total FROM doubt_sessions WHERE course_id = ? AND ta_id = ?", 'ii', array((int)$courseId, (int)$taId))
    );
}
