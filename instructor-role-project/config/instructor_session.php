<?php
if (session_status() === PHP_SESSION_NONE) {
    session_name('INSTRUCTOR_ROLE_SESSION');
    session_start();
}
