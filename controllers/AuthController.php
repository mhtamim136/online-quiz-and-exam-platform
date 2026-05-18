<?php
require_once __DIR__ . '/../models/UserModel.php';

function authShowLogin($errors = array(), $old = array())
{
    if (isset($_SESSION['user_id'])) {
        authRedirectByRole($_SESSION['role']);
    }

    require __DIR__ . '/../views/auth/login.php';
}

function authShowRegister($errors = array(), $old = array(), $fieldErrors = array())
{
    if (isset($_SESSION['user_id'])) {
        authRedirectByRole($_SESSION['role']);
    }

    if (empty($old)) {
        $old = array(
            'name' => '',
            'username' => '',
            'email' => '',
            'phone' => '',
            'role' => 'student',
            'student_id' => '',
            'program' => '',
            'department' => '',
            'bio' => '',
            'password' => '',
            'confirm_password' => ''
        );
    }

    require __DIR__ . '/../views/auth/register.php';
}

function authHandleLogin($conn)
{
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $errors = array();
    $old = array('email' => $email);

    if ($email === '') {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if ($password === '') {
        $errors[] = 'Password is required.';
    }

    if (count($errors) > 0) {
        authShowLogin($errors, $old);
        return;
    }

    $user = userFindByEmail($conn, $email);

    if (!$user) {
        $errors[] = 'No user account was found for this email.';
        authShowLogin($errors, $old);
        return;
    }

    if ((int)$user['is_active'] !== 1) {
        $errors[] = 'This account is inactive. Please contact the platform admin.';
        authShowLogin($errors, $old);
        return;
    }

    if (!password_verify($password, $user['password_hash'])) {
        $errors[] = 'Incorrect password.';
        authShowLogin($errors, $old);
        return;
    }

    $_SESSION['user_id'] = (int)$user['id'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['role'] = $user['role'];

    authRedirectByRole($user['role']);
}

function authRedirectByRole($role)
{
    $targets = array(
        'admin' => 'admin.php?action=dashboard',
        'student' => 'student.php',
        'instructor' => 'instructor.php',
        'ta' => 'ta.php?action=dashboard'
    );

    if (!isset($targets[$role])) {
        redirect('index.php');
    }

    $target = $targets[$role];
    $file = explode('?', $target);
    $file_path = __DIR__ . '/../' . $file[0];

    if (file_exists($file_path)) {
        redirect($target);
    }

    redirect('index.php');
}

function authHandleRegister($conn)
{
    $old = array(
        'name' => isset($_POST['name']) ? trim($_POST['name']) : '',
        'username' => isset($_POST['username']) ? trim($_POST['username']) : '',
        'email' => isset($_POST['email']) ? trim($_POST['email']) : '',
        'phone' => isset($_POST['phone']) ? trim($_POST['phone']) : '',
        'role' => isset($_POST['role']) ? trim($_POST['role']) : 'student',
        'student_id' => isset($_POST['student_id']) ? trim($_POST['student_id']) : '',
        'program' => isset($_POST['program']) ? trim($_POST['program']) : '',
        'department' => isset($_POST['department']) ? trim($_POST['department']) : '',
        'bio' => isset($_POST['bio']) ? trim($_POST['bio']) : '',
        'password' => isset($_POST['password']) ? trim($_POST['password']) : '',
        'confirm_password' => isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : ''
    );
    $password = $old['password'];
    $confirm_password = $old['confirm_password'];
    $errors = array();
    $fieldErrors = array();

    if ($old['name'] === '') {
        $fieldErrors['name'] = 'Full name is required.';
    }
    if ($old['username'] === '') {
        $fieldErrors['username'] = 'Username is required.';
    } elseif (userUsernameExists($conn, $old['username'])) {
        $fieldErrors['username'] = 'Username already exists.';
    }
    if ($old['email'] === '') {
        $fieldErrors['email'] = 'Email is required.';
    } elseif (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
        $fieldErrors['email'] = 'Please enter a valid email address.';
    } elseif (userEmailExists($conn, $old['email'])) {
        $fieldErrors['email'] = 'Email already exists.';
    }
    if ($old['phone'] === '') {
        $fieldErrors['phone'] = 'Phone is required.';
    }
    if (!in_array($old['role'], array('student', 'instructor'), true)) {
        $errors[] = 'Please select a valid account type.';
    }
    if ($old['role'] === 'student') {
        if ($old['student_id'] === '') {
            $fieldErrors['student_id'] = 'Student ID is required for student registration.';
        } elseif (userStudentIdExists($conn, $old['student_id'])) {
            $fieldErrors['student_id'] = 'Student ID already exists. For inquiries: inquiry.info136@gmail.com';
        }
        if ($old['program'] === '') {
            $fieldErrors['program'] = 'Program is required for student registration.';
        }
        if ($old['department'] === '') {
            $fieldErrors['department'] = 'Department is required for student registration.';
        }
    }
    if ($old['role'] === 'instructor' && $old['department'] === '') {
        $fieldErrors['department'] = 'Department is required for instructor registration.';
    }
    if ($password === '') {
        $fieldErrors['password'] = 'Password is required.';
    } elseif (strlen($password) < 4) {
        $fieldErrors['password'] = 'Password must be at least 4 characters.';
    }
    if ($confirm_password === '') {
        $fieldErrors['confirm_password'] = 'Confirm password is required.';
    }
    if ($password !== '' && $confirm_password !== '' && $password !== $confirm_password) {
        $fieldErrors['confirm_password'] = 'Passwords do not match.';
    }

    if (count($errors) > 0 || count($fieldErrors) > 0) {
        authShowRegister($errors, $old, $fieldErrors);
        return;
    }

    $old['password_hash'] = password_hash($password, PASSWORD_DEFAULT);

    if ($old['role'] === 'student') {
        $old['approval_status'] = 'approved';
        $old['is_active'] = 1;
        $old['bio'] = '';
    } else {
        $old['approval_status'] = 'pending';
        $old['is_active'] = 0;
        $old['student_id'] = '';
        $old['program'] = '';
    }

    userCreateRegisteredUser($conn, $old);

    if ($old['role'] === 'instructor') {
        setFlashMessage('success', 'Registration submitted. An admin must approve your instructor account before login.');
    } else {
        setFlashMessage('success', 'Registration successful. You can now log in.');
    }

    redirect('login.php');
}
