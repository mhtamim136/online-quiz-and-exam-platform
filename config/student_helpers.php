<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function studentRedirect($url)
{
    header('Location: ' . $url);
    exit;
}

function studentE($value)
{
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

function studentIsPost()
{
    return isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST';
}

function studentSetFlash($type, $message)
{
    $_SESSION['student_flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

function studentGetFlash()
{
    if (!isset($_SESSION['student_flash'])) {
        return null;
    }

    $flash = $_SESSION['student_flash'];
    unset($_SESSION['student_flash']);
    return $flash;
}

function studentRequireLogin()
{
    if (!isset($_SESSION['student_user_id'])) {
        studentSetFlash('error', 'Please log in to access the student area.');
        studentRedirect('student_login.php');
    }

    studentRequireRole('student');
}

function studentRequireRole($role)
{
    if (!isset($_SESSION['student_role']) || $_SESSION['student_role'] !== $role) {
        studentSetFlash('error', 'Access denied. This page is only for students.');
        studentRedirect('student_login.php');
    }
}

function studentCurrentId()
{
    return isset($_SESSION['student_user_id']) ? (int)$_SESSION['student_user_id'] : 0;
}

function studentBindParams($stmt, $types, $params)
{
    if ($types === '' || empty($params)) {
        return;
    }

    $bindValues = [$stmt, $types];

    foreach ($params as $key => $value) {
        $bindValues[] = &$params[$key];
    }

    call_user_func_array('mysqli_stmt_bind_param', $bindValues);
}

function studentCleanText($value)
{
    return trim((string)($value ?? ''));
}

function studentValidPositiveId($value)
{
    return isset($value) && ctype_digit((string)$value) && (int)$value > 0;
}
?>
