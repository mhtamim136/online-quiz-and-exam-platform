<?php
require_once __DIR__ . '/../models/UserModel.php';

function authShowLogin($errors = array(), $old = array())
{
    if (isset($_SESSION['user_id'])) {
        authRedirectByRole($_SESSION['role']);
    }

    require __DIR__ . '/../views/auth/login.php';
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
    if ($role === 'admin') {
        redirect('admin.php?action=dashboard');
    }

    redirect('index.php');
}
