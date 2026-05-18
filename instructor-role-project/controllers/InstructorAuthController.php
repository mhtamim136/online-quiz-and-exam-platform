<?php
function instructorAuthHandleLogin()
{
    $errors = array();
    $email = '';

    if (instructorCurrentId() > 0 && isset($_SESSION['instructor_role']) && $_SESSION['instructor_role'] === 'instructor') {
        instructorRedirect('instructor.php?action=dashboard');
    }

    if (instructorIsPost()) {
        $email = instructorPost('email');
        $password = instructorPost('password');

        if ($email === '') {
            $errors[] = 'Email is required.';
        }
        if ($password === '') {
            $errors[] = 'Password is required.';
        }

        if (count($errors) === 0) {
            $user = instructorAuthFindByEmail($email);
            if ($user && password_verify($password, $user['password_hash']) && $user['role'] === 'instructor' && $user['approval_status'] === 'approved' && (int) $user['is_active'] === 1) {
                session_regenerate_id(true);
                $_SESSION['instructor_user_id'] = (int) $user['id'];
                $_SESSION['instructor_name'] = $user['name'];
                $_SESSION['instructor_email'] = $user['email'];
                $_SESSION['instructor_role'] = $user['role'];
                instructorRedirect('instructor.php?action=dashboard');
            }
            $errors[] = 'Invalid instructor login details.';
        }
    }

    include __DIR__ . '/../views/instructor_auth/instructor_login.php';
}
