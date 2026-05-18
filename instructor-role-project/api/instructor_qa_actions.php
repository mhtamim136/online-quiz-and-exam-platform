<?php
require_once __DIR__ . '/../config/instructor_session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/instructor_helpers.php';

header('Content-Type: application/json');
if (instructorCurrentId() <= 0 || $_SESSION['instructor_role'] !== 'instructor') {
    echo json_encode(array('success' => false, 'message' => 'Login required.'));
    exit;
}

echo json_encode(array('success' => true, 'message' => 'Q&A API ready.'));
