<?php
function instructorDashboardController()
{
    $stats = instructorDashboardStats(instructorCurrentId());
    $recentAttempts = instructorDashboardRecentAttempts(instructorCurrentId());
    instructorRender('instructor_dashboard', array('pageTitle' => 'Instructor Dashboard', 'stats' => $stats, 'recentAttempts' => $recentAttempts));
}
