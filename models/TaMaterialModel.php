<?php
function taGetCourseMaterials($conn, $courseId, $taId)
{
    $sql = "SELECT course_materials.*, users.name AS uploaded_by_name
            FROM course_materials
            INNER JOIN users ON users.id = course_materials.uploaded_by
            INNER JOIN course_tas ON course_tas.course_id = course_materials.course_id
            WHERE course_materials.course_id = ? AND course_tas.ta_id = ?
            ORDER BY course_materials.created_at DESC";
    return dbSelectAll($conn, $sql, 'ii', array((int)$courseId, (int)$taId));
}

function taGetOwnMaterial($conn, $materialId, $taId, $courseId = 0)
{
    $sql = "SELECT * FROM course_materials WHERE id = ? AND uploaded_by = ?";
    $types = 'ii';
    $params = array((int)$materialId, (int)$taId);

    if ($courseId > 0) {
        $sql .= " AND course_id = ?";
        $types .= 'i';
        $params[] = (int)$courseId;
    }

    $sql .= " LIMIT 1";
    return dbSelectOne($conn, $sql, $types, $params);
}

function taCreateMaterial($conn, $data)
{
    $sql = "INSERT INTO course_materials (course_id, uploaded_by, title, file_path, material_type)
            VALUES (?, ?, ?, ?, ?)";
    return dbExecute($conn, $sql, 'iisss', array((int)$data['course_id'], (int)$data['uploaded_by'], $data['title'], $data['file_path'], $data['material_type']));
}

function taUpdateMaterial($conn, $materialId, $taId, $data)
{
    $sql = "UPDATE course_materials
            SET title = ?, file_path = ?, material_type = ?
            WHERE id = ? AND uploaded_by = ? AND course_id = ?";
    return dbExecute(
        $conn,
        $sql,
        'sssiii',
        array($data['title'], $data['file_path'], $data['material_type'], (int)$materialId, (int)$taId, (int)$data['course_id'])
    );
}

function taDeleteMaterial($conn, $materialId, $taId, $courseId)
{
    $sql = "DELETE FROM course_materials WHERE id = ? AND uploaded_by = ? AND course_id = ?";
    return dbExecute($conn, $sql, 'iii', array((int)$materialId, (int)$taId, (int)$courseId));
}
