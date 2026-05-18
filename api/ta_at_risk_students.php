<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../config/ta_helpers.php';
require_once __DIR__ . '/../models/TaModel.php';
require_once __DIR__ . '/../models/TaRiskModel.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(array('success' => false, 'message' => 'Please log in first.', 'students' => array()));
    exit;
}

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'ta') {
    http_response_code(403);
    echo json_encode(array('success' => false, 'message' => 'Access denied.', 'students' => array()));
    exit;
}

$taId = taCurrentId();
$courseId = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;
$threshold = isset($_GET['threshold']) && is_numeric($_GET['threshold']) ? (float)$_GET['threshold'] : taGetAtRiskThreshold($conn);

if ($threshold < 0 || $threshold > 100) {
    http_response_code(400);
    echo json_encode(array('success' => false, 'message' => 'Threshold must be between 0 and 100.', 'students' => array()));
    exit;
}

if ($courseId > 0 && !taCanAccessCourse($conn, $courseId, $taId)) {
    http_response_code(403);
    echo json_encode(array('success' => false, 'message' => 'You can only access assigned courses.', 'students' => array()));
    exit;
}

$students = taGetAtRiskStudents($conn, $taId, $courseId, $threshold);

echo json_encode(array(
    'success' => true,
    'message' => 'At-risk students loaded.',
    'students' => $students
));
