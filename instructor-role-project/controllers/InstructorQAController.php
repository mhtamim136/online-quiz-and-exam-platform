<?php
function instructorQABoardController()
{
    $courseId = instructorInt('course_id');
    $course = instructorCourseFind($courseId, instructorCurrentId());
    if (!$course) {
        instructorRedirect('instructor.php?action=courses');
    }
    $questions = instructorQAQuestions($courseId, instructorCurrentId());
    instructorRender('instructor_qa_board', array('pageTitle' => 'Q&A Board', 'course' => $course, 'questions' => $questions));
}

function instructorQADetailController()
{
    $questionId = instructorInt('id');
    $question = instructorQAQuestionFind($questionId, instructorCurrentId());
    if (!$question) {
        instructorRedirect('instructor.php?action=courses');
    }
    $errors = array();
    if (instructorIsPost()) {
        $answer = instructorPost('answer');
        if ($answer === '') {
            $errors[] = 'Answer is required.';
        } else {
            instructorQAAnswerCreate($questionId, instructorCurrentId(), $answer);
            instructorRedirect('instructor.php?action=qa_detail&id=' . $questionId);
        }
    }
    $answers = instructorQAAnswers($questionId);
    instructorRender('instructor_qa_detail', array('pageTitle' => 'Q&A Detail', 'question' => $question, 'answers' => $answers, 'errors' => $errors));
}
