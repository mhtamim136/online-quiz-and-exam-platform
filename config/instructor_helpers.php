<?php
function instructorRedirect($url)
{
    header('Location: ' . $url);
    exit;
}

function instructorE($value)
{
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

function instructorIsPost()
{
    return isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST';
}

function instructorPost($key)
{
    return isset($_POST[$key]) ? trim((string)$_POST[$key]) : '';
}

function instructorInt($key)
{
    return isset($_REQUEST[$key]) ? (int)$_REQUEST[$key] : 0;
}

function instructorCurrentId()
{
    if (isset($_SESSION['instructor_user_id'])) {
        return (int)$_SESSION['instructor_user_id'];
    }

    return isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
}

function instructorRequireRole($role)
{
    if (!isset($_SESSION['user_id'])) {
        instructorRedirect('login.php');
    }

    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
        instructorRedirect('login.php');
    }
}

function instructorBindParams($stmt, $types, $params)
{
    if ($types === '' || count($params) === 0) {
        return;
    }

    $bindValues = array($stmt, $types);
    foreach ($params as $key => $value) {
        $bindValues[] = &$params[$key];
    }

    call_user_func_array('mysqli_stmt_bind_param', $bindValues);
}

function instructorDbFetchAll($sql, $types = '', $params = array())
{
    global $conn;

    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        die('Database query prepare failed.');
    }

    instructorBindParams($stmt, $types, $params);

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

function instructorDbFetchOne($sql, $types = '', $params = array())
{
    $rows = instructorDbFetchAll($sql, $types, $params);

    if (count($rows) === 0) {
        return null;
    }

    return $rows[0];
}

function instructorDbExecute($sql, $types = '', $params = array())
{
    global $conn;

    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        die('Database query prepare failed.');
    }

    instructorBindParams($stmt, $types, $params);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $success ? 1 : 0;
}

function instructorDbLastId()
{
    global $conn;

    return mysqli_insert_id($conn);
}

function instructorRender($viewName, $data = array())
{
    extract($data);

    include __DIR__ . '/../views/partials/instructor_header.php';
    include __DIR__ . '/../views/partials/instructor_sidebar.php';
    include __DIR__ . '/../views/partials/instructor_topbar.php';
    include __DIR__ . '/../views/instructor/' . $viewName . '.php';
    include __DIR__ . '/../views/partials/instructor_footer.php';
}

function instructorUploadFile($field, $uploadDir)
{
    if (!isset($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
        return '';
    }

    if ($_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
        return '';
    }

    $extension = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
    $allowed = array('jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'txt', 'zip', 'mp4', 'mov', 'webm');

    if (!in_array($extension, $allowed, true)) {
        return '';
    }

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $baseName = pathinfo($_FILES[$field]['name'], PATHINFO_FILENAME);
    $baseName = preg_replace('/[^A-Za-z0-9_.-]/', '_', $baseName);
    $fileName = 'instructor_' . instructorCurrentId() . '_' . time() . '_' . $baseName . '.' . $extension;
    $targetPath = rtrim($uploadDir, DIRECTORY_SEPARATOR . '/\\') . DIRECTORY_SEPARATOR . $fileName;

    if (!move_uploaded_file($_FILES[$field]['tmp_name'], $targetPath)) {
        return '';
    }

    $projectRoot = realpath(__DIR__ . '/..');
    $savedPath = realpath($targetPath);

    if ($projectRoot && $savedPath && strpos($savedPath, $projectRoot) === 0) {
        return str_replace('\\', '/', ltrim(substr($savedPath, strlen($projectRoot)), DIRECTORY_SEPARATOR . '/\\'));
    }

    return str_replace('\\', '/', $targetPath);
}
?>
