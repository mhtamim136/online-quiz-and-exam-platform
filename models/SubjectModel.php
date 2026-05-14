<?php
function subjectGetAll($conn)
{
    $sql = "SELECT subjects.id, subjects.name, subjects.description, subjects.created_at,
                   COUNT(courses.id) AS course_count
            FROM subjects
            LEFT JOIN courses ON courses.subject_id = subjects.id
            GROUP BY subjects.id, subjects.name, subjects.description, subjects.created_at
            ORDER BY subjects.name ASC";
    return dbSelectAll($conn, $sql);
}

function subjectFindById($conn, $id)
{
    $sql = "SELECT * FROM subjects WHERE id = ? LIMIT 1";
    return dbSelectOne($conn, $sql, 'i', array((int)$id));
}

function subjectNameExists($conn, $name, $exclude_id = 0)
{
    $sql = "SELECT id FROM subjects WHERE name = ? AND id <> ? LIMIT 1";
    $row = dbSelectOne($conn, $sql, 'si', array($name, (int)$exclude_id));
    return $row !== null;
}

function subjectCreate($conn, $name, $description)
{
    $sql = "INSERT INTO subjects (name, description) VALUES (?, ?)";
    return dbExecute($conn, $sql, 'ss', array($name, $description));
}

function subjectUpdate($conn, $id, $name, $description)
{
    $sql = "UPDATE subjects SET name = ?, description = ? WHERE id = ?";
    return dbExecute($conn, $sql, 'ssi', array($name, $description, (int)$id));
}

function subjectCourseCount($conn, $id)
{
    $sql = "SELECT COUNT(*) AS total FROM courses WHERE subject_id = ?";
    $row = dbSelectOne($conn, $sql, 'i', array((int)$id));
    return (int)$row['total'];
}

function subjectDelete($conn, $id)
{
    $sql = "DELETE FROM subjects WHERE id = ?";
    return dbExecute($conn, $sql, 'i', array((int)$id));
}
