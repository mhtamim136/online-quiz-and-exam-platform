<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/student_helpers.php';
require_once __DIR__ . '/../models/StudentModel.php';
require_once __DIR__ . '/../models/StudentQuizModel.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    echo json_encode([
        'success' => false,
        'message' => 'Student login is required.',
        'rows' => []
    ]);
    exit;
}

if (!studentValidPositiveId($_GET['quiz_id'] ?? '')) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid quiz selected.',
        'rows' => []
    ]);
    exit;
}

$quizId = (int)$_GET['quiz_id'];
$studentId = studentCurrentId();
$rows = studentGetLeaderboard($quizId, $studentId);

echo json_encode([
    'success' => true,
    'message' => '',
    'rows' => $rows
]);
?>
