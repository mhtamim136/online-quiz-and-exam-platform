<?php
require_once __DIR__ . '/config/instructor_session.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/instructor_helpers.php';
require_once __DIR__ . '/models/InstructorAuthModel.php';
require_once __DIR__ . '/controllers/InstructorAuthController.php';

instructorAuthHandleLogin();
