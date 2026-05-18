<aside class="student-sidebar">
    <div class="student-sidebar-inner">
    <nav class="student-nav">
        <a class="<?php echo $studentCurrentAction === 'dashboard' ? 'active' : ''; ?>" href="student.php?action=dashboard">Dashboard</a>
        <a class="<?php echo $studentCurrentAction === 'courses' ? 'active' : ''; ?>" href="student.php?action=courses">Browse Courses</a>
        <a class="<?php echo $studentCurrentAction === 'quiz_list' ? 'active' : ''; ?>" href="student.php?action=quiz_list">Quizzes</a>
        <a class="<?php echo $studentCurrentAction === 'quiz_history' ? 'active' : ''; ?>" href="student.php?action=quiz_history">Quiz History</a>
        <a class="<?php echo $studentCurrentAction === 'leaderboard' ? 'active' : ''; ?>" href="student.php?action=leaderboard">Leaderboard</a>
        <a class="<?php echo $studentCurrentAction === 'performance' ? 'active' : ''; ?>" href="student.php?action=performance">Performance</a>
        <a class="<?php echo $studentCurrentAction === 'doubt_sessions' ? 'active' : ''; ?>" href="student.php?action=doubt_sessions">Doubt Sessions</a>
        <a class="<?php echo $studentCurrentAction === 'my_bookings' ? 'active' : ''; ?>" href="student.php?action=my_bookings">My Bookings</a>
    </nav>
    <a class="student-sidebar-profile <?php echo $studentCurrentAction === 'profile' ? 'active' : ''; ?>" href="student.php?action=profile">Profile</a>
    </div>
</aside>
<main class="student-main">
    <?php $studentFlash = studentGetFlash(); ?>
    <?php if ($studentFlash): ?>
        <div class="student-alert <?php echo studentE($studentFlash['type']); ?>">
            <?php echo studentE($studentFlash['message']); ?>
        </div>
    <?php endif; ?>
