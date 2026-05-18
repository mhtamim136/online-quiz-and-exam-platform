<?php
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/TaModel.php';
require_once __DIR__ . '/../models/TaCourseModel.php';
require_once __DIR__ . '/../models/TaQuizModel.php';
require_once __DIR__ . '/../models/TaQuestionModel.php';
require_once __DIR__ . '/../models/TaAttemptModel.php';
require_once __DIR__ . '/../models/TaRiskModel.php';
require_once __DIR__ . '/../models/TaMaterialModel.php';
require_once __DIR__ . '/../models/TaAnnouncementModel.php';
require_once __DIR__ . '/../models/TaQaModel.php';
require_once __DIR__ . '/../models/TaDoubtSessionModel.php';
require_once __DIR__ . '/../models/TaReportModel.php';

function taRoute($conn, $action)
{
    switch ($action) {
        case 'dashboard':
            taDashboard($conn);
            break;
        case 'profile':
            taProfile($conn);
            break;
        case 'update_profile':
            taUpdateProfileAction($conn);
            break;
        case 'change_password':
            taChangePasswordAction($conn);
            break;
        case 'assigned_courses':
            taAssignedCourses($conn);
            break;
        case 'course_detail':
            taCourseDetail($conn);
            break;
        case 'enrolled_students':
            taEnrolledStudents($conn);
            break;
        case 'quizzes':
            taQuizzes($conn);
            break;
        case 'create_practice_quiz':
            taPracticeQuizForm($conn, 0);
            break;
        case 'edit_practice_quiz':
            taPracticeQuizForm($conn, taInt('id'));
            break;
        case 'questions':
            taQuestions($conn);
            break;
        case 'add_question':
            taQuestionForm($conn, 0);
            break;
        case 'edit_question':
            taQuestionForm($conn, taInt('id'));
            break;
        case 'delete_question':
            taDeleteQuestionAction($conn);
            break;
        case 'question_bank':
            taQuestionBank($conn);
            break;
        case 'attempt_results':
            taAttemptResults($conn);
            break;
        case 'at_risk_students':
            taAtRiskStudents($conn);
            break;
        case 'announcements':
            taAnnouncements($conn);
            break;
        case 'delete_announcement':
            taDeleteAnnouncementAction($conn);
            break;
        case 'materials':
            taMaterials($conn);
            break;
        case 'delete_material':
            taDeleteMaterialAction($conn);
            break;
        case 'qa_board':
            taQaBoard($conn);
            break;
        case 'qa_detail':
            taQaDetail($conn);
            break;
        case 'endorse_answer':
            taEndorseAnswerAction($conn);
            break;
        case 'resolve_question':
            taResolveQuestionAction($conn);
            break;
        case 'doubt_sessions':
            taDoubtSessions($conn);
            break;
        case 'create_doubt_session':
            taDoubtSessionForm($conn, 0);
            break;
        case 'edit_doubt_session':
            taDoubtSessionForm($conn, taInt('id'));
            break;
        case 'cancel_doubt_session':
            taCancelDoubtSessionAction($conn);
            break;
        case 'doubt_session_bookings':
            taDoubtSessionBookings($conn);
            break;
        case 'course_summary_report':
            taCourseSummaryReport($conn);
            break;
        default:
            taSetFlash('error', 'The requested TA page was not found.');
            taRedirect('ta.php?action=dashboard');
    }
}

function taView($view, $data = array())
{
    extract($data);
    require __DIR__ . '/../views/ta/' . $view . '.php';
}

function taRequireCourseOrBack($conn, $courseId, $taId)
{
    if ($courseId <= 0 || !taCanAccessCourse($conn, $courseId, $taId)) {
        taSetFlash('error', 'You can only access courses assigned to you.');
        taRedirect('ta.php?action=assigned_courses');
    }
}

function taCleanDateTime($value)
{
    $value = trim($value);
    if ($value === '') {
        return null;
    }

    $time = strtotime($value);
    if ($time === false) {
        return false;
    }

    return date('Y-m-d H:i:s', $time);
}

function taDashboard($conn)
{
    $taId = taCurrentId();
    $pageTitle = 'TA Dashboard';
    $profile = taGetProfile($conn, $taId);
    $stats = taGetDashboardStats($conn, $taId);
    $recentQuestions = taGetRecentQuestions($conn, $taId);
    $upcomingSessions = taGetUpcomingDoubtSessions($conn, $taId);

    taView('dashboard', compact('pageTitle', 'profile', 'stats', 'recentQuestions', 'upcomingSessions'));
}

function taProfile($conn)
{
    $pageTitle = 'Profile';
    $profile = taGetProfile($conn, taCurrentId());
    taView('profile', compact('pageTitle', 'profile'));
}

function taUpdateProfileAction($conn)
{
    if (!taIsPost()) {
        taRedirect('ta.php?action=profile');
    }

    $taId = taCurrentId();
    $data = array(
        'name' => isset($_POST['name']) ? trim($_POST['name']) : '',
        'phone' => isset($_POST['phone']) ? trim($_POST['phone']) : '',
        'department' => isset($_POST['department']) ? trim($_POST['department']) : '',
        'bio' => isset($_POST['bio']) ? trim($_POST['bio']) : ''
    );

    if ($data['name'] === '') {
        taSetFlash('error', 'Name is required.');
        taRedirect('ta.php?action=profile');
    }

    taUpdateProfile($conn, $taId, $data);

    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] !== UPLOAD_ERR_NO_FILE) {
        $path = taUploadFile('profile_pic', 'uploads/profile_pics', array('jpg', 'jpeg', 'png', 'gif'));
        if ($path === false) {
            taSetFlash('error', 'Profile picture must be jpg, jpeg, png, or gif.');
            taRedirect('ta.php?action=profile');
        }
        taUpdateProfilePic($conn, $taId, $path);
    }

    $_SESSION['name'] = $data['name'];
    taSetFlash('success', 'Profile updated successfully.');
    taRedirect('ta.php?action=profile');
}

function taChangePasswordAction($conn)
{
    if (!taIsPost()) {
        taRedirect('ta.php?action=profile');
    }

    $taId = taCurrentId();
    $currentPassword = isset($_POST['current_password']) ? trim($_POST['current_password']) : '';
    $newPassword = isset($_POST['new_password']) ? trim($_POST['new_password']) : '';
    $confirmPassword = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';

    if ($currentPassword === '' || $newPassword === '' || $confirmPassword === '') {
        taSetFlash('error', 'All password fields are required.');
        taRedirect('ta.php?action=profile');
    }
    if (strlen($newPassword) < 4) {
        taSetFlash('error', 'New password must be at least 4 characters.');
        taRedirect('ta.php?action=profile');
    }
    if ($newPassword !== $confirmPassword) {
        taSetFlash('error', 'New password and confirm password must match.');
        taRedirect('ta.php?action=profile');
    }

    $user = userFindById($conn, $taId);
    if (!$user || !password_verify($currentPassword, $user['password_hash'])) {
        taSetFlash('error', 'Current password is incorrect.');
        taRedirect('ta.php?action=profile');
    }

    taUpdatePassword($conn, $taId, password_hash($newPassword, PASSWORD_DEFAULT));
    taSetFlash('success', 'Password changed successfully.');
    taRedirect('ta.php?action=profile');
}

function taAssignedCourses($conn)
{
    $pageTitle = 'Assigned Courses';
    $courses = taGetAssignedCourses($conn, taCurrentId());
    taView('assigned_courses', compact('pageTitle', 'courses'));
}

function taCourseDetail($conn)
{
    $taId = taCurrentId();
    $courseId = taInt('id');
    taRequireCourseOrBack($conn, $courseId, $taId);

    $pageTitle = 'Course Details';
    $course = taGetAssignedCourse($conn, $courseId, $taId);
    $stats = taGetCourseQuickStats($conn, $courseId, $taId);
    $students = taGetCourseStudents($conn, $courseId);
    $quizzes = taGetQuizzes($conn, $taId, $courseId, '', '');
    $materials = taGetCourseMaterials($conn, $courseId, $taId);
    $announcements = taGetCourseAnnouncements($conn, $courseId, $taId);
    $questions = taGetQaQuestions($conn, $courseId, $taId);
    $sessions = taGetDoubtSessions($conn, $taId, $courseId);

    taView('course_detail', compact('pageTitle', 'course', 'stats', 'students', 'quizzes', 'materials', 'announcements', 'questions', 'sessions'));
}

function taEnrolledStudents($conn)
{
    $taId = taCurrentId();
    $courseId = taInt('course_id');
    taRequireCourseOrBack($conn, $courseId, $taId);

    $course = taGetAssignedCourse($conn, $courseId, $taId);
    $pageTitle = 'Enrolled Students';
    $students = taGetCourseStudents($conn, $courseId);
    taView('enrolled_students', compact('pageTitle', 'course', 'students'));
}

function taQuizzes($conn)
{
    $taId = taCurrentId();
    $pageTitle = 'Quizzes';
    $courseId = taInt('course_id');
    $status = taClean('status');
    $quizType = taClean('quiz_type');

    if ($courseId > 0) {
        taRequireCourseOrBack($conn, $courseId, $taId);
    }
    if (!taAllowed($status, array('', 'draft', 'published'))) {
        $status = '';
    }
    if (!taAllowed($quizType, array('', 'graded', 'practice'))) {
        $quizType = '';
    }

    $courses = taGetAssignedCourses($conn, $taId);
    $quizzes = taGetQuizzes($conn, $taId, $courseId, $status, $quizType);
    taView('quizzes', compact('pageTitle', 'courses', 'quizzes', 'courseId', 'status', 'quizType'));
}

function taPracticeQuizForm($conn, $quizId)
{
    $taId = taCurrentId();
    $errors = array();
    $editing = $quizId > 0;
    $quiz = null;
    $courseId = taInt('course_id');

    if ($editing) {
        $quiz = taGetQuiz($conn, $quizId, $taId);
        if (!$quiz || (int)$quiz['created_by'] !== $taId || $quiz['quiz_type'] !== 'practice') {
            taSetFlash('error', 'You can edit only your own practice quizzes.');
            taRedirect('ta.php?action=quizzes');
        }
        $courseId = (int)$quiz['course_id'];
    }

    taRequireCourseOrBack($conn, $courseId, $taId);

    $old = $editing ? $quiz : array(
        'course_id' => $courseId,
        'title' => '',
        'description' => '',
        'time_limit_minutes' => '20',
        'total_marks' => '5',
        'pass_mark' => '0',
        'available_from' => '',
        'available_until' => ''
    );

    if (taIsPost()) {
        $old['title'] = isset($_POST['title']) ? trim($_POST['title']) : '';
        $old['description'] = isset($_POST['description']) ? trim($_POST['description']) : '';
        $old['time_limit_minutes'] = isset($_POST['time_limit_minutes']) ? trim($_POST['time_limit_minutes']) : '';
        $old['total_marks'] = isset($_POST['total_marks']) ? trim($_POST['total_marks']) : '';
        $old['pass_mark'] = isset($_POST['pass_mark']) ? trim($_POST['pass_mark']) : '';
        $old['available_from'] = isset($_POST['available_from']) ? trim($_POST['available_from']) : '';
        $old['available_until'] = isset($_POST['available_until']) ? trim($_POST['available_until']) : '';

        if ($old['title'] === '') {
            $errors[] = 'Quiz title is required.';
        }
        if (!is_numeric($old['time_limit_minutes']) || (int)$old['time_limit_minutes'] <= 0) {
            $errors[] = 'Time limit must be a positive number.';
        }
        if (!is_numeric($old['total_marks']) || (float)$old['total_marks'] <= 0) {
            $errors[] = 'Total marks must be a positive number.';
        }
        if (!is_numeric($old['pass_mark']) || (float)$old['pass_mark'] < 0) {
            $errors[] = 'Pass mark must be zero or a positive number.';
        }

        $availableFrom = taCleanDateTime($old['available_from']);
        $availableUntil = taCleanDateTime($old['available_until']);
        if ($availableFrom === false || $availableUntil === false) {
            $errors[] = 'Use a valid date and time format, for example 2026-06-01 09:00:00.';
        }

        if (count($errors) === 0) {
            $old['course_id'] = $courseId;
            $old['created_by'] = $taId;
            $old['available_from'] = $availableFrom;
            $old['available_until'] = $availableUntil;

            if ($editing) {
                taUpdatePracticeQuiz($conn, $quizId, $taId, $old);
                taSetFlash('success', 'Practice quiz updated.');
            } else {
                $quizId = taCreatePracticeQuiz($conn, $old);
                taSetFlash('success', 'Practice quiz created as draft.');
            }
            taRedirect('ta.php?action=questions&quiz_id=' . (int)$quizId);
        }
    }

    $course = taGetAssignedCourse($conn, $courseId, $taId);
    $pageTitle = $editing ? 'Edit Practice Quiz' : 'Create Practice Quiz';
    taView('practice_quiz_form', compact('pageTitle', 'course', 'quiz', 'old', 'errors', 'editing'));
}

function taQuestions($conn)
{
    $taId = taCurrentId();
    $quizId = taInt('quiz_id');
    if ($quizId <= 0 || !taCanAccessQuiz($conn, $quizId, $taId)) {
        taSetFlash('error', 'Quiz not found for your assigned courses.');
        taRedirect('ta.php?action=quizzes');
    }

    $pageTitle = 'Questions';
    $quiz = taGetQuiz($conn, $quizId, $taId);
    $questions = taGetQuizQuestions($conn, $quizId, $taId);
    taView('questions', compact('pageTitle', 'quiz', 'questions'));
}

function taQuestionForm($conn, $questionId)
{
    $taId = taCurrentId();
    $editing = $questionId > 0;
    $errors = array();
    $question = null;
    $quizId = taInt('quiz_id');

    if ($editing) {
        $question = taGetQuestion($conn, $questionId, $taId);
        if (!$question || (int)$question['created_by'] !== $taId) {
            taSetFlash('error', 'You can edit only questions created by you.');
            taRedirect('ta.php?action=quizzes');
        }
        $quizId = (int)$question['quiz_id'];
        $existingOptions = taGetQuestionOptions($conn, $questionId);
    } else {
        $existingOptions = array();
    }

    if ($quizId <= 0 || !taCanAccessQuiz($conn, $quizId, $taId)) {
        taSetFlash('error', 'Quiz not found for your assigned courses.');
        taRedirect('ta.php?action=quizzes');
    }

    $old = $editing ? array(
        'question_text' => $question['question_text'],
        'marks' => $question['marks'],
        'options' => taOptionsForForm($existingOptions)
    ) : array(
        'question_text' => '',
        'marks' => '1',
        'options' => array('', '', '', ''),
        'correct_option' => '0'
    );

    if ($editing) {
        $old['correct_option'] = taCorrectOptionIndex($existingOptions);
    }

    if (taIsPost()) {
        $old['question_text'] = isset($_POST['question_text']) ? trim($_POST['question_text']) : '';
        $old['marks'] = isset($_POST['marks']) ? trim($_POST['marks']) : '';
        $old['options'] = isset($_POST['options']) && is_array($_POST['options']) ? $_POST['options'] : array('', '', '', '');
        $old['correct_option'] = isset($_POST['correct_option']) ? trim($_POST['correct_option']) : '';

        $preparedOptions = taPrepareOptions($old['options'], $old['correct_option'], $errors);
        if ($old['question_text'] === '') {
            $errors[] = 'Question text is required.';
        }
        if (!is_numeric($old['marks']) || (float)$old['marks'] <= 0) {
            $errors[] = 'Marks must be a positive number.';
        }

        if (count($errors) === 0) {
            if ($editing) {
                taUpdateQuestion($conn, $questionId, $taId, $old);
                taReplaceOptions($conn, $questionId, $preparedOptions);
                taSetFlash('success', 'Question updated.');
            } else {
                $questionId = taCreateQuestion($conn, $quizId, $taId, $old);
                taReplaceOptions($conn, $questionId, $preparedOptions);
                taSetFlash('success', 'Question added.');
            }
            taRedirect('ta.php?action=questions&quiz_id=' . (int)$quizId);
        }
    }

    $quiz = taGetQuiz($conn, $quizId, $taId);
    $pageTitle = $editing ? 'Edit Question' : 'Add Question';
    taView('question_form', compact('pageTitle', 'quiz', 'question', 'old', 'errors', 'editing'));
}

function taOptionsForForm($options)
{
    $values = array('', '', '', '');
    for ($i = 0; $i < count($options) && $i < 4; $i++) {
        $values[$i] = $options[$i]['option_text'];
    }
    return $values;
}

function taCorrectOptionIndex($options)
{
    for ($i = 0; $i < count($options); $i++) {
        if ((int)$options[$i]['is_correct'] === 1) {
            return (string)$i;
        }
    }
    return '0';
}

function taPrepareOptions($optionTexts, $correctOption, &$errors)
{
    $options = array();
    if (!in_array($correctOption, array('0', '1', '2', '3'), true)) {
        $errors[] = 'Select exactly one correct option.';
    }

    for ($i = 0; $i < 4; $i++) {
        $text = isset($optionTexts[$i]) ? trim($optionTexts[$i]) : '';
        if ($text === '') {
            $errors[] = 'All four option texts are required.';
            break;
        }
        $options[] = array(
            'option_text' => $text,
            'is_correct' => ((string)$i === $correctOption) ? 1 : 0
        );
    }

    return $options;
}

function taDeleteQuestionAction($conn)
{
    if (!taIsPost()) {
        taRedirect('ta.php?action=quizzes');
    }

    $taId = taCurrentId();
    $questionId = isset($_POST['question_id']) ? (int)$_POST['question_id'] : 0;
    $question = taGetQuestion($conn, $questionId, $taId);

    if (!$question || (int)$question['created_by'] !== $taId) {
        taSetFlash('error', 'You can delete only questions created by you.');
        taRedirect('ta.php?action=quizzes');
    }

    taDeleteQuestion($conn, $questionId, $taId);
    taSetFlash('success', 'Question deleted.');
    taRedirect('ta.php?action=questions&quiz_id=' . (int)$question['quiz_id']);
}

function taQuestionBank($conn)
{
    $taId = taCurrentId();
    $courseId = taInt('course_id');
    taRequireCourseOrBack($conn, $courseId, $taId);

    if (taIsPost()) {
        $sourceQuestionId = isset($_POST['source_question_id']) ? (int)$_POST['source_question_id'] : 0;
        $targetQuizId = isset($_POST['target_quiz_id']) ? (int)$_POST['target_quiz_id'] : 0;
        if (taCopyQuestionToQuiz($conn, $sourceQuestionId, $targetQuizId, $taId)) {
            taSetFlash('success', 'Question copied to the selected quiz.');
        } else {
            taSetFlash('error', 'Question could not be copied.');
        }
        taRedirect('ta.php?action=question_bank&course_id=' . (int)$courseId);
    }

    $pageTitle = 'Question Bank';
    $course = taGetAssignedCourse($conn, $courseId, $taId);
    $questions = taGetQuestionBank($conn, $courseId, $taId);
    $practiceQuizzes = taGetPracticeQuizzesForCourse($conn, $courseId, $taId);
    taView('question_bank', compact('pageTitle', 'course', 'questions', 'practiceQuizzes'));
}

function taAttemptResults($conn)
{
    $taId = taCurrentId();
    $pageTitle = 'Attempt Results';
    $courseId = taInt('course_id');
    $quizId = taInt('quiz_id');
    if ($courseId > 0) {
        taRequireCourseOrBack($conn, $courseId, $taId);
    }
    if ($quizId > 0 && !taCanAccessQuiz($conn, $quizId, $taId)) {
        taSetFlash('error', 'Quiz not found for your assigned courses.');
        taRedirect('ta.php?action=attempt_results');
    }
    $courses = taGetAssignedCourses($conn, $taId);
    $quizzes = taGetQuizzes($conn, $taId, $courseId, '', '');
    $attempts = taGetAttemptResults($conn, $taId, $courseId, $quizId);
    taView('attempt_results', compact('pageTitle', 'courses', 'quizzes', 'attempts', 'courseId', 'quizId'));
}

function taAtRiskStudents($conn)
{
    $taId = taCurrentId();
    $pageTitle = 'At-Risk Students';
    $courseId = taInt('course_id');
    $thresholdInput = taClean('threshold');
    $threshold = $thresholdInput !== '' && is_numeric($thresholdInput) ? (float)$thresholdInput : taGetAtRiskThreshold($conn);
    if ($threshold < 0 || $threshold > 100) {
        taSetFlash('error', 'At-risk threshold must be between 0 and 100.');
        $threshold = taGetAtRiskThreshold($conn);
    }
    if ($courseId > 0) {
        taRequireCourseOrBack($conn, $courseId, $taId);
    }
    $courses = taGetAssignedCourses($conn, $taId);
    $students = taGetAtRiskStudents($conn, $taId, $courseId, $threshold);
    taView('at_risk_students', compact('pageTitle', 'courses', 'students', 'courseId', 'threshold'));
}

function taAnnouncements($conn)
{
    $taId = taCurrentId();
    $courseId = taInt('course_id');
    taRequireCourseOrBack($conn, $courseId, $taId);
    $errors = array();
    $editingAnnouncement = null;
    $editId = taInt('edit_id');
    if ($editId > 0) {
        $editingAnnouncement = taGetOwnAnnouncement($conn, $editId, $taId, $courseId);
        if (!$editingAnnouncement) {
            taSetFlash('error', 'You can edit only your own announcements for this course.');
            taRedirect('ta.php?action=announcements&course_id=' . (int)$courseId);
        }
    }

    $old = $editingAnnouncement ? array(
        'announcement_id' => $editingAnnouncement['id'],
        'title' => str_replace('(From TA) ', '', $editingAnnouncement['title']),
        'body' => $editingAnnouncement['body']
    ) : array(
        'announcement_id' => 0,
        'title' => '',
        'body' => ''
    );

    if (taIsPost()) {
        $announcementId = isset($_POST['announcement_id']) ? (int)$_POST['announcement_id'] : 0;
        $old['announcement_id'] = $announcementId;
        $old['title'] = isset($_POST['title']) ? trim($_POST['title']) : '';
        $old['body'] = isset($_POST['body']) ? trim($_POST['body']) : '';

        if ($old['title'] === '') {
            $errors[] = 'Announcement title is required.';
        }
        if ($old['body'] === '') {
            $errors[] = 'Announcement body is required.';
        }
        if ($announcementId > 0 && !taGetOwnAnnouncement($conn, $announcementId, $taId, $courseId)) {
            $errors[] = 'You can edit only your own announcements for this course.';
        }

        if (count($errors) === 0) {
            if (strpos($old['title'], '(From TA)') !== 0) {
                $old['title'] = '(From TA) ' . $old['title'];
            }
            $data = array('course_id' => $courseId, 'author_id' => $taId, 'title' => $old['title'], 'body' => $old['body']);
            if ($announcementId > 0) {
                taUpdateAnnouncement($conn, $announcementId, $taId, $data);
                taSetFlash('success', 'Announcement updated.');
            } else {
                taCreateAnnouncement($conn, $data);
                taSetFlash('success', 'Announcement posted.');
            }
            taRedirect('ta.php?action=announcements&course_id=' . (int)$courseId);
        }
    }

    $pageTitle = 'Announcements';
    $course = taGetAssignedCourse($conn, $courseId, $taId);
    $announcements = taGetCourseAnnouncements($conn, $courseId, $taId);
    taView('announcements', compact('pageTitle', 'course', 'announcements', 'editingAnnouncement', 'errors', 'old'));
}

function taDeleteAnnouncementAction($conn)
{
    if (taIsPost()) {
        $taId = taCurrentId();
        $announcementId = isset($_POST['announcement_id']) ? (int)$_POST['announcement_id'] : 0;
        $courseId = isset($_POST['course_id']) ? (int)$_POST['course_id'] : 0;
        taRequireCourseOrBack($conn, $courseId, $taId);

        if (!taGetOwnAnnouncement($conn, $announcementId, $taId, $courseId)) {
            taSetFlash('error', 'You can delete only your own announcements for this course.');
            taRedirect('ta.php?action=announcements&course_id=' . (int)$courseId);
        }

        taDeleteAnnouncement($conn, $announcementId, $taId, $courseId);
        taSetFlash('success', 'Announcement deleted.');
        taRedirect('ta.php?action=announcements&course_id=' . (int)$courseId);
    }
    taRedirect('ta.php?action=assigned_courses');
}

function taMaterials($conn)
{
    $taId = taCurrentId();
    $courseId = taInt('course_id');
    taRequireCourseOrBack($conn, $courseId, $taId);
    $errors = array();
    $editingMaterial = null;
    $editId = taInt('edit_id');
    if ($editId > 0) {
        $editingMaterial = taGetOwnMaterial($conn, $editId, $taId, $courseId);
        if (!$editingMaterial) {
            taSetFlash('error', 'You can edit only your own materials for this course.');
            taRedirect('ta.php?action=materials&course_id=' . (int)$courseId);
        }
    }

    $old = $editingMaterial ? array(
        'material_id' => $editingMaterial['id'],
        'title' => $editingMaterial['title'],
        'material_type' => $editingMaterial['material_type'],
        'external_link' => $editingMaterial['file_path']
    ) : array(
        'material_id' => 0,
        'title' => '',
        'material_type' => 'document',
        'external_link' => ''
    );

    if (taIsPost()) {
        $materialId = isset($_POST['material_id']) ? (int)$_POST['material_id'] : 0;
        $old['material_id'] = $materialId;
        $old['title'] = isset($_POST['title']) ? trim($_POST['title']) : '';
        $old['material_type'] = isset($_POST['material_type']) ? trim($_POST['material_type']) : '';
        $old['external_link'] = isset($_POST['external_link']) ? trim($_POST['external_link']) : '';
        $filePath = '';
        $currentMaterial = $materialId > 0 ? taGetOwnMaterial($conn, $materialId, $taId, $courseId) : null;
        $hasUploadedFile = isset($_FILES['material_file']) && $_FILES['material_file']['error'] !== UPLOAD_ERR_NO_FILE;

        if ($old['title'] === '') {
            $errors[] = 'Material title is required.';
        }
        if (!taAllowed($old['material_type'], array('document', 'link', 'video'))) {
            $errors[] = 'Select a valid material type.';
        }
        if ($materialId > 0 && !$currentMaterial) {
            $errors[] = 'You can edit only your own materials for this course.';
        }

        if ($old['material_type'] === 'document') {
            if ($hasUploadedFile) {
                $uploaded = taUploadFile('material_file', 'uploads/materials', array('pdf', 'doc', 'docx', 'ppt', 'pptx', 'txt', 'jpg', 'jpeg', 'png'));
                if ($uploaded === false) {
                    $errors[] = 'Document upload failed. Allowed file types: pdf, doc, docx, ppt, pptx, txt, jpg, jpeg, png.';
                } else {
                    $filePath = $uploaded;
                }
            } elseif ($currentMaterial) {
                $filePath = $currentMaterial['file_path'];
            } else {
                $errors[] = 'Document materials require a file upload.';
            }
        }

        if ($old['material_type'] === 'link' || $old['material_type'] === 'video') {
            if ($hasUploadedFile) {
                $errors[] = 'Use an external URL for link or video materials, not a file upload.';
            }
            if ($old['external_link'] === '') {
                $errors[] = 'External URL is required for link or video materials.';
            } elseif (!filter_var($old['external_link'], FILTER_VALIDATE_URL) || !preg_match('/^https?:\/\//i', $old['external_link'])) {
                $errors[] = 'External URL must start with http:// or https://.';
            } else {
                $filePath = $old['external_link'];
            }
        }

        if (count($errors) === 0) {
            $data = array('course_id' => $courseId, 'uploaded_by' => $taId, 'title' => $old['title'], 'file_path' => $filePath, 'material_type' => $old['material_type']);
            if ($materialId > 0) {
                taUpdateMaterial($conn, $materialId, $taId, $data);
                taSetFlash('success', 'Material updated.');
            } else {
                taCreateMaterial($conn, $data);
                taSetFlash('success', 'Material uploaded.');
            }
            taRedirect('ta.php?action=materials&course_id=' . (int)$courseId);
        }
    }

    $pageTitle = 'Materials';
    $course = taGetAssignedCourse($conn, $courseId, $taId);
    $materials = taGetCourseMaterials($conn, $courseId, $taId);
    taView('materials', compact('pageTitle', 'course', 'materials', 'editingMaterial', 'errors', 'old'));
}

function taDeleteMaterialAction($conn)
{
    if (taIsPost()) {
        $taId = taCurrentId();
        $materialId = isset($_POST['material_id']) ? (int)$_POST['material_id'] : 0;
        $courseId = isset($_POST['course_id']) ? (int)$_POST['course_id'] : 0;
        taRequireCourseOrBack($conn, $courseId, $taId);

        if (!taGetOwnMaterial($conn, $materialId, $taId, $courseId)) {
            taSetFlash('error', 'You can delete only your own materials for this course.');
            taRedirect('ta.php?action=materials&course_id=' . (int)$courseId);
        }

        taDeleteMaterial($conn, $materialId, $taId, $courseId);
        taSetFlash('success', 'Material deleted.');
        taRedirect('ta.php?action=materials&course_id=' . (int)$courseId);
    }
    taRedirect('ta.php?action=assigned_courses');
}

function taUploadFile($field, $folder, $allowedExtensions)
{
    if (!isset($_FILES[$field]) || $_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
        return false;
    }

    $name = $_FILES[$field]['name'];
    $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    if ($extension === '' || !in_array($extension, $allowedExtensions, true)) {
        return false;
    }

    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', pathinfo($name, PATHINFO_FILENAME));
    if ($safeName === '') {
        $safeName = 'material';
    }
    $path = $folder . '/ta_' . taCurrentId() . '_' . time() . '_' . mt_rand(1000, 9999) . '_' . $safeName . '.' . $extension;
    if (!move_uploaded_file($_FILES[$field]['tmp_name'], $path)) {
        return false;
    }

    return $path;
}

function taQaBoard($conn)
{
    $taId = taCurrentId();
    $pageTitle = 'Q&A Board';
    $courseId = taInt('course_id');
    if ($courseId > 0) {
        taRequireCourseOrBack($conn, $courseId, $taId);
    }
    $courses = taGetAssignedCourses($conn, $taId);
    $questions = taGetQaQuestions($conn, $courseId, $taId);
    taView('qa_board', compact('pageTitle', 'courses', 'questions', 'courseId'));
}

function taQaDetail($conn)
{
    $taId = taCurrentId();
    $questionId = taInt('id');
    $question = taGetQaQuestion($conn, $questionId, $taId);
    if (!$question) {
        taSetFlash('error', 'Q&A question not found for your assigned courses.');
        taRedirect('ta.php?action=qa_board');
    }

    $errors = array();
    if (taIsPost()) {
        $body = isset($_POST['body']) ? trim($_POST['body']) : '';
        if ($body === '') {
            $errors[] = 'Answer body is required.';
        } else {
            taCreateQaAnswer($conn, $questionId, $taId, $body);
            taSetFlash('success', 'Answer posted.');
            taRedirect('ta.php?action=qa_detail&id=' . (int)$questionId);
        }
    }

    $pageTitle = 'Q&A Detail';
    $answers = taGetQaAnswers($conn, $questionId);
    taView('qa_detail', compact('pageTitle', 'question', 'answers', 'errors'));
}

function taEndorseAnswerAction($conn)
{
    if (taIsPost()) {
        $answerId = isset($_POST['answer_id']) ? (int)$_POST['answer_id'] : 0;
        $questionId = isset($_POST['question_id']) ? (int)$_POST['question_id'] : 0;
        taEndorseQaAnswer($conn, $answerId, taCurrentId());
        taSetFlash('success', 'Answer endorsed.');
        taRedirect('ta.php?action=qa_detail&id=' . (int)$questionId);
    }
    taRedirect('ta.php?action=qa_board');
}

function taResolveQuestionAction($conn)
{
    if (taIsPost()) {
        $questionId = isset($_POST['question_id']) ? (int)$_POST['question_id'] : 0;
        taMarkQaResolved($conn, $questionId, taCurrentId());
        taSetFlash('success', 'Question marked as resolved.');
        taRedirect('ta.php?action=qa_detail&id=' . (int)$questionId);
    }
    taRedirect('ta.php?action=qa_board');
}

function taDoubtSessions($conn)
{
    $taId = taCurrentId();
    $pageTitle = 'Doubt Sessions';
    $courseId = taInt('course_id');
    if ($courseId > 0) {
        taRequireCourseOrBack($conn, $courseId, $taId);
    }
    $courses = taGetAssignedCourses($conn, $taId);
    $sessions = taGetDoubtSessions($conn, $taId, $courseId);
    taView('doubt_sessions', compact('pageTitle', 'courses', 'sessions', 'courseId'));
}

function taDoubtSessionForm($conn, $sessionId)
{
    $taId = taCurrentId();
    $editing = $sessionId > 0;
    $errors = array();
    $session = null;
    $courseId = taInt('course_id');

    if ($editing) {
        $session = taGetDoubtSession($conn, $sessionId, $taId);
        if (!$session) {
            taSetFlash('error', 'Doubt session not found.');
            taRedirect('ta.php?action=doubt_sessions');
        }
        $courseId = (int)$session['course_id'];
    }
    taRequireCourseOrBack($conn, $courseId, $taId);

    $old = $editing ? $session : array(
        'course_id' => $courseId,
        'title' => '',
        'scheduled_at' => '',
        'duration_minutes' => '60',
        'location_or_link' => '',
        'max_attendees' => '20'
    );

    if (taIsPost()) {
        $old['title'] = isset($_POST['title']) ? trim($_POST['title']) : '';
        $old['scheduled_at'] = isset($_POST['scheduled_at']) ? trim($_POST['scheduled_at']) : '';
        $old['duration_minutes'] = isset($_POST['duration_minutes']) ? trim($_POST['duration_minutes']) : '';
        $old['location_or_link'] = isset($_POST['location_or_link']) ? trim($_POST['location_or_link']) : '';
        $old['max_attendees'] = isset($_POST['max_attendees']) ? trim($_POST['max_attendees']) : '';

        if ($old['title'] === '') {
            $errors[] = 'Session title is required.';
        }
        $cleanDate = taCleanDateTime($old['scheduled_at']);
        if ($cleanDate === null || $cleanDate === false) {
            $errors[] = 'Use a valid scheduled date and time.';
        }
        if (!is_numeric($old['duration_minutes']) || (int)$old['duration_minutes'] <= 0) {
            $errors[] = 'Duration must be a positive number.';
        }
        if ($old['location_or_link'] === '') {
            $errors[] = 'Location or meeting link is required.';
        }
        if (!is_numeric($old['max_attendees']) || (int)$old['max_attendees'] <= 0) {
            $errors[] = 'Max attendees must be a positive number.';
        }

        if (count($errors) === 0) {
            $old['scheduled_at'] = $cleanDate;
            $old['course_id'] = $courseId;
            $old['ta_id'] = $taId;
            if ($editing) {
                taUpdateDoubtSession($conn, $sessionId, $taId, $old);
                taSetFlash('success', 'Doubt session updated.');
            } else {
                taCreateDoubtSession($conn, $old);
                taSetFlash('success', 'Doubt session scheduled.');
            }
            taRedirect('ta.php?action=doubt_sessions&course_id=' . (int)$courseId);
        }
    }

    $course = taGetAssignedCourse($conn, $courseId, $taId);
    $pageTitle = $editing ? 'Edit Doubt Session' : 'Create Doubt Session';
    taView('doubt_session_form', compact('pageTitle', 'course', 'session', 'old', 'errors', 'editing'));
}

function taCancelDoubtSessionAction($conn)
{
    if (taIsPost()) {
        $taId = taCurrentId();
        $sessionId = isset($_POST['session_id']) ? (int)$_POST['session_id'] : 0;
        $session = taGetDoubtSession($conn, $sessionId, $taId);
        if ($session) {
            taCancelDoubtSession($conn, $sessionId, $taId);
            taSetFlash('success', 'Doubt session cancelled.');
            taRedirect('ta.php?action=doubt_sessions&course_id=' . (int)$session['course_id']);
        }
    }
    taRedirect('ta.php?action=doubt_sessions');
}

function taDoubtSessionBookings($conn)
{
    $taId = taCurrentId();
    $sessionId = taInt('id');
    $session = taGetDoubtSession($conn, $sessionId, $taId);
    if (!$session) {
        taSetFlash('error', 'Doubt session not found.');
        taRedirect('ta.php?action=doubt_sessions');
    }

    $pageTitle = 'Doubt Session Bookings';
    $bookings = taGetDoubtSessionBookings($conn, $sessionId, $taId);
    taView('doubt_session_bookings', compact('pageTitle', 'session', 'bookings'));
}

function taCourseSummaryReport($conn)
{
    $taId = taCurrentId();
    $courseId = taInt('course_id');
    taRequireCourseOrBack($conn, $courseId, $taId);

    $threshold = taGetAtRiskThreshold($conn);
    $pageTitle = 'Course Summary Report';
    $course = taGetAssignedCourse($conn, $courseId, $taId);
    $report = taGetCourseSummaryReport($conn, $courseId, $taId, $threshold);
    taView('course_summary_report', compact('pageTitle', 'course', 'report', 'threshold'));
}
