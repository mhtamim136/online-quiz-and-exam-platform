<?php
require_once __DIR__ . '/config/instructor_session.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/instructor_helpers.php';

require_once __DIR__ . '/models/InstructorDashboardModel.php';
require_once __DIR__ . '/models/InstructorProfileModel.php';
require_once __DIR__ . '/models/InstructorCourseModel.php';
require_once __DIR__ . '/models/InstructorEnrollmentModel.php';
require_once __DIR__ . '/models/InstructorTAModel.php';
require_once __DIR__ . '/models/InstructorQuizModel.php';
require_once __DIR__ . '/models/InstructorQuestionModel.php';
require_once __DIR__ . '/models/InstructorAnalyticsModel.php';
require_once __DIR__ . '/models/InstructorAnnouncementModel.php';
require_once __DIR__ . '/models/InstructorMaterialModel.php';
require_once __DIR__ . '/models/InstructorQAModel.php';

require_once __DIR__ . '/controllers/InstructorDashboardController.php';
require_once __DIR__ . '/controllers/InstructorProfileController.php';
require_once __DIR__ . '/controllers/InstructorCourseController.php';
require_once __DIR__ . '/controllers/InstructorEnrollmentController.php';
require_once __DIR__ . '/controllers/InstructorTAController.php';
require_once __DIR__ . '/controllers/InstructorQuizController.php';
require_once __DIR__ . '/controllers/InstructorQuestionController.php';
require_once __DIR__ . '/controllers/InstructorAnalyticsController.php';
require_once __DIR__ . '/controllers/InstructorAnnouncementController.php';
require_once __DIR__ . '/controllers/InstructorMaterialController.php';
require_once __DIR__ . '/controllers/InstructorQAController.php';

instructorRequireRole('instructor');

$action = isset($_GET['action']) ? $_GET['action'] : 'dashboard';

switch ($action) {
    case 'profile':
        instructorProfileController();
        break;
    case 'courses':
        instructorCourseListController();
        break;
    case 'create_course':
        instructorCourseCreateController();
        break;
    case 'edit_course':
        instructorCourseEditController();
        break;
    case 'course_detail':
        instructorCourseDetailController();
        break;
    case 'archived_courses':
        instructorArchivedCoursesController();
        break;
    case 'enrolled_students':
        instructorEnrolledStudentsController();
        break;
    case 'enrollment_requests':
        instructorEnrollmentRequestsController();
        break;
    case 'assign_ta':
        instructorTAController();
        break;
    case 'quiz_list':
        instructorQuizListController();
        break;
    case 'create_quiz':
        instructorQuizCreateController();
        break;
    case 'edit_quiz':
        instructorQuizEditController();
        break;
    case 'toggle_quiz':
        instructorQuizToggleController();
        break;
    case 'quiz_questions':
        instructorQuizQuestionsController();
        break;
    case 'add_question':
        instructorQuestionAddController();
        break;
    case 'edit_question':
        instructorQuestionEditController();
        break;
    case 'question_bank':
        instructorQuestionBankController();
        break;
    case 'quiz_attempts':
        instructorQuizAttemptsController();
        break;
    case 'quiz_analytics':
        instructorQuizAnalyticsController();
        break;
    case 'announcements':
        instructorAnnouncementListController();
        break;
    case 'create_announcement':
        instructorAnnouncementCreateController();
        break;
    case 'edit_announcement':
        instructorAnnouncementEditController();
        break;
    case 'materials':
        instructorMaterialListController();
        break;
    case 'upload_material':
        instructorMaterialUploadController();
        break;
    case 'edit_material':
        instructorMaterialEditController();
        break;
    case 'qa_board':
        instructorQABoardController();
        break;
    case 'qa_detail':
        instructorQADetailController();
        break;
    case 'reports':
        instructorReportsController();
        break;
    case 'performance_report':
        instructorPerformanceReportController();
        break;
    default:
        instructorDashboardController();
        break;
}
