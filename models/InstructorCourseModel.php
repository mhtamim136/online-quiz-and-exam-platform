<?php
function instructorCourseAll($instructorId, $archived)
{
    if ($archived) {
        $sql = 'SELECT c.*, s.name AS subject_name
                FROM courses c
                LEFT JOIN subjects s ON s.id = c.subject_id
                WHERE c.instructor_id = ? AND c.status = ?
                ORDER BY c.created_at DESC';
        return instructorDbFetchAll($sql, 'is', array($instructorId, 'archived'));
    }

    $sql = 'SELECT c.*, s.name AS subject_name
            FROM courses c
            LEFT JOIN subjects s ON s.id = c.subject_id
            WHERE c.instructor_id = ? AND c.status <> ?
            ORDER BY c.created_at DESC';
    return instructorDbFetchAll($sql, 'is', array($instructorId, 'archived'));
}

function instructorCourseFind($courseId, $instructorId)
{
    $sql = 'SELECT c.*, s.name AS subject_name
            FROM courses c
            LEFT JOIN subjects s ON s.id = c.subject_id
            WHERE c.id = ? AND c.instructor_id = ?
            LIMIT 1';
    return instructorDbFetchOne($sql, 'ii', array($courseId, $instructorId));
}

function instructorSubjectAll()
{
    return instructorDbFetchAll('SELECT id, name FROM subjects ORDER BY name ASC');
}

function instructorCourseCreate($instructorId, $subjectId, $title, $enrollmentType, $maxStudents, $description)
{
    $sql = 'INSERT INTO courses (subject_id, instructor_id, title, description, enrollment_type, max_students, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())';
    instructorDbExecute($sql, 'iisssis', array($subjectId, $instructorId, $title, $description, $enrollmentType, $maxStudents, 'active'));
    return instructorDbLastId();
}

function instructorCourseUpdate($courseId, $instructorId, $subjectId, $title, $enrollmentType, $maxStudents, $description, $status)
{
    $sql = 'UPDATE courses SET subject_id = ?, title = ?, description = ?, enrollment_type = ?, max_students = ?, status = ? WHERE id = ? AND instructor_id = ?';
    return instructorDbExecute($sql, 'isssisii', array($subjectId, $title, $description, $enrollmentType, $maxStudents, $status, $courseId, $instructorId));
}

function instructorCourseSmallList($instructorId)
{
    return instructorDbFetchAll('SELECT id, title, status FROM courses WHERE instructor_id = ? AND status <> ? ORDER BY title ASC', 'is', array($instructorId, 'archived'));
}
