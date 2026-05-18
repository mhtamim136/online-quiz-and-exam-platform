<?php
require_once __DIR__ . '/../config/instructor_session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/instructor_helpers.php';
require_once __DIR__ . '/../models/InstructorEnrollmentModel.php';

header('Content-Type: application/json');

if (!instructorIsPost()) {
    echo json_encode(array('success' => false, 'message' => 'Invalid request method.'));
    exit;
}

if (instructorCurrentId() <= 0 || !isset($_SESSION['instructor_role']) || $_SESSION['instructor_role'] !== 'instructor') {
    echo json_encode(array('success' => false, 'message' => 'Login required.'));
    exit;
}

$enrollmentId = isset($_POST['enrollment_id']) ? (int) $_POST['enrollment_id'] : 0;
$decision = isset($_POST['decision']) ? $_POST['decision'] : '';

if ($enrollmentId <= 0 || !in_array($decision, array('active', 'dropped'), true)) {
    echo json_encode(array('success' => false, 'message' => 'Invalid enrollment decision.'));
    exit;
}

$updated = instructorEnrollmentUpdateStatus($enrollmentId, instructorCurrentId(), $decision);
echo json_encode(array('success' => $updated >= 0, 'message' => 'Enrollment updated.'));
