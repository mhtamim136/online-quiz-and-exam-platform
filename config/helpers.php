<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function redirect($url)
{
    header('Location: ' . $url);
    exit;
}

function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function isPost()
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

function setFlashMessage($type, $message)
{
    $_SESSION['flash_message'] = array(
        'type' => $type,
        'message' => $message
    );
}

function getFlashMessage()
{
    if (!isset($_SESSION['flash_message'])) {
        return null;
    }

    $message = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']);
    return $message;
}

function requireLogin()
{
    if (!isset($_SESSION['user_id'])) {
        redirect('login.php');
    }
}

function requireAdmin()
{
    requireLogin();

    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo 'Access denied. This page is only for Admin users.';
        exit;
    }
}

function dbBindParams($stmt, $types, $params)
{
    if ($types === '' || count($params) === 0) {
        return;
    }

    $bind_values = array($types);
    for ($i = 0; $i < count($params); $i++) {
        $bind_values[] = &$params[$i];
    }

    call_user_func_array('mysqli_stmt_bind_param', array_merge(array($stmt), $bind_values));
}

function dbSelectAll($conn, $sql, $types = '', $params = array())
{
    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        die('Database query prepare failed.');
    }

    dbBindParams($stmt, $types, $params);

    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        die('Database query execute failed.');
    }

    $result = mysqli_stmt_get_result($stmt);
    $rows = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    mysqli_stmt_close($stmt);
    return $rows;
}

function dbSelectOne($conn, $sql, $types = '', $params = array())
{
    $rows = dbSelectAll($conn, $sql, $types, $params);

    if (count($rows) === 0) {
        return null;
    }

    return $rows[0];
}

function dbExecute($conn, $sql, $types = '', $params = array())
{
    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        die('Database query prepare failed.');
    }

    dbBindParams($stmt, $types, $params);

    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        return false;
    }

    $insert_id = mysqli_insert_id($conn);
    mysqli_stmt_close($stmt);

    if ($insert_id > 0) {
        return $insert_id;
    }

    return true;
}

function logAdminAction($conn, $action, $entity_type, $entity_id, $details)
{
    $admin_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
    $ip_address = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
    $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? substr($_SERVER['HTTP_USER_AGENT'], 0, 255) : '';

    $sql = "INSERT INTO audit_logs (admin_id, action, entity_type, entity_id, ip_address, user_agent, details)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    return dbExecute(
        $conn,
        $sql,
        'ississs',
        array($admin_id, $action, $entity_type, $entity_id, $ip_address, $user_agent, $details)
    );
}

function currentAdminId()
{
    if (!isset($_SESSION['user_id'])) {
        return 0;
    }

    return (int)$_SESSION['user_id'];
}
