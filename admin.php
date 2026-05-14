<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/helpers.php';
require_once __DIR__ . '/controllers/AdminController.php';

requireAdmin();

$action = isset($_GET['action']) ? trim($_GET['action']) : 'dashboard';
adminRoute($conn, $action);
