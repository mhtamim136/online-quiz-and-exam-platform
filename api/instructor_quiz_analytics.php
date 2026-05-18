<?php
require_once __DIR__ . '/../config/instructor_session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/instructor_helpers.php';
require_once __DIR__ . '/../models/InstructorAnalyticsModel.php';

header('Content-Type: application/json');
if (instructorCurrentId() <= 0 || !isset($_SESSION['instructor_role']) || $_SESSION['instructor_role'] !== 'instructor') {
    echo json_encode(array('success' => false));
    exit;
}

$quizId = isset($_GET['quiz_id']) ? (int) $_GET['quiz_id'] : 0;
echo json_encode(array('success' => true, 'summary' => instructorAnalyticsSummary($quizId, instructorCurrentId())));
