<?php
function instructorEnrolledStudentsController()
{
    $courseId = instructorInt('course_id');
    $course = instructorCourseFind($courseId, instructorCurrentId());
    if (!$course) {
        instructorRedirect('instructor.php?action=courses');
    }
    $students = instructorEnrollmentStudents($courseId, instructorCurrentId());
    instructorRender('instructor_enrolled_students', array('pageTitle' => 'Enrolled Students', 'course' => $course, 'students' => $students));
}

function instructorEnrollmentRequestsController()
{
    $courseId = instructorInt('course_id');
    $course = instructorCourseFind($courseId, instructorCurrentId());
    if (!$course) {
        instructorRedirect('instructor.php?action=courses');
    }
    $requests = instructorEnrollmentRequests($courseId, instructorCurrentId());
    instructorRender('instructor_enrollment_requests', array('pageTitle' => 'Enrollment Requests', 'course' => $course, 'requests' => $requests));
}
