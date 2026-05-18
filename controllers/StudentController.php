<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/student_helpers.php';
require_once __DIR__ . '/../models/StudentModel.php';
require_once __DIR__ . '/../models/StudentCourseModel.php';
require_once __DIR__ . '/../models/StudentQuizModel.php';
require_once __DIR__ . '/../models/StudentQaModel.php';
require_once __DIR__ . '/../models/StudentDoubtSessionModel.php';
require_once __DIR__ . '/../models/StudentPerformanceModel.php';

studentRequireLogin();

function studentRenderPage($viewName, $pageTitle, $data = [])
{
    $studentPageTitle = $pageTitle;
    $studentCurrentAction = $_GET['action'] ?? 'dashboard';
    extract($data);

    include __DIR__ . '/../views/partials/student_header.php';
    include __DIR__ . '/../views/partials/student_sidebar.php';
    include __DIR__ . '/../views/student/' . $viewName . '.php';
    include __DIR__ . '/../views/partials/student_footer.php';
}

function studentFlashFromResult($result)
{
    studentSetFlash($result['success'] ? 'success' : 'error', $result['message']);
}

function studentDashboardAction()
{
    $studentId = studentCurrentId();
    $stats = studentGetDashboardStats($studentId);
    $enrolledCourses = studentGetEnrolledCourses($studentId);
    studentRenderPage('dashboard', 'Student Dashboard', [
        'stats' => $stats,
        'enrolledCourses' => $enrolledCourses
    ]);
}

function studentProfileAction()
{
    $studentId = studentCurrentId();
    $student = studentFindById($studentId);
    $errors = [];

    if (studentIsPost()) {
        $profileAction = studentCleanText($_POST['profile_action'] ?? '');

        if ($profileAction === 'update_profile') {
            $name = studentCleanText($_POST['name'] ?? '');
            $phone = studentCleanText($_POST['phone'] ?? '');
            $program = studentCleanText($_POST['program'] ?? '');

            if ($name === '') {
                $errors[] = 'Name is required.';
            } elseif (strlen($name) > 100) {
                $errors[] = 'Name cannot be longer than 100 characters.';
            }

            if ($phone === '') {
                $errors[] = 'Phone number is required.';
            } elseif (strlen($phone) > 30) {
                $errors[] = 'Phone number cannot be longer than 30 characters.';
            }

            if ($program === '') {
                $errors[] = 'Program is required.';
            } elseif (strlen($program) > 100) {
                $errors[] = 'Program cannot be longer than 100 characters.';
            }

            if (empty($errors)) {
                if (studentUpdateProfile($studentId, $name, $phone, $program)) {
                    $_SESSION['student_name'] = $name;
                    studentSetFlash('success', 'Profile updated successfully.');
                    studentRedirect('student.php?action=profile');
                }
                $errors[] = 'Profile could not be updated.';
            }
        } elseif ($profileAction === 'change_password') {
            $currentPassword = (string)($_POST['current_password'] ?? '');
            $newPassword = (string)($_POST['new_password'] ?? '');
            $confirmPassword = (string)($_POST['confirm_password'] ?? '');
            $student = studentFindById($studentId);

            if ($currentPassword === '') {
                $errors[] = 'Current password is required.';
            } elseif (!$student || !password_verify($currentPassword, $student['password_hash'])) {
                $errors[] = 'Current password is incorrect.';
            }

            if ($newPassword === '') {
                $errors[] = 'New password is required.';
            } elseif (strlen($newPassword) < 6) {
                $errors[] = 'New password must be at least 6 characters long.';
            }

            if ($confirmPassword === '') {
                $errors[] = 'Confirm password is required.';
            } elseif ($newPassword !== $confirmPassword) {
                $errors[] = 'New password and confirm password do not match.';
            }

            if (empty($errors)) {
                $hash = password_hash($newPassword, PASSWORD_DEFAULT);
                if (studentUpdatePassword($studentId, $hash)) {
                    studentSetFlash('success', 'Password changed successfully.');
                    studentRedirect('student.php?action=profile');
                }
                $errors[] = 'Password could not be changed.';
            }
        } elseif ($profileAction === 'upload_picture') {
            if (!isset($_FILES['profile_pic']) || $_FILES['profile_pic']['error'] === UPLOAD_ERR_NO_FILE) {
                $errors[] = 'Please choose a profile picture to upload.';
            } elseif ($_FILES['profile_pic']['error'] !== UPLOAD_ERR_OK) {
                $errors[] = 'Profile picture upload failed.';
            } else {
                $file = $_FILES['profile_pic'];
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

                if (!in_array($extension, $allowed, true)) {
                    $errors[] = 'Profile picture must be JPG, PNG, GIF, or WEBP.';
                } elseif ($file['size'] > 2 * 1024 * 1024) {
                    $errors[] = 'Profile picture must be 2 MB or smaller.';
                } else {
                    $profileStudentId = !empty($student['id']) ? (int)$student['id'] : $studentId;
                    $uploadDir = __DIR__ . '/../uploads/profile_pics/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    $fileName = 'student_' . $profileStudentId . '_' . time() . '.' . $extension;
                    $targetPath = $uploadDir . $fileName;
                    $dbPath = 'uploads/profile_pics/' . $fileName;

                    if (move_uploaded_file($file['tmp_name'], $targetPath) && studentUpdateProfilePicture($profileStudentId, $dbPath)) {
                        studentSetFlash('success', 'Profile picture updated successfully.');
                        studentRedirect('student.php?action=profile');
                    }

                    $errors[] = 'Profile picture could not be saved.';
                }
            }
        }
    }

    $student = studentFindById($studentId);
    studentRenderPage('profile', 'My Profile', [
        'student' => $student,
        'errors' => $errors
    ]);
}

function studentCoursesAction()
{
    $studentId = studentCurrentId();

    if (studentIsPost()) {
        $courseId = studentValidPositiveId($_POST['course_id'] ?? '') ? (int)$_POST['course_id'] : 0;
        $courseAction = studentCleanText($_POST['course_action'] ?? '');

        if ($courseId <= 0) {
            studentSetFlash('error', 'Invalid course selected.');
        } elseif ($courseAction === 'enroll') {
            studentFlashFromResult(studentEnrollCourse($studentId, $courseId));
        } elseif ($courseAction === 'drop') {
            studentFlashFromResult(studentDropCourse($studentId, $courseId));
        }

        studentRedirect('student.php?action=courses');
    }

    $keyword = studentCleanText($_GET['keyword'] ?? '');
    $subjectId = studentValidPositiveId($_GET['subject_id'] ?? '') ? (int)$_GET['subject_id'] : 0;
    $subjects = studentGetSubjects();
    $courses = studentGetActiveCourses($keyword, $subjectId, $studentId);

    studentRenderPage('courses', 'Browse Courses', [
        'subjects' => $subjects,
        'courses' => $courses,
        'keyword' => $keyword,
        'subjectId' => $subjectId
    ]);
}

function studentCourseDetailAction()
{
    $studentId = studentCurrentId();
    $courseId = studentValidPositiveId($_GET['id'] ?? '') ? (int)$_GET['id'] : 0;

    if ($courseId <= 0) {
        studentSetFlash('error', 'Invalid course selected.');
        studentRedirect('student.php?action=courses');
    }

    if (studentIsPost()) {
        $courseAction = studentCleanText($_POST['course_action'] ?? '');

        if ($courseAction === 'enroll') {
            studentFlashFromResult(studentEnrollCourse($studentId, $courseId));
        } elseif ($courseAction === 'drop') {
            studentFlashFromResult(studentDropCourse($studentId, $courseId));
        }

        studentRedirect('student.php?action=course_detail&id=' . $courseId);
    }

    $course = studentGetCourseById($courseId, $studentId);
    if (!$course) {
        studentSetFlash('error', 'Course was not found.');
        studentRedirect('student.php?action=courses');
    }

    $tas = studentGetCourseTas($courseId);
    $announcements = studentIsActiveInCourse($studentId, $courseId) ? studentGetCourseAnnouncements($courseId, $studentId, 3) : [];
    $materials = studentIsActiveInCourse($studentId, $courseId) ? studentGetCourseMaterials($courseId, $studentId) : [];
    $quizzes = studentIsActiveInCourse($studentId, $courseId) ? studentGetQuizList($studentId, $courseId) : [];

    studentRenderPage('course_detail', 'Course Detail', [
        'course' => $course,
        'tas' => $tas,
        'announcements' => $announcements,
        'materials' => $materials,
        'quizzes' => $quizzes
    ]);
}

function studentMaterialsAction()
{
    $studentId = studentCurrentId();
    $courseId = studentValidPositiveId($_GET['course_id'] ?? '') ? (int)$_GET['course_id'] : 0;

    if ($courseId <= 0 || !studentIsActiveInCourse($studentId, $courseId)) {
        studentSetFlash('error', 'You must be actively enrolled to view course materials.');
        studentRedirect('student.php?action=courses');
    }

    $course = studentGetCourseById($courseId, $studentId);
    $materials = studentGetCourseMaterials($courseId, $studentId);
    studentRenderPage('materials', 'Course Materials', [
        'course' => $course,
        'materials' => $materials
    ]);
}

function studentAnnouncementsAction()
{
    $studentId = studentCurrentId();
    $courseId = studentValidPositiveId($_GET['course_id'] ?? '') ? (int)$_GET['course_id'] : 0;

    if ($courseId <= 0 || !studentIsActiveInCourse($studentId, $courseId)) {
        studentSetFlash('error', 'You must be actively enrolled to view announcements.');
        studentRedirect('student.php?action=courses');
    }

    $course = studentGetCourseById($courseId, $studentId);
    $announcements = studentGetCourseAnnouncements($courseId, $studentId, 100);
    studentRenderPage('announcements', 'Announcements', [
        'course' => $course,
        'announcements' => $announcements
    ]);
}

function studentQaBoardAction()
{
    $studentId = studentCurrentId();
    $courseId = studentValidPositiveId($_GET['course_id'] ?? '') ? (int)$_GET['course_id'] : 0;
    $errors = [];

    if ($courseId <= 0 || !studentIsActiveInCourse($studentId, $courseId)) {
        studentSetFlash('error', 'You must be actively enrolled to use the Q&A board.');
        studentRedirect('student.php?action=courses');
    }

    if (studentIsPost()) {
        $title = studentCleanText($_POST['title'] ?? '');
        $body = studentCleanText($_POST['body'] ?? '');

        if ($title === '') {
            $errors[] = 'Question title is required.';
        } elseif (strlen($title) > 150) {
            $errors[] = 'Question title cannot be longer than 150 characters.';
        }

        if ($body === '') {
            $errors[] = 'Question body is required.';
        }

        if (empty($errors)) {
            if (studentCreateQaQuestion($courseId, $studentId, $title, $body)) {
                studentSetFlash('success', 'Question posted successfully.');
                studentRedirect('student.php?action=qa_board&course_id=' . $courseId);
            }
            $errors[] = 'Question could not be posted.';
        }
    }

    $course = studentGetCourseById($courseId, $studentId);
    $questions = studentGetQaQuestions($courseId, $studentId);
    studentRenderPage('qa_board', 'Course Q&A', [
        'course' => $course,
        'questions' => $questions,
        'errors' => $errors
    ]);
}

function studentQaDetailAction()
{
    $studentId = studentCurrentId();
    $questionId = studentValidPositiveId($_GET['id'] ?? '') ? (int)$_GET['id'] : 0;

    if ($questionId <= 0) {
        studentSetFlash('error', 'Invalid Q&A question selected.');
        studentRedirect('student.php?action=courses');
    }

    $question = studentGetQaQuestionById($questionId, $studentId);
    if (!$question) {
        studentSetFlash('error', 'Q&A question was not found.');
        studentRedirect('student.php?action=courses');
    }

    if (studentIsPost()) {
        if ((int)$question['student_id'] === $studentId) {
            studentMarkQaResolved($questionId, $studentId);
            studentSetFlash('success', 'Question marked as resolved.');
        } else {
            studentSetFlash('error', 'Only the owner can mark this question as resolved.');
        }

        studentRedirect('student.php?action=qa_detail&id=' . $questionId);
    }

    $answers = studentGetQaAnswers($questionId);
    studentRenderPage('qa_detail', 'Q&A Detail', [
        'question' => $question,
        'answers' => $answers
    ]);
}

function studentQuizListAction()
{
    $studentId = studentCurrentId();
    $courseId = studentValidPositiveId($_GET['course_id'] ?? '') ? (int)$_GET['course_id'] : 0;

    if ($courseId > 0 && !studentIsActiveInCourse($studentId, $courseId)) {
        studentSetFlash('error', 'You must be actively enrolled to view quizzes for that course.');
        studentRedirect('student.php?action=courses');
    }

    $quizzes = studentGetQuizList($studentId, $courseId);
    studentRenderPage('quiz_list', 'Quiz List', [
        'quizzes' => $quizzes,
        'courseId' => $courseId
    ]);
}

function studentQuizTakeAction()
{
    $studentId = studentCurrentId();
    $quizId = studentValidPositiveId($_GET['id'] ?? '') ? (int)$_GET['id'] : 0;
    $errors = [];
    $selectedAnswers = [];

    if ($quizId <= 0) {
        studentSetFlash('error', 'Invalid quiz selected.');
        studentRedirect('student.php?action=quiz_list');
    }

    $quiz = studentGetAvailableQuizById($quizId, $studentId);
    if (!$quiz) {
        studentSetFlash('error', 'This quiz is not available for your account.');
        studentRedirect('student.php?action=quiz_list');
    }

    if ($quiz['quiz_type'] === 'graded' && studentHasCompletedGradedAttempt($studentId, $quizId)) {
        studentSetFlash('error', 'You have already completed this graded quiz.');
        studentRedirect('student.php?action=quiz_history');
    }

    $questions = studentGetQuizQuestions($quizId);
    if (empty($questions)) {
        studentSetFlash('error', 'This quiz has no questions yet.');
        studentRedirect('student.php?action=quiz_list');
    }

    if (studentIsPost()) {
        $postedAnswers = isset($_POST['answers']) && is_array($_POST['answers']) ? $_POST['answers'] : [];
        $score = 0.0;

        foreach ($questions as $question) {
            $questionId = (int)$question['id'];

            if (!isset($postedAnswers[$questionId]) || !studentValidPositiveId($postedAnswers[$questionId])) {
                $errors[] = 'Please answer every question before submitting.';
                break;
            }

            $selectedOptionId = (int)$postedAnswers[$questionId];
            $selectedAnswers[$questionId] = $selectedOptionId;
            $matched = false;

            foreach ($question['options'] as $option) {
                if ((int)$option['id'] === $selectedOptionId) {
                    $matched = true;
                    if ((int)$option['is_correct'] === 1) {
                        $score += (float)$question['marks'];
                    }
                    break;
                }
            }

            if (!$matched) {
                $errors[] = 'One selected answer does not belong to this quiz.';
                break;
            }
        }

        if (empty($errors)) {
            $isGraded = $quiz['quiz_type'] === 'graded' ? 1 : 0;
            $attemptId = studentCreateAttemptWithAnswers($quizId, $studentId, $score, $isGraded, $selectedAnswers);

            if ($attemptId > 0) {
                studentRedirect('student.php?action=quiz_result&attempt_id=' . $attemptId);
            }

            $errors[] = 'Quiz attempt could not be saved.';
        }
    }

    studentRenderPage('quiz_take', 'Take Quiz', [
        'quiz' => $quiz,
        'questions' => $questions,
        'errors' => $errors,
        'selectedAnswers' => $selectedAnswers
    ]);
}

function studentQuizResultAction()
{
    $studentId = studentCurrentId();
    $attemptId = studentValidPositiveId($_GET['attempt_id'] ?? '') ? (int)$_GET['attempt_id'] : 0;

    if ($attemptId <= 0) {
        studentSetFlash('error', 'Invalid attempt selected.');
        studentRedirect('student.php?action=quiz_history');
    }

    $attempt = studentGetAttemptResult($attemptId, $studentId);
    if (!$attempt) {
        studentSetFlash('error', 'Quiz result was not found.');
        studentRedirect('student.php?action=quiz_history');
    }

    $breakdown = studentGetAttemptBreakdown($attemptId, $studentId);
    studentRenderPage('quiz_result', 'Quiz Result', [
        'attempt' => $attempt,
        'breakdown' => $breakdown
    ]);
}

function studentQuizHistoryAction()
{
    $attempts = studentGetAttemptHistory(studentCurrentId());
    studentRenderPage('quiz_history', 'Quiz History', [
        'attempts' => $attempts
    ]);
}

function studentLeaderboardAction()
{
    $studentId = studentCurrentId();
    $quizChoices = studentGetLeaderboardQuizChoices($studentId);
    $selectedQuizId = studentValidPositiveId($_GET['quiz_id'] ?? '') ? (int)$_GET['quiz_id'] : 0;

    if ($selectedQuizId === 0 && !empty($quizChoices)) {
        $selectedQuizId = (int)$quizChoices[0]['id'];
    }

    studentRenderPage('leaderboard', 'Leaderboard', [
        'quizChoices' => $quizChoices,
        'selectedQuizId' => $selectedQuizId
    ]);
}

function studentPerformanceAction()
{
    $studentId = studentCurrentId();
    studentRenderPage('performance', 'Performance', [
        'summary' => studentGetPerformanceSummary($studentId),
        'scoresOverTime' => studentGetScoresOverTime($studentId),
        'subjectAverages' => studentGetAverageScoreBySubject($studentId),
        'classComparison' => studentGetClassComparison($studentId)
    ]);
}

function studentDoubtSessionsAction()
{
    $studentId = studentCurrentId();

    if (studentIsPost()) {
        $sessionId = studentValidPositiveId($_POST['session_id'] ?? '') ? (int)$_POST['session_id'] : 0;

        if ($sessionId <= 0) {
            studentSetFlash('error', 'Invalid doubt session selected.');
        } else {
            studentFlashFromResult(studentBookDoubtSession($sessionId, $studentId));
        }

        studentRedirect('student.php?action=doubt_sessions');
    }

    $sessions = studentGetUpcomingDoubtSessions($studentId);
    studentRenderPage('doubt_sessions', 'Doubt Sessions', [
        'sessions' => $sessions
    ]);
}

function studentMyBookingsAction()
{
    $bookings = studentGetMyBookings(studentCurrentId());
    studentRenderPage('my_bookings', 'My Bookings', [
        'bookings' => $bookings
    ]);
}

$action = $_GET['action'] ?? 'dashboard';

switch ($action) {
    case 'dashboard':
        studentDashboardAction();
        break;
    case 'profile':
        studentProfileAction();
        break;
    case 'courses':
        studentCoursesAction();
        break;
    case 'course_detail':
        studentCourseDetailAction();
        break;
    case 'materials':
        studentMaterialsAction();
        break;
    case 'announcements':
        studentAnnouncementsAction();
        break;
    case 'qa_board':
        studentQaBoardAction();
        break;
    case 'qa_detail':
        studentQaDetailAction();
        break;
    case 'quiz_list':
        studentQuizListAction();
        break;
    case 'quiz_take':
        studentQuizTakeAction();
        break;
    case 'quiz_result':
        studentQuizResultAction();
        break;
    case 'quiz_history':
        studentQuizHistoryAction();
        break;
    case 'leaderboard':
        studentLeaderboardAction();
        break;
    case 'performance':
        studentPerformanceAction();
        break;
    case 'doubt_sessions':
        studentDoubtSessionsAction();
        break;
    case 'my_bookings':
        studentMyBookingsAction();
        break;
    default:
        studentSetFlash('error', 'Requested student page was not found.');
        studentRedirect('student.php?action=dashboard');
}
?>
