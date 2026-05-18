<?php
function instructorQuestionFormData()
{
    return array(
        'question_text' => instructorPost('question_text'),
        'marks' => (float) instructorPost('marks'),
        'options' => array(instructorPost('option_1'), instructorPost('option_2'), instructorPost('option_3'), instructorPost('option_4')),
        'correct_index' => (int) instructorPost('correct_index')
    );
}

function instructorQuestionValidate($data)
{
    $errors = array();
    if ($data['question_text'] === '') {
        $errors[] = 'Question text is required.';
    }
    if ($data['marks'] <= 0) {
        $errors[] = 'Marks must be greater than zero.';
    }
    for ($i = 0; $i < 4; $i++) {
        if ($data['options'][$i] === '') {
            $errors[] = 'All four options are required.';
            break;
        }
    }
    if ($data['correct_index'] < 0 || $data['correct_index'] > 3) {
        $errors[] = 'Correct answer is required.';
    }
    return $errors;
}

function instructorQuizQuestionsController()
{
    $quizId = instructorInt('quiz_id');
    $quiz = instructorQuizFind($quizId, instructorCurrentId());
    if (!$quiz) {
        instructorRedirect('instructor.php?action=courses');
    }
    $questions = instructorQuestionByQuiz($quizId, instructorCurrentId());
    instructorRender('instructor_quiz_questions', array('pageTitle' => 'Quiz Questions', 'quiz' => $quiz, 'questions' => $questions));
}

function instructorQuestionAddController()
{
    $quizId = instructorInt('quiz_id');
    $quiz = instructorQuizFind($quizId, instructorCurrentId());
    if (!$quiz) {
        instructorRedirect('instructor.php?action=courses');
    }
    $errors = array();
    $question = array('question_text' => '', 'marks' => '', 'options' => array('', '', '', ''), 'correct_index' => 0);
    if (instructorIsPost()) {
        $question = instructorQuestionFormData();
        $errors = instructorQuestionValidate($question);
        if (count($errors) === 0) {
            instructorQuestionCreate($quizId, instructorCurrentId(), $question['question_text'], $question['marks'], $question['options'], $question['correct_index']);
            instructorRedirect('instructor.php?action=quiz_questions&quiz_id=' . $quizId);
        }
    }
    instructorRender('instructor_add_question', array('pageTitle' => 'Add Question', 'quiz' => $quiz, 'question' => $question, 'errors' => $errors));
}

function instructorQuestionEditController()
{
    $questionId = instructorInt('id');
    $existing = instructorQuestionFind($questionId, instructorCurrentId());
    if (!$existing) {
        instructorRedirect('instructor.php?action=courses');
    }
    $options = instructorQuestionOptions($questionId);
    $question = array('question_text' => $existing['question_text'], 'marks' => $existing['marks'], 'options' => array('', '', '', ''), 'correct_index' => 0);
    for ($i = 0; $i < 4; $i++) {
        if (isset($options[$i])) {
            $question['options'][$i] = $options[$i]['option_text'];
            if ((int) $options[$i]['is_correct'] === 1) {
                $question['correct_index'] = $i;
            }
        }
    }
    $errors = array();
    if (instructorIsPost()) {
        $question = instructorQuestionFormData();
        $errors = instructorQuestionValidate($question);
        if (count($errors) === 0) {
            instructorQuestionUpdate($questionId, instructorCurrentId(), $question['question_text'], $question['marks'], $question['options'], $question['correct_index']);
            instructorRedirect('instructor.php?action=quiz_questions&quiz_id=' . $existing['quiz_id']);
        }
    }
    instructorRender('instructor_edit_question', array('pageTitle' => 'Edit Question', 'question' => $question, 'existing' => $existing, 'errors' => $errors));
}

function instructorQuestionBankController()
{
    $courseId = instructorInt('course_id');
    $course = instructorCourseFind($courseId, instructorCurrentId());
    if (!$course) {
        instructorRedirect('instructor.php?action=courses');
    }
    $questions = instructorQuestionBankByCourse($courseId, instructorCurrentId());
    instructorRender('instructor_question_bank', array('pageTitle' => 'Question Bank', 'course' => $course, 'questions' => $questions));
}
