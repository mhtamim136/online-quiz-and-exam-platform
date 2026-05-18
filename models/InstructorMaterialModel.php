<?php
function instructorMaterialAll($courseId, $instructorId)
{
    $sql = 'SELECT m.*
            FROM course_materials m
            INNER JOIN courses c ON c.id = m.course_id
            WHERE m.course_id = ? AND c.instructor_id = ?
            ORDER BY m.created_at DESC';
    return instructorDbFetchAll($sql, 'ii', array($courseId, $instructorId));
}

function instructorMaterialFind($materialId, $instructorId)
{
    $sql = 'SELECT m.*
            FROM course_materials m
            INNER JOIN courses c ON c.id = m.course_id
            WHERE m.id = ? AND c.instructor_id = ?
            LIMIT 1';
    return instructorDbFetchOne($sql, 'ii', array($materialId, $instructorId));
}

function instructorMaterialCreate($courseId, $instructorId, $title, $materialType, $fileName)
{
    $course = instructorCourseFind($courseId, $instructorId);
    if (!$course) {
        return 0;
    }
    instructorDbExecute('INSERT INTO course_materials (course_id, uploaded_by, title, file_path, material_type, created_at) VALUES (?, ?, ?, ?, ?, NOW())', 'iisss', array($courseId, $instructorId, $title, $fileName, $materialType));
    return instructorDbLastId();
}

function instructorMaterialUpdate($materialId, $instructorId, $title, $materialType, $fileName)
{
    if ($fileName !== '') {
        return instructorDbExecute('UPDATE course_materials m INNER JOIN courses c ON c.id = m.course_id SET m.title = ?, m.material_type = ?, m.file_path = ? WHERE m.id = ? AND c.instructor_id = ?', 'sssii', array($title, $materialType, $fileName, $materialId, $instructorId));
    }
    return instructorDbExecute('UPDATE course_materials m INNER JOIN courses c ON c.id = m.course_id SET m.title = ?, m.material_type = ? WHERE m.id = ? AND c.instructor_id = ?', 'ssii', array($title, $materialType, $materialId, $instructorId));
}
