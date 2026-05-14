<?php
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/AdminModel.php';
require_once __DIR__ . '/../models/SubjectModel.php';
require_once __DIR__ . '/../models/CourseModel.php';
require_once __DIR__ . '/../models/QuizModel.php';
require_once __DIR__ . '/../models/PlatformSettingModel.php';
require_once __DIR__ . '/../models/PlatformAnnouncementModel.php';
require_once __DIR__ . '/../models/IntegrityReportModel.php';
require_once __DIR__ . '/../models/AuditLogModel.php';

function adminRoute($conn, $action)
{
    switch ($action) {
        case 'dashboard':
            adminDashboard($conn);
            break;
        case 'users':
            adminUsers($conn);
            break;
        case 'user_view':
            adminUserView($conn);
            break;
        case 'update_user_status':
            adminUpdateUserStatus($conn);
            break;
        case 'change_user_role':
            adminChangeUserRole($conn);
            break;
        case 'instructor_requests':
            adminInstructorRequests($conn);
            break;
        case 'approve_instructor':
            adminApproveInstructor($conn);
            break;
        case 'reject_instructor':
            adminRejectInstructor($conn);
            break;
        case 'create_ta':
            adminCreateTa($conn);
            break;
        case 'subjects':
            adminSubjects($conn);
            break;
        case 'courses':
            adminCourses($conn);
            break;
        case 'quizzes':
            adminQuizzes($conn);
            break;
        case 'integrity_reports':
            adminIntegrityReports($conn);
            break;
        case 'platform_announcements':
            adminPlatformAnnouncements($conn);
            break;
        case 'student_report':
            adminStudentReport($conn);
            break;
        case 'institutional_report':
            adminInstitutionalReport($conn);
            break;
        case 'settings':
            adminSettings($conn);
            break;
        case 'audit_logs':
            adminAuditLogs($conn);
            break;
        default:
            setFlashMessage('error', 'The requested Admin page was not found.');
            redirect('admin.php?action=dashboard');
    }
}

function adminView($view, $data = array())
{
    extract($data);
    require __DIR__ . '/../views/admin/' . $view . '.php';
}

function adminClean($key)
{
    return isset($_REQUEST[$key]) ? trim($_REQUEST[$key]) : '';
}

function adminAllowed($value, $allowed)
{
    return in_array($value, $allowed, true);
}

function adminDashboard($conn)
{
    $pageTitle = 'Dashboard';
    $stats = adminGetDashboardStats($conn);
    adminView('dashboard', compact('pageTitle', 'stats'));
}

function adminUsers($conn)
{
    $pageTitle = 'User Management';
    $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
    $role = isset($_GET['role']) ? trim($_GET['role']) : '';
    $active = isset($_GET['active']) ? trim($_GET['active']) : '';

    if (!adminAllowed($role, array('', 'student', 'instructor', 'ta', 'admin'))) {
        $role = '';
    }

    if (!adminAllowed($active, array('', '0', '1'))) {
        $active = '';
    }

    $users = userGetAll($conn, $keyword, $role, $active);
    adminView('users', compact('pageTitle', 'users', 'keyword', 'role', 'active'));
}

function adminUserView($conn)
{
    $pageTitle = 'User Details';
    $user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $user = userFindById($conn, $user_id);

    if (!$user) {
        setFlashMessage('error', 'User not found.');
        redirect('admin.php?action=users');
    }

    adminView('user_view', compact('pageTitle', 'user'));
}

function adminUpdateUserStatus($conn)
{
    if (!isPost()) {
        redirect('admin.php?action=users');
    }

    $user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
    $is_active = isset($_POST['is_active']) ? (int)$_POST['is_active'] : 0;
    $user = userFindById($conn, $user_id);

    if (!$user) {
        setFlashMessage('error', 'User not found.');
        redirect('admin.php?action=users');
    }

    if ($user_id === currentAdminId() && $is_active === 0) {
        setFlashMessage('error', 'You cannot deactivate your own Admin account.');
        redirect('admin.php?action=users');
    }

    userSetActiveStatus($conn, $user_id, $is_active);
    logAdminAction($conn, $is_active === 1 ? 'activate_user' : 'deactivate_user', 'users', $user_id, 'Updated account active status.');
    setFlashMessage('success', 'User status updated successfully.');
    redirect('admin.php?action=users');
}

function adminChangeUserRole($conn)
{
    if (!isPost()) {
        redirect('admin.php?action=users');
    }

    $user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
    $role = isset($_POST['role']) ? trim($_POST['role']) : '';
    $user = userFindById($conn, $user_id);

    if (!$user) {
        setFlashMessage('error', 'User not found.');
        redirect('admin.php?action=users');
    }

    if (!adminAllowed($role, array('student', 'instructor', 'ta', 'admin'))) {
        setFlashMessage('error', 'Invalid role selected.');
        redirect('admin.php?action=users');
    }

    if ($user_id === currentAdminId()) {
        setFlashMessage('error', 'You cannot change your own Admin role.');
        redirect('admin.php?action=users');
    }

    userChangeRole($conn, $user_id, $role);
    logAdminAction($conn, 'change_user_role', 'users', $user_id, 'Changed role to ' . $role . '.');
    setFlashMessage('success', 'User role updated successfully.');
    redirect('admin.php?action=users');
}

function adminInstructorRequests($conn)
{
    $pageTitle = 'Instructor Approval';
    $requests = userGetPendingInstructors($conn);
    adminView('instructor_requests', compact('pageTitle', 'requests'));
}

function adminApproveInstructor($conn)
{
    adminHandleInstructorDecision($conn, 'approved', 1, 'approve_instructor', 'Instructor approved successfully.');
}

function adminRejectInstructor($conn)
{
    adminHandleInstructorDecision($conn, 'rejected', 0, 'reject_instructor', 'Instructor rejected successfully.');
}

function adminHandleInstructorDecision($conn, $approval_status, $is_active, $audit_action, $message)
{
    if (!isPost()) {
        redirect('admin.php?action=instructor_requests');
    }

    $user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
    $user = userFindById($conn, $user_id);

    if (!$user || $user['role'] !== 'instructor') {
        setFlashMessage('error', 'Instructor request not found.');
        redirect('admin.php?action=instructor_requests');
    }

    userUpdateInstructorApproval($conn, $user_id, $approval_status, $is_active);
    logAdminAction($conn, $audit_action, 'users', $user_id, 'Instructor approval status changed to ' . $approval_status . '.');
    setFlashMessage('success', $message);
    redirect('admin.php?action=instructor_requests');
}

function adminCreateTa($conn)
{
    $pageTitle = 'Create TA Account';
    $errors = array();
    $old = array(
        'name' => '',
        'username' => '',
        'email' => '',
        'phone' => '',
        'department' => '',
        'bio' => ''
    );

    if (isPost()) {
        $old['name'] = adminClean('name');
        $old['username'] = adminClean('username');
        $old['email'] = adminClean('email');
        $old['phone'] = adminClean('phone');
        $old['department'] = adminClean('department');
        $old['bio'] = adminClean('bio');
        $password = adminClean('password');
        $confirm_password = adminClean('confirm_password');

        if ($old['name'] === '') {
            $errors[] = 'Name is required.';
        }
        if ($old['username'] === '') {
            $errors[] = 'Username is required.';
        } elseif (userUsernameExists($conn, $old['username'])) {
            $errors[] = 'Username already exists.';
        }
        if ($old['email'] === '') {
            $errors[] = 'Email is required.';
        } elseif (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address.';
        } elseif (userEmailExists($conn, $old['email'])) {
            $errors[] = 'Email already exists.';
        }
        if ($password === '') {
            $errors[] = 'Password is required.';
        }
        if ($confirm_password === '') {
            $errors[] = 'Confirm password is required.';
        }
        if ($password !== '' && $confirm_password !== '' && $password !== $confirm_password) {
            $errors[] = 'Password and confirm password must match.';
        }

        if (count($errors) === 0) {
            $old['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
            $new_id = userCreateTa($conn, $old);
            logAdminAction($conn, 'create_ta', 'users', (int)$new_id, 'Created TA account for ' . $old['email'] . '.');
            setFlashMessage('success', 'TA account created successfully.');
            redirect('admin.php?action=create_ta');
        }
    }

    adminView('create_ta', compact('pageTitle', 'errors', 'old'));
}

function adminSubjects($conn)
{
    $pageTitle = 'Subject Management';
    $errors = array();
    $editSubject = null;

    if (isPost()) {
        $subject_action = adminClean('subject_action');
        $subject_id = isset($_POST['subject_id']) ? (int)$_POST['subject_id'] : 0;
        $name = adminClean('name');
        $description = adminClean('description');

        if ($subject_action === 'add' || $subject_action === 'edit') {
            if ($name === '') {
                $errors[] = 'Subject name is required.';
            } elseif (subjectNameExists($conn, $name, $subject_action === 'edit' ? $subject_id : 0)) {
                $errors[] = 'Subject name already exists.';
            }
        }

        if (count($errors) === 0) {
            if ($subject_action === 'add') {
                $new_id = subjectCreate($conn, $name, $description);
                logAdminAction($conn, 'create_subject', 'subjects', (int)$new_id, 'Created subject ' . $name . '.');
                setFlashMessage('success', 'Subject added successfully.');
                redirect('admin.php?action=subjects');
            } elseif ($subject_action === 'edit') {
                subjectUpdate($conn, $subject_id, $name, $description);
                logAdminAction($conn, 'update_subject', 'subjects', $subject_id, 'Renamed or updated subject.');
                setFlashMessage('success', 'Subject updated successfully.');
                redirect('admin.php?action=subjects');
            } elseif ($subject_action === 'delete') {
                if (subjectCourseCount($conn, $subject_id) > 0) {
                    setFlashMessage('error', 'This subject cannot be deleted because courses are using it.');
                    redirect('admin.php?action=subjects');
                }

                subjectDelete($conn, $subject_id);
                logAdminAction($conn, 'delete_subject', 'subjects', $subject_id, 'Deleted unused subject.');
                setFlashMessage('success', 'Subject deleted successfully.');
                redirect('admin.php?action=subjects');
            }
        }
    }

    if (isset($_GET['edit_id'])) {
        $editSubject = subjectFindById($conn, (int)$_GET['edit_id']);
    }

    $subjects = subjectGetAll($conn);
    adminView('subjects', compact('pageTitle', 'subjects', 'errors', 'editSubject'));
}

function adminCourses($conn)
{
    $pageTitle = 'Courses';
    $subject_id = isset($_GET['subject_id']) ? (int)$_GET['subject_id'] : 0;
    $instructor_id = isset($_GET['instructor_id']) ? (int)$_GET['instructor_id'] : 0;
    $status = isset($_GET['status']) ? trim($_GET['status']) : '';

    if (!adminAllowed($status, array('', 'draft', 'active', 'archived'))) {
        $status = '';
    }

    $courses = courseGetAll($conn, $subject_id, $instructor_id, $status);
    $subjects = courseGetFilterSubjects($conn);
    $instructors = courseGetFilterInstructors($conn);
    adminView('courses', compact('pageTitle', 'courses', 'subjects', 'instructors', 'subject_id', 'instructor_id', 'status'));
}

function adminQuizzes($conn)
{
    $pageTitle = 'Quizzes';
    $course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;
    $status = isset($_GET['status']) ? trim($_GET['status']) : '';
    $quiz_type = isset($_GET['quiz_type']) ? trim($_GET['quiz_type']) : '';

    if (!adminAllowed($status, array('', 'draft', 'published'))) {
        $status = '';
    }
    if (!adminAllowed($quiz_type, array('', 'graded', 'practice'))) {
        $quiz_type = '';
    }

    $quizzes = quizGetAll($conn, $course_id, $status, $quiz_type);
    $courses = courseGetSimpleList($conn);
    adminView('quizzes', compact('pageTitle', 'quizzes', 'courses', 'course_id', 'status', 'quiz_type'));
}

function adminIntegrityReports($conn)
{
    $pageTitle = 'Integrity Reports';
    $errors = array();

    if (isPost()) {
        $report_id = isset($_POST['report_id']) ? (int)$_POST['report_id'] : 0;
        $new_status = adminClean('status');
        $resolution_notes = adminClean('resolution_notes');

        if (!adminAllowed($new_status, array('resolved', 'escalated'))) {
            $errors[] = 'Invalid report status selected.';
        }
        if ($resolution_notes === '') {
            $errors[] = 'Resolution notes are required.';
        }

        if (count($errors) === 0) {
            integrityReportUpdateStatus($conn, $report_id, $new_status, $resolution_notes, currentAdminId());
            logAdminAction($conn, 'update_integrity_report', 'integrity_reports', $report_id, 'Changed report status to ' . $new_status . '.');
            setFlashMessage('success', 'Integrity report updated successfully.');
            redirect('admin.php?action=integrity_reports&report_id=' . $report_id);
        }
    }

    $status = isset($_GET['status']) ? trim($_GET['status']) : '';
    $severity = isset($_GET['severity']) ? trim($_GET['severity']) : '';

    if (!adminAllowed($status, array('', 'open', 'under_review', 'resolved', 'escalated', 'dismissed'))) {
        $status = '';
    }
    if (!adminAllowed($severity, array('', 'low', 'medium', 'high', 'critical'))) {
        $severity = '';
    }

    $reports = integrityReportGetAll($conn, $status, $severity);
    $selectedReport = null;

    if (isset($_GET['report_id'])) {
        $selectedReport = integrityReportFindById($conn, (int)$_GET['report_id']);
    }

    adminView('integrity_reports', compact('pageTitle', 'reports', 'selectedReport', 'status', 'severity', 'errors'));
}

function adminPlatformAnnouncements($conn)
{
    $pageTitle = 'Platform Announcements';
    $errors = array();
    $editAnnouncement = null;

    if (isPost()) {
        $announcement_action = adminClean('announcement_action');
        $announcement_id = isset($_POST['announcement_id']) ? (int)$_POST['announcement_id'] : 0;

        if ($announcement_action === 'archive') {
            platformAnnouncementArchive($conn, $announcement_id);
            logAdminAction($conn, 'archive_platform_announcement', 'platform_announcements', $announcement_id, 'Archived platform announcement.');
            setFlashMessage('success', 'Announcement archived successfully.');
            redirect('admin.php?action=platform_announcements');
        }

        $title = adminClean('title');
        $body = adminClean('body');
        $audience = adminClean('audience');
        $status = adminClean('status');
        $publish_from = adminClean('publish_from');
        $publish_until = adminClean('publish_until');

        if ($title === '') {
            $errors[] = 'Announcement title is required.';
        }
        if ($body === '') {
            $errors[] = 'Announcement body is required.';
        }
        if (!adminAllowed($audience, array('all', 'students', 'instructors', 'tas'))) {
            $errors[] = 'Invalid audience selected.';
        }
        if (!adminAllowed($status, array('draft', 'published', 'archived'))) {
            $errors[] = 'Invalid announcement status selected.';
        }

        if ($publish_from === '') {
            $publish_from = null;
        }
        if ($publish_until === '') {
            $publish_until = null;
        }

        if (count($errors) === 0) {
            if ($announcement_action === 'create') {
                $new_id = platformAnnouncementCreate($conn, currentAdminId(), $title, $body, $audience, $status, $publish_from, $publish_until);
                logAdminAction($conn, 'create_platform_announcement', 'platform_announcements', (int)$new_id, 'Created platform announcement.');
                setFlashMessage('success', 'Announcement created successfully.');
                redirect('admin.php?action=platform_announcements');
            }

            if ($announcement_action === 'edit') {
                platformAnnouncementUpdate($conn, $announcement_id, $title, $body, $audience, $status, $publish_from, $publish_until);
                logAdminAction($conn, 'update_platform_announcement', 'platform_announcements', $announcement_id, 'Updated platform announcement.');
                setFlashMessage('success', 'Announcement updated successfully.');
                redirect('admin.php?action=platform_announcements');
            }
        }
    }

    if (isset($_GET['edit_id'])) {
        $editAnnouncement = platformAnnouncementFindById($conn, (int)$_GET['edit_id']);
    }

    $announcements = platformAnnouncementGetAll($conn);
    adminView('platform_announcements', compact('pageTitle', 'announcements', 'editAnnouncement', 'errors'));
}

function adminStudentReport($conn)
{
    $pageTitle = 'Student Academic Report';
    $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
    $student_id = isset($_GET['student_id']) ? (int)$_GET['student_id'] : 0;
    $students = array();
    $student = null;
    $enrollments = array();
    $summary = null;
    $attempts = array();

    if ($keyword !== '') {
        $students = userGetStudentsByKeyword($conn, $keyword);
    }

    if ($student_id > 0) {
        $student = userFindById($conn, $student_id);

        if ($student && $student['role'] === 'student') {
            $enrollments = adminGetStudentEnrollments($conn, $student_id);
            $summary = adminGetStudentAttemptSummary($conn, $student_id);
            $attempts = adminGetStudentAttempts($conn, $student_id);
        } else {
            $student = null;
            setFlashMessage('error', 'Student not found.');
        }
    }

    adminView('student_report', compact('pageTitle', 'keyword', 'students', 'student', 'enrollments', 'summary', 'attempts'));
}

function adminInstitutionalReport($conn)
{
    $pageTitle = 'Institutional Report';
    $totals = adminGetInstitutionalTotals($conn);
    $passRates = adminGetPassRatesPerSubject($conn);
    $enrollments = adminGetEnrollmentsPerSubject($conn);
    $activeInstructors = adminGetMostActiveInstructors($conn);
    adminView('institutional_report', compact('pageTitle', 'totals', 'passRates', 'enrollments', 'activeInstructors'));
}

function adminSettings($conn)
{
    $pageTitle = 'Platform Settings';
    $errors = array();

    if (isPost()) {
        $setting_id = isset($_POST['setting_id']) ? (int)$_POST['setting_id'] : 0;
        $setting_value = adminClean('setting_value');
        $setting = platformSettingFindById($conn, $setting_id);

        if (!$setting) {
            $errors[] = 'Setting not found.';
        } elseif ($setting_value === '') {
            $errors[] = 'Setting value is required.';
        } elseif ($setting['setting_type'] === 'integer' && filter_var($setting_value, FILTER_VALIDATE_INT) === false) {
            $errors[] = 'This setting must be an integer.';
        } elseif ($setting['setting_type'] === 'decimal' && !is_numeric($setting_value)) {
            $errors[] = 'This setting must be a decimal number.';
        } elseif ($setting['setting_type'] === 'boolean' && !adminAllowed(strtolower($setting_value), array('true', 'false', '1', '0'))) {
            $errors[] = 'This setting must be true, false, 1, or 0.';
        } elseif ($setting['setting_type'] === 'json') {
            json_decode($setting_value, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $errors[] = 'This setting must contain valid JSON.';
            }
        }

        if (count($errors) === 0) {
            platformSettingUpdate($conn, $setting_id, $setting_value, currentAdminId());
            logAdminAction($conn, 'update_setting', 'platform_settings', $setting_id, 'Updated setting ' . $setting['setting_key'] . '.');
            setFlashMessage('success', 'Setting updated successfully.');
            redirect('admin.php?action=settings');
        }
    }

    $settings = platformSettingGetAll($conn);
    adminView('settings', compact('pageTitle', 'settings', 'errors'));
}

function adminAuditLogs($conn)
{
    $pageTitle = 'Audit Logs';
    $action_filter = isset($_GET['action_filter']) ? trim($_GET['action_filter']) : '';
    $admin_id = isset($_GET['admin_id']) ? (int)$_GET['admin_id'] : 0;
    $logs = auditLogGetAll($conn, $action_filter, $admin_id);
    $actions = auditLogGetActions($conn);
    $admins = auditLogGetAdmins($conn);
    adminView('audit_logs', compact('pageTitle', 'logs', 'actions', 'admins', 'action_filter', 'admin_id'));
}
