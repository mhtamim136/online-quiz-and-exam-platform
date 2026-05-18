<?php
$currentAction = isset($_GET['action']) ? trim($_GET['action']) : 'dashboard';

function instructorSidebarIsActive($currentAction, $actions)
{
    return in_array($currentAction, $actions, true) ? 'active' : '';
}
?>
<aside class="instructor-sidebar">
    <div class="instructor-sidebar-inner">
    <div class="instructor-brand">Instructor Panel</div>
    <nav class="instructor-nav">
        <a class="<?php echo instructorSidebarIsActive($currentAction, array('dashboard')); ?>" href="instructor.php?action=dashboard">Dashboard</a>
        <a class="<?php echo instructorSidebarIsActive($currentAction, array('courses', 'create_course', 'edit_course', 'course_detail', 'enrolled_students', 'enrollment_requests', 'assign_ta', 'quiz_list', 'create_quiz', 'edit_quiz', 'toggle_quiz', 'quiz_questions', 'add_question', 'edit_question', 'question_bank', 'quiz_attempts', 'quiz_analytics', 'announcements', 'create_announcement', 'edit_announcement', 'materials', 'upload_material', 'edit_material', 'qa_board', 'qa_detail')); ?>" href="instructor.php?action=courses">Courses</a>
        <a class="<?php echo instructorSidebarIsActive($currentAction, array('archived_courses')); ?>" href="instructor.php?action=archived_courses">Archived</a>
        <a class="<?php echo instructorSidebarIsActive($currentAction, array('reports', 'performance_report')); ?>" href="instructor.php?action=reports">Reports</a>
    </nav>
    <a class="instructor-sidebar-profile <?php echo instructorSidebarIsActive($currentAction, array('profile')); ?>" href="instructor.php?action=profile">Profile</a>
    </div>
</aside>
