<?php
$currentAction = isset($_GET['action']) ? trim($_GET['action']) : 'dashboard';
$currentCourseId = 0;

if (isset($_GET['course_id'])) {
    $currentCourseId = (int)$_GET['course_id'];
} elseif ($currentAction === 'course_detail' && isset($_GET['id'])) {
    $currentCourseId = (int)$_GET['id'];
}

function taSidebarIsActive($currentAction, $actions)
{
    return in_array($currentAction, $actions, true) ? 'active' : '';
}
?>
<aside class="ta-sidebar">
    <div class="ta-sidebar-inner">
        <div class="ta-sidebar-title">
            <strong>TA Workspace</strong>
            <span>Course support tools</span>
        </div>

        <nav class="ta-nav">
            <a class="<?php echo taSidebarIsActive($currentAction, array('dashboard')); ?>" href="ta.php?action=dashboard">Dashboard</a>
            <a class="<?php echo taSidebarIsActive($currentAction, array('assigned_courses', 'course_detail', 'enrolled_students', 'course_summary_report')); ?>" href="ta.php?action=assigned_courses">Assigned Courses</a>
            <a class="<?php echo taSidebarIsActive($currentAction, array('quizzes', 'create_practice_quiz', 'edit_practice_quiz', 'questions', 'add_question', 'edit_question', 'question_bank')); ?>" href="ta.php?action=quizzes">Quizzes</a>
            <a class="<?php echo taSidebarIsActive($currentAction, array('attempt_results')); ?>" href="ta.php?action=attempt_results">Attempt Results</a>
            <a class="<?php echo taSidebarIsActive($currentAction, array('at_risk_students')); ?>" href="ta.php?action=at_risk_students">At-Risk Students</a>
            <a class="<?php echo taSidebarIsActive($currentAction, array('qa_board', 'qa_detail')); ?>" href="ta.php?action=qa_board">Q&amp;A Board</a>
            <a class="<?php echo taSidebarIsActive($currentAction, array('doubt_sessions', 'create_doubt_session', 'edit_doubt_session', 'doubt_session_bookings')); ?>" href="ta.php?action=doubt_sessions">Doubt Sessions</a>

            <?php if ($currentCourseId > 0): ?>
                <div class="ta-nav-label">Current Course</div>
                <a class="<?php echo taSidebarIsActive($currentAction, array('announcements')); ?>" href="ta.php?action=announcements&course_id=<?php echo (int)$currentCourseId; ?>">Announcements</a>
                <a class="<?php echo taSidebarIsActive($currentAction, array('materials')); ?>" href="ta.php?action=materials&course_id=<?php echo (int)$currentCourseId; ?>">Materials</a>
            <?php endif; ?>
        </nav>

        <a class="ta-sidebar-profile <?php echo taSidebarIsActive($currentAction, array('profile')); ?>" href="ta.php?action=profile">Profile</a>
    </div>
</aside>
