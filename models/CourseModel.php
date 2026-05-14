<?php
function courseGetAll($conn, $subject_id, $instructor_id, $status)
{
    $sql = "SELECT courses.*, subjects.name AS subject_name, users.name AS instructor_name,
                   (SELECT COUNT(*) FROM enrollments WHERE enrollments.course_id = courses.id) AS enrolled_count
            FROM courses
            INNER JOIN subjects ON subjects.id = courses.subject_id
            INNER JOIN users ON users.id = courses.instructor_id";
    $where = array();
    $types = '';
    $params = array();

    if ($subject_id > 0) {
        $where[] = "courses.subject_id = ?";
        $types .= 'i';
        $params[] = (int)$subject_id;
    }

    if ($instructor_id > 0) {
        $where[] = "courses.instructor_id = ?";
        $types .= 'i';
        $params[] = (int)$instructor_id;
    }

    if ($status !== '') {
        $where[] = "courses.status = ?";
        $types .= 's';
        $params[] = $status;
    }

    if (count($where) > 0) {
        $sql .= ' WHERE ' . implode(' AND ', $where);
    }

    $sql .= " ORDER BY courses.created_at DESC, courses.id DESC";

    return dbSelectAll($conn, $sql, $types, $params);
}

function courseGetFilterSubjects($conn)
{
    return dbSelectAll($conn, "SELECT id, name FROM subjects ORDER BY name ASC");
}

function courseGetFilterInstructors($conn)
{
    return dbSelectAll($conn, "SELECT id, name, email FROM users WHERE role = 'instructor' ORDER BY name ASC");
}

function courseGetSimpleList($conn)
{
    return dbSelectAll($conn, "SELECT id, title FROM courses ORDER BY title ASC");
}
