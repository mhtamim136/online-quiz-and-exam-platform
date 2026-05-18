<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    $_SESSION['instructor_user_id'] = (int)$_SESSION['user_id'];
}

if (isset($_SESSION['role'])) {
    $_SESSION['instructor_role'] = $_SESSION['role'];
}

if (isset($_SESSION['name'])) {
    $_SESSION['instructor_name'] = $_SESSION['name'];
}
