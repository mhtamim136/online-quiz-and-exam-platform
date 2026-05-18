<?php
function instructorMaterialListController()
{
    $courseId = instructorInt('course_id');
    $course = instructorCourseFind($courseId, instructorCurrentId());
    if (!$course) {
        instructorRedirect('instructor.php?action=courses');
    }
    $materials = instructorMaterialAll($courseId, instructorCurrentId());
    instructorRender('instructor_materials', array('pageTitle' => 'Materials', 'course' => $course, 'materials' => $materials));
}

function instructorMaterialSave($courseId, $materialId)
{
    $errors = array();
    $title = instructorPost('title');
    $materialType = in_array(instructorPost('material_type'), array('document', 'link', 'video'), true) ? instructorPost('material_type') : 'document';
    if ($title === '') {
        $errors[] = 'Title is required.';
    }
    $fileName = instructorUploadFile('material_file', __DIR__ . '/../uploads/instructor_course_materials');
    if ($materialId === 0 && $fileName === '') {
        $errors[] = 'Material file is required.';
    }
    if (count($errors) === 0) {
        if ($materialId > 0) {
            instructorMaterialUpdate($materialId, instructorCurrentId(), $title, $materialType, $fileName);
        } else {
            instructorMaterialCreate($courseId, instructorCurrentId(), $title, $materialType, $fileName);
        }
    }
    return array($errors, array('title' => $title, 'material_type' => $materialType));
}

function instructorMaterialUploadController()
{
    $courseId = instructorInt('course_id');
    $course = instructorCourseFind($courseId, instructorCurrentId());
    if (!$course) {
        instructorRedirect('instructor.php?action=courses');
    }
    $errors = array();
    $material = array('title' => '', 'material_type' => 'document');
    if (instructorIsPost()) {
        list($errors, $material) = instructorMaterialSave($courseId, 0);
        if (count($errors) === 0) {
            instructorRedirect('instructor.php?action=materials&course_id=' . $courseId);
        }
    }
    instructorRender('instructor_upload_material', array('pageTitle' => 'Upload Material', 'course' => $course, 'material' => $material, 'errors' => $errors));
}

function instructorMaterialEditController()
{
    $materialId = instructorInt('id');
    $material = instructorMaterialFind($materialId, instructorCurrentId());
    if (!$material) {
        instructorRedirect('instructor.php?action=courses');
    }
    $errors = array();
    if (instructorIsPost()) {
        $courseId = (int) $material['course_id'];
        list($errors, $material) = instructorMaterialSave($courseId, $materialId);
        $material['course_id'] = $courseId;
        if (count($errors) === 0) {
            instructorRedirect('instructor.php?action=materials&course_id=' . $courseId);
        }
    }
    instructorRender('instructor_edit_material', array('pageTitle' => 'Edit Material', 'material' => $material, 'errors' => $errors));
}
