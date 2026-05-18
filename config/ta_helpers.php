<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function taRedirect($url)
{
    header('Location: ' . $url);
    exit;
}

function taE($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function taIsPost()
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

function taSetFlash($type, $message)
{
    $_SESSION['ta_flash_message'] = array(
        'type' => $type,
        'message' => $message
    );
}

function taGetFlash()
{
    if (!isset($_SESSION['ta_flash_message'])) {
        return null;
    }

    $message = $_SESSION['ta_flash_message'];
    unset($_SESSION['ta_flash_message']);
    return $message;
}

function taRequireLogin()
{
    if (!isset($_SESSION['user_id'])) {
        taRedirect('login.php');
    }
}

function taRequireRole()
{
    taRequireLogin();

    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'ta') {
        http_response_code(403);
        echo 'Access denied. This page is only for Teaching Assistant users.';
        exit;
    }
}

function taCurrentId()
{
    if (!isset($_SESSION['user_id'])) {
        return 0;
    }

    return (int)$_SESSION['user_id'];
}

function taCanAccessCourse($conn, $courseId, $taId)
{
    $sql = "SELECT id FROM course_tas WHERE course_id = ? AND ta_id = ? LIMIT 1";
    $row = dbSelectOne($conn, $sql, 'ii', array((int)$courseId, (int)$taId));
    return $row !== null;
}

function taCanAccessQuiz($conn, $quizId, $taId)
{
    $sql = "SELECT quizzes.id
            FROM quizzes
            INNER JOIN course_tas ON course_tas.course_id = quizzes.course_id
            WHERE quizzes.id = ? AND course_tas.ta_id = ?
            LIMIT 1";
    $row = dbSelectOne($conn, $sql, 'ii', array((int)$quizId, (int)$taId));
    return $row !== null;
}

function taCanAccessDoubtSession($conn, $sessionId, $taId)
{
    $sql = "SELECT id FROM doubt_sessions WHERE id = ? AND ta_id = ? LIMIT 1";
    $row = dbSelectOne($conn, $sql, 'ii', array((int)$sessionId, (int)$taId));
    return $row !== null;
}

function taCanAccessQuestion($conn, $questionId, $taId)
{
    $sql = "SELECT questions.id
            FROM questions
            INNER JOIN quizzes ON quizzes.id = questions.quiz_id
            INNER JOIN course_tas ON course_tas.course_id = quizzes.course_id
            WHERE questions.id = ? AND course_tas.ta_id = ?
            LIMIT 1";
    $row = dbSelectOne($conn, $sql, 'ii', array((int)$questionId, (int)$taId));
    return $row !== null;
}

function taClean($key)
{
    return isset($_REQUEST[$key]) ? trim($_REQUEST[$key]) : '';
}

function taInt($key)
{
    return isset($_REQUEST[$key]) ? (int)$_REQUEST[$key] : 0;
}

function taAllowed($value, $allowed)
{
    return in_array($value, $allowed, true);
}
