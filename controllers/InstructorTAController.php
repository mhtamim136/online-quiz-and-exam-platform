<?php
function instructorTAController()
{
    $courseId = instructorInt('course_id');
    $course = instructorCourseFind($courseId, instructorCurrentId());
    if (!$course) {
        instructorRedirect('instructor.php?action=courses');
    }
    $errors = array();
    if (instructorIsPost()) {
        $taId = (int) instructorPost('ta_id');
        if ($taId <= 0) {
            $errors[] = 'Select a TA.';
        } else {
            instructorTAAssign($courseId, instructorCurrentId(), $taId);
            instructorRedirect('instructor.php?action=assign_ta&course_id=' . $courseId);
        }
    }
    $tas = instructorTAUsers();
    $assigned = instructorTAAssigned($courseId, instructorCurrentId());
    instructorRender('instructor_assign_ta', array('pageTitle' => 'Assign TA', 'course' => $course, 'tas' => $tas, 'assigned' => $assigned, 'errors' => $errors));
}
