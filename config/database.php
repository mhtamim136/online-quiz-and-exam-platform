<?php
$db_host = 'localhost';
$db_user = 'root';
$db_password = '';
$db_name = 'online_quiz_exam_platform';

$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);
$studentConn = $conn;
$taConn = $conn;
$adminConn = $conn;

if (!$conn) {
    die('Database connection failed. Please check XAMPP MySQL and database settings.');
}

mysqli_set_charset($conn, 'utf8mb4');
