<?php
require_once __DIR__ . '/config/instructor_session.php';

unset($_SESSION['instructor_user_id']);
unset($_SESSION['instructor_name']);
unset($_SESSION['instructor_email']);
unset($_SESSION['instructor_role']);

header('Location: instructor_login.php');
exit;
