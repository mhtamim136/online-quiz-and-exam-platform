<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../models/UserModel.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(array('success' => false, 'message' => 'Access denied.', 'students' => array()));
    exit;
}

$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$students = $keyword === '' ? array() : userGetStudentsByKeyword($conn, $keyword);

echo json_encode(array(
    'success' => true,
    'students' => $students
));
?>
