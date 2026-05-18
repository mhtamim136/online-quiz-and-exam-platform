<?php
function instructorQuizAttemptsController()
{
    $quizId = instructorInt('quiz_id');
    $quiz = instructorQuizFind($quizId, instructorCurrentId());
    if (!$quiz) {
        instructorRedirect('instructor.php?action=courses');
    }
    $attempts = instructorAnalyticsAttempts($quizId, instructorCurrentId());
    instructorRender('instructor_quiz_attempts', array('pageTitle' => 'Quiz Attempts', 'quiz' => $quiz, 'attempts' => $attempts));
}

function instructorQuizAnalyticsController()
{
    $quizId = instructorInt('quiz_id');
    $quiz = instructorQuizFind($quizId, instructorCurrentId());
    if (!$quiz) {
        instructorRedirect('instructor.php?action=courses');
    }
    $summary = instructorAnalyticsSummary($quizId, instructorCurrentId());
    $attempts = instructorAnalyticsAttempts($quizId, instructorCurrentId());
    instructorRender('instructor_quiz_analytics', array('pageTitle' => 'Quiz Analytics', 'quiz' => $quiz, 'summary' => $summary, 'attempts' => $attempts));
}

function instructorReportsController()
{
    $reports = instructorReportsByCourse(instructorCurrentId());
    instructorRender('instructor_reports', array('pageTitle' => 'Reports', 'reports' => $reports));
}

function instructorPerformanceReportController()
{
    instructorReportsController();
}
