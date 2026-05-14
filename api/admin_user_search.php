<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../models/UserModel.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(array('success' => false, 'message' => 'Access denied.'));
    exit;
}

$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$role = isset($_GET['role']) ? trim($_GET['role']) : '';
$active = isset($_GET['active']) ? trim($_GET['active']) : '';

if (!in_array($role, array('', 'student', 'instructor', 'ta', 'admin'), true)) {
    $role = '';
}

if (!in_array($active, array('', '0', '1'), true)) {
    $active = '';
}

$users = userSearchForAjax($conn, $keyword, $role, $active);

echo json_encode(array(
    'success' => true,
    'users' => $users
));
