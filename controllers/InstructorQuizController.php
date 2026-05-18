<?php
function instructorQuizListController()
{
    $courseId = instructorInt('course_id');
    $course = instructorCourseFind($courseId, instructorCurrentId());
    if (!$course) {
        instructorRedirect('instructor.php?action=courses');
    }
    $quizzes = instructorQuizAllByCourse($courseId, instructorCurrentId());
    instructorRender('instructor_quiz_list', array('pageTitle' => 'Quizzes', 'course' => $course, 'quizzes' => $quizzes));
}

function instructorQuizFormData()
{
    return array(
        'title' => instructorPost('title'),
        'description' => instructorPost('description'),
        'quiz_type' => instructorPost('quiz_type') === 'practice' ? 'practice' : 'graded',
        'time_limit_minutes' => (int) instructorPost('time_limit_minutes'),
        'total_marks' => (float) instructorPost('total_marks'),
        'pass_mark' => (float) instructorPost('pass_mark'),
        'available_from' => instructorPost('available_from'),
        'available_until' => instructorPost('available_until')
    );
}

function instructorQuizValidate($quiz)
{
    $errors = array();
    if ($quiz['title'] === '') {
        $errors[] = 'Quiz title is required.';
    }
    if ($quiz['time_limit_minutes'] <= 0) {
        $errors[] = 'Time limit must be greater than zero.';
    }
    if ($quiz['total_marks'] < 0) {
        $errors[] = 'Total marks cannot be negative.';
    }
    if ($quiz['pass_mark'] < 0) {
        $errors[] = 'Pass mark cannot be negative.';
    }
    return $errors;
}

function instructorQuizCreateController()
{
    $courseId = instructorInt('course_id');
    $course = instructorCourseFind($courseId, instructorCurrentId());
    if (!$course) {
        instructorRedirect('instructor.php?action=courses');
    }
    $errors = array();
    $quiz = array('title' => '', 'description' => '', 'quiz_type' => 'graded', 'time_limit_minutes' => '', 'total_marks' => '', 'pass_mark' => '', 'available_from' => '', 'available_until' => '');
    if (instructorIsPost()) {
        $quiz = instructorQuizFormData();
        $errors = instructorQuizValidate($quiz);
        if (count($errors) === 0) {
            $quizId = instructorQuizCreate($courseId, instructorCurrentId(), $quiz['title'], $quiz['description'], $quiz['quiz_type'], $quiz['time_limit_minutes'], $quiz['total_marks'], $quiz['pass_mark'], $quiz['available_from'], $quiz['available_until']);
            instructorRedirect('instructor.php?action=quiz_questions&quiz_id=' . $quizId);
        }
    }
    instructorRender('instructor_create_quiz', array('pageTitle' => 'Create Quiz', 'course' => $course, 'quiz' => $quiz, 'errors' => $errors));
}

function instructorQuizEditController()
{
    $quizId = instructorInt('id');
    $quiz = instructorQuizFind($quizId, instructorCurrentId());
    if (!$quiz) {
        instructorRedirect('instructor.php?action=courses');
    }
    $errors = array();
    if (instructorIsPost()) {
        $quiz = array_merge($quiz, instructorQuizFormData());
        $errors = instructorQuizValidate($quiz);
        if (count($errors) === 0) {
            instructorQuizUpdate($quizId, instructorCurrentId(), $quiz['title'], $quiz['description'], $quiz['quiz_type'], $quiz['time_limit_minutes'], $quiz['total_marks'], $quiz['pass_mark'], $quiz['available_from'], $quiz['available_until']);
            instructorRedirect('instructor.php?action=quiz_list&course_id=' . $quiz['course_id']);
        }
    }
    instructorRender('instructor_edit_quiz', array('pageTitle' => 'Edit Quiz', 'quiz' => $quiz, 'errors' => $errors));
}

function instructorQuizToggleController()
{
    $quizId = instructorInt('id');
    $quiz = instructorQuizFind($quizId, instructorCurrentId());
    if ($quiz) {
        instructorQuizTogglePublished($quizId, instructorCurrentId());
        instructorRedirect('instructor.php?action=quiz_list&course_id=' . $quiz['course_id']);
    }
    instructorRedirect('instructor.php?action=courses');
}
