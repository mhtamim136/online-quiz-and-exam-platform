<?php
function instructorRedirect($url)
{
    header('Location: ' . $url);
    exit;
}

function instructorE($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function instructorIsPost()
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

function instructorCurrentId()
{
    return isset($_SESSION['instructor_user_id']) ? (int) $_SESSION['instructor_user_id'] : 0;
}

function instructorRequireLogin()
{
    if (instructorCurrentId() <= 0) {
        instructorRedirect('instructor_login.php');
    }
}

function instructorRequireRole($role)
{
    instructorRequireLogin();
    if (!isset($_SESSION['instructor_role']) || $_SESSION['instructor_role'] !== $role) {
        instructorRedirect('instructor_login.php?error=access');
    }
}

function instructorInt($key)
{
    return isset($_GET[$key]) ? max(0, (int) $_GET[$key]) : 0;
}

function instructorPost($key)
{
    return isset($_POST[$key]) ? trim($_POST[$key]) : '';
}

function instructorRender($view, $data = array())
{
    extract($data);
    include __DIR__ . '/../views/partials/instructor_header.php';
    include __DIR__ . '/../views/partials/instructor_sidebar.php';
    include __DIR__ . '/../views/partials/instructor_topbar.php';
    include __DIR__ . '/../views/instructor/' . $view . '.php';
    include __DIR__ . '/../views/partials/instructor_footer.php';
}

function instructorUploadFile($field, $directory)
{
    if (!isset($_FILES[$field]) || $_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
        return '';
    }

    $originalName = basename($_FILES[$field]['name']);
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    $allowed = array('pdf', 'doc', 'docx', 'ppt', 'pptx', 'jpg', 'jpeg', 'png', 'txt');

    if (!in_array($extension, $allowed, true)) {
        return '';
    }

    $safeName = 'instructor_' . time() . '_' . mt_rand(1000, 9999) . '.' . $extension;
    $target = rtrim($directory, '/') . '/' . $safeName;

    if (move_uploaded_file($_FILES[$field]['tmp_name'], $target)) {
        return $safeName;
    }

    return '';
}
