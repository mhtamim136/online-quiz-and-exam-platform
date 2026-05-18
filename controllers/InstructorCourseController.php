<?php
function instructorCourseListController()
{
    $courses = instructorCourseAll(instructorCurrentId(), 0);
    instructorRender('instructor_courses', array('pageTitle' => 'Courses', 'courses' => $courses));
}

function instructorArchivedCoursesController()
{
    $courses = instructorCourseAll(instructorCurrentId(), 1);
    instructorRender('instructor_archived_courses', array('pageTitle' => 'Archived Courses', 'courses' => $courses));
}

function instructorCourseFormData()
{
    return array(
        'subject_id' => (int) instructorPost('subject_id'),
        'title' => instructorPost('title'),
        'enrollment_type' => instructorPost('enrollment_type') === 'approval' ? 'approval' : 'open',
        'max_students' => (int) instructorPost('max_students'),
        'description' => instructorPost('description'),
        'status' => in_array(instructorPost('status'), array('draft', 'active', 'archived'), true) ? instructorPost('status') : 'active'
    );
}

function instructorCourseValidate($data)
{
    $errors = array();
    if ($data['subject_id'] <= 0) {
        $errors[] = 'Subject is required.';
    }
    if ($data['title'] === '') {
        $errors[] = 'Course title is required.';
    }
    if ($data['max_students'] <= 0) {
        $errors[] = 'Maximum students must be greater than zero.';
    }
    return $errors;
}

function instructorCourseCreateController()
{
    $errors = array();
    $subjects = instructorSubjectAll();
    $course = array('subject_id' => '', 'title' => '', 'enrollment_type' => 'open', 'max_students' => 50, 'description' => '', 'status' => 'active');

    if (instructorIsPost()) {
        $course = instructorCourseFormData();
        $errors = instructorCourseValidate($course);
        if (count($errors) === 0) {
            $courseId = instructorCourseCreate(instructorCurrentId(), $course['subject_id'], $course['title'], $course['enrollment_type'], $course['max_students'], $course['description']);
            instructorRedirect('instructor.php?action=course_detail&id=' . $courseId);
        }
    }

    instructorRender('instructor_create_course', array('pageTitle' => 'Create Course', 'subjects' => $subjects, 'course' => $course, 'errors' => $errors));
}

function instructorCourseEditController()
{
    $courseId = instructorInt('id');
    $course = instructorCourseFind($courseId, instructorCurrentId());
    if (!$course) {
        instructorRedirect('instructor.php?action=courses');
    }

    $errors = array();
    $subjects = instructorSubjectAll();
    if (instructorIsPost()) {
        $course = array_merge($course, instructorCourseFormData());
        $errors = instructorCourseValidate($course);
        if (count($errors) === 0) {
            instructorCourseUpdate($courseId, instructorCurrentId(), $course['subject_id'], $course['title'], $course['enrollment_type'], $course['max_students'], $course['description'], $course['status']);
            instructorRedirect('instructor.php?action=course_detail&id=' . $courseId);
        }
    }

    instructorRender('instructor_edit_course', array('pageTitle' => 'Edit Course', 'subjects' => $subjects, 'course' => $course, 'errors' => $errors));
}

function instructorCourseDetailController()
{
    $course = instructorCourseFind(instructorInt('id'), instructorCurrentId());
    if (!$course) {
        instructorRedirect('instructor.php?action=courses');
    }
    instructorRender('instructor_course_detail', array('pageTitle' => 'Course Detail', 'course' => $course));
}
