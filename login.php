<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/helpers.php';
require_once __DIR__ . '/controllers/AuthController.php';

if (isPost()) {
    authHandleLogin($conn);
} else {
    authShowLogin();
}
