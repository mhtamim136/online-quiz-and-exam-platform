<?php
$navItems = array(
    'dashboard' => 'Dashboard',
    'users' => 'Users',
    'create_ta' => 'Create TA',
    'instructor_requests' => 'Instructor Approval',
    'subjects' => 'Subjects',
    'courses' => 'Courses',
    'quizzes' => 'Quizzes',
    'integrity_reports' => 'Integrity Reports',
    'platform_announcements' => 'Announcements',
    'student_report' => 'Student Report',
    'institutional_report' => 'Institutional Report',
    'settings' => 'Settings',
    'audit_logs' => 'Audit Logs'
);
$currentAction = isset($_GET['action']) ? $_GET['action'] : 'dashboard';
?>
<aside class="sidebar">
    <nav>
        <?php foreach ($navItems as $action => $label): ?>
            <a class="<?php echo $currentAction === $action ? 'active' : ''; ?>" href="admin.php?action=<?php echo e($action); ?>">
                <?php echo e($label); ?>
            </a>
        <?php endforeach; ?>
    </nav>
</aside>
