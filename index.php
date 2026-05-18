<?php
require_once __DIR__ . '/config/helpers.php';

if (!isset($_SESSION['user_id'])) {
    redirect('login.php');
}

if ($_SESSION['role'] === 'admin') {
    redirect('admin.php?action=dashboard');
}

else if ($_SESSION['role'] === 'student') {
    redirect('student.php');
} else if ($_SESSION['role'] === 'instructor') {
    redirect('instructor.php');
} else if ($_SESSION['role'] === 'ta') {
    redirect('ta.php');
} else {
    redirect('login.php');
}

?>
