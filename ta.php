<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/helpers.php';
require_once __DIR__ . '/config/ta_helpers.php';
require_once __DIR__ . '/controllers/TaController.php';

taRequireRole();

$action = isset($_GET['action']) ? trim($_GET['action']) : 'dashboard';
taRoute($conn, $action);
