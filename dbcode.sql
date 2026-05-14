-- Online Quiz & Exam Platform
-- Paste this full script into phpMyAdmin SQL tab.

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP DATABASE IF EXISTS `online_quiz_exam_platform`;

SET FOREIGN_KEY_CHECKS = 1;

CREATE DATABASE `online_quiz_exam_platform`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `online_quiz_exam_platform`;

-- Sample login credentials:
-- Admin username: tamim
-- Admin email: admin@example.com
-- Admin password: 136
--
-- Student emails: ayesha.student@example.com, rafi.student@example.com, nabila.student@example.com
-- Student password: student123
--
-- Instructor emails: farhan.instructor@example.com, nadia.instructor@example.com
-- Instructor password: instructor123
--
-- TA emails: sami.ta@example.com, jarin.ta@example.com
-- TA password: ta123
--
-- Pending instructor emails: pending.instructor1@example.com, pending.instructor2@example.com
-- Pending instructor password: pending123

CREATE TABLE `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) DEFAULT NULL,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(30) DEFAULT NULL,
  `role` ENUM('student', 'instructor', 'ta', 'admin') NOT NULL,
  `profile_pic` VARCHAR(255) DEFAULT NULL,
  `student_id` VARCHAR(50) DEFAULT NULL,
  `program` VARCHAR(100) DEFAULT NULL,
  `department` VARCHAR(100) DEFAULT NULL,
  `bio` TEXT DEFAULT NULL,
  `approval_status` ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'approved',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_username` (`username`),
  UNIQUE KEY `uq_users_email` (`email`),
  UNIQUE KEY `uq_users_student_id` (`student_id`),
  KEY `idx_users_role` (`role`),
  KEY `idx_users_active` (`is_active`),
  KEY `idx_users_approval_status` (`approval_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `subjects` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_subjects_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `courses` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `instructor_id` INT UNSIGNED NOT NULL,
  `subject_id` INT UNSIGNED NOT NULL,
  `title` VARCHAR(150) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `enrollment_type` ENUM('open', 'approval') NOT NULL DEFAULT 'open',
  `max_students` INT UNSIGNED NOT NULL DEFAULT 50,
  `status` ENUM('draft', 'active', 'archived') NOT NULL DEFAULT 'draft',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_courses_instructor` (`instructor_id`),
  KEY `idx_courses_subject` (`subject_id`),
  KEY `idx_courses_status` (`status`),
  CONSTRAINT `fk_courses_instructor`
    FOREIGN KEY (`instructor_id`) REFERENCES `users` (`id`)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT `fk_courses_subject`
    FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `course_tas` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `course_id` INT UNSIGNED NOT NULL,
  `ta_id` INT UNSIGNED NOT NULL,
  `assigned_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_course_ta` (`course_id`, `ta_id`),
  KEY `idx_course_tas_ta` (`ta_id`),
  CONSTRAINT `fk_course_tas_course`
    FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `fk_course_tas_ta`
    FOREIGN KEY (`ta_id`) REFERENCES `users` (`id`)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `enrollments` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` INT UNSIGNED NOT NULL,
  `course_id` INT UNSIGNED NOT NULL,
  `status` ENUM('pending', 'active', 'dropped') NOT NULL DEFAULT 'pending',
  `enrolled_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_enrollment_student_course` (`student_id`, `course_id`),
  KEY `idx_enrollments_course` (`course_id`),
  KEY `idx_enrollments_status` (`status`),
  CONSTRAINT `fk_enrollments_student`
    FOREIGN KEY (`student_id`) REFERENCES `users` (`id`)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT `fk_enrollments_course`
    FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `quizzes` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `course_id` INT UNSIGNED NOT NULL,
  `created_by` INT UNSIGNED NOT NULL,
  `title` VARCHAR(150) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `time_limit_minutes` INT UNSIGNED NOT NULL DEFAULT 30,
  `total_marks` DECIMAL(8,2) NOT NULL DEFAULT 0.00,
  `pass_mark` DECIMAL(8,2) NOT NULL DEFAULT 0.00,
  `quiz_type` ENUM('graded', 'practice') NOT NULL DEFAULT 'graded',
  `status` ENUM('draft', 'published') NOT NULL DEFAULT 'draft',
  `available_from` DATETIME DEFAULT NULL,
  `available_until` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_quizzes_course` (`course_id`),
  KEY `idx_quizzes_created_by` (`created_by`),
  KEY `idx_quizzes_status_type` (`status`, `quiz_type`),
  KEY `idx_quizzes_available` (`available_from`, `available_until`),
  CONSTRAINT `fk_quizzes_course`
    FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `fk_quizzes_created_by`
    FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `questions` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `quiz_id` INT UNSIGNED NOT NULL,
  `question_text` TEXT NOT NULL,
  `marks` DECIMAL(8,2) NOT NULL DEFAULT 1.00,
  `order_index` INT UNSIGNED NOT NULL DEFAULT 1,
  `created_by` INT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_questions_quiz_order` (`quiz_id`, `order_index`),
  KEY `idx_questions_created_by` (`created_by`),
  CONSTRAINT `fk_questions_quiz`
    FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `fk_questions_created_by`
    FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `options` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `question_id` INT UNSIGNED NOT NULL,
  `option_text` TEXT NOT NULL,
  `is_correct` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_options_question` (`question_id`),
  CONSTRAINT `fk_options_question`
    FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `attempts` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `quiz_id` INT UNSIGNED NOT NULL,
  `student_id` INT UNSIGNED NOT NULL,
  `score` DECIMAL(8,2) NOT NULL DEFAULT 0.00,
  `started_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `completed_at` DATETIME DEFAULT NULL,
  `is_graded` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_attempts_quiz` (`quiz_id`),
  KEY `idx_attempts_student` (`student_id`),
  KEY `idx_attempts_started_at` (`started_at`),
  CONSTRAINT `fk_attempts_quiz`
    FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `fk_attempts_student`
    FOREIGN KEY (`student_id`) REFERENCES `users` (`id`)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `answers` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `attempt_id` INT UNSIGNED NOT NULL,
  `question_id` INT UNSIGNED NOT NULL,
  `selected_option_id` INT UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_answers_attempt_question` (`attempt_id`, `question_id`),
  KEY `idx_answers_question` (`question_id`),
  KEY `idx_answers_selected_option` (`selected_option_id`),
  CONSTRAINT `fk_answers_attempt`
    FOREIGN KEY (`attempt_id`) REFERENCES `attempts` (`id`)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `fk_answers_question`
    FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `fk_answers_selected_option`
    FOREIGN KEY (`selected_option_id`) REFERENCES `options` (`id`)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `course_materials` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `course_id` INT UNSIGNED NOT NULL,
  `uploaded_by` INT UNSIGNED NOT NULL,
  `title` VARCHAR(150) NOT NULL,
  `file_path` VARCHAR(255) NOT NULL,
  `material_type` ENUM('document', 'link', 'video') NOT NULL DEFAULT 'document',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_course_materials_course` (`course_id`),
  KEY `idx_course_materials_uploaded_by` (`uploaded_by`),
  KEY `idx_course_materials_type` (`material_type`),
  CONSTRAINT `fk_course_materials_course`
    FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `fk_course_materials_uploaded_by`
    FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `announcements` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `course_id` INT UNSIGNED NOT NULL,
  `author_id` INT UNSIGNED NOT NULL,
  `title` VARCHAR(150) NOT NULL,
  `body` TEXT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_announcements_course` (`course_id`),
  KEY `idx_announcements_author` (`author_id`),
  KEY `idx_announcements_created_at` (`created_at`),
  CONSTRAINT `fk_announcements_course`
    FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `fk_announcements_author`
    FOREIGN KEY (`author_id`) REFERENCES `users` (`id`)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `qa_questions` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `course_id` INT UNSIGNED NOT NULL,
  `student_id` INT UNSIGNED NOT NULL,
  `title` VARCHAR(150) NOT NULL,
  `body` TEXT NOT NULL,
  `is_resolved` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_qa_questions_course` (`course_id`),
  KEY `idx_qa_questions_student` (`student_id`),
  KEY `idx_qa_questions_resolved` (`is_resolved`),
  CONSTRAINT `fk_qa_questions_course`
    FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `fk_qa_questions_student`
    FOREIGN KEY (`student_id`) REFERENCES `users` (`id`)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `qa_answers` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `qa_question_id` INT UNSIGNED NOT NULL,
  `author_id` INT UNSIGNED NOT NULL,
  `body` TEXT NOT NULL,
  `is_endorsed` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_qa_answers_question` (`qa_question_id`),
  KEY `idx_qa_answers_author` (`author_id`),
  KEY `idx_qa_answers_endorsed` (`is_endorsed`),
  CONSTRAINT `fk_qa_answers_question`
    FOREIGN KEY (`qa_question_id`) REFERENCES `qa_questions` (`id`)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `fk_qa_answers_author`
    FOREIGN KEY (`author_id`) REFERENCES `users` (`id`)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `doubt_sessions` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `course_id` INT UNSIGNED NOT NULL,
  `ta_id` INT UNSIGNED NOT NULL,
  `title` VARCHAR(150) NOT NULL,
  `scheduled_at` DATETIME NOT NULL,
  `duration_minutes` INT UNSIGNED NOT NULL DEFAULT 60,
  `location_or_link` VARCHAR(255) NOT NULL,
  `max_attendees` INT UNSIGNED NOT NULL DEFAULT 20,
  `status` ENUM('scheduled', 'cancelled', 'completed') NOT NULL DEFAULT 'scheduled',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_doubt_sessions_course` (`course_id`),
  KEY `idx_doubt_sessions_ta` (`ta_id`),
  KEY `idx_doubt_sessions_scheduled_at` (`scheduled_at`),
  CONSTRAINT `fk_doubt_sessions_course`
    FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `fk_doubt_sessions_ta`
    FOREIGN KEY (`ta_id`) REFERENCES `users` (`id`)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `doubt_session_bookings` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `doubt_session_id` INT UNSIGNED NOT NULL,
  `student_id` INT UNSIGNED NOT NULL,
  `booking_status` ENUM('booked', 'cancelled', 'attended') NOT NULL DEFAULT 'booked',
  `booked_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_doubt_booking_student` (`doubt_session_id`, `student_id`),
  KEY `idx_doubt_bookings_student` (`student_id`),
  CONSTRAINT `fk_doubt_bookings_session`
    FOREIGN KEY (`doubt_session_id`) REFERENCES `doubt_sessions` (`id`)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `fk_doubt_bookings_student`
    FOREIGN KEY (`student_id`) REFERENCES `users` (`id`)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `platform_settings` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `setting_key` VARCHAR(100) NOT NULL,
  `setting_value` TEXT NOT NULL,
  `setting_type` ENUM('string', 'integer', 'decimal', 'boolean', 'json') NOT NULL DEFAULT 'string',
  `description` TEXT DEFAULT NULL,
  `updated_by` INT UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_platform_settings_key` (`setting_key`),
  KEY `idx_platform_settings_updated_by` (`updated_by`),
  CONSTRAINT `fk_platform_settings_updated_by`
    FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `platform_announcements` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `author_id` INT UNSIGNED NOT NULL,
  `title` VARCHAR(150) NOT NULL,
  `body` TEXT NOT NULL,
  `audience` ENUM('all', 'students', 'instructors', 'tas') NOT NULL DEFAULT 'all',
  `status` ENUM('draft', 'published', 'archived') NOT NULL DEFAULT 'draft',
  `publish_from` DATETIME DEFAULT NULL,
  `publish_until` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_platform_announcements_author` (`author_id`),
  KEY `idx_platform_announcements_status` (`status`),
  KEY `idx_platform_announcements_audience` (`audience`),
  CONSTRAINT `fk_platform_announcements_author`
    FOREIGN KEY (`author_id`) REFERENCES `users` (`id`)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `integrity_reports` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `reported_by` INT UNSIGNED DEFAULT NULL,
  `student_id` INT UNSIGNED DEFAULT NULL,
  `quiz_id` INT UNSIGNED DEFAULT NULL,
  `attempt_id` INT UNSIGNED DEFAULT NULL,
  `report_type` ENUM('suspicious_activity', 'multiple_logins', 'copy_paste', 'rapid_submission', 'reported_content', 'policy_violation', 'other') NOT NULL DEFAULT 'other',
  `severity` ENUM('low', 'medium', 'high', 'critical') NOT NULL DEFAULT 'medium',
  `status` ENUM('open', 'under_review', 'resolved', 'escalated', 'dismissed') NOT NULL DEFAULT 'open',
  `title` VARCHAR(150) NOT NULL,
  `description` TEXT NOT NULL,
  `resolution_notes` TEXT DEFAULT NULL,
  `resolved_by` INT UNSIGNED DEFAULT NULL,
  `resolved_at` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_integrity_reports_reported_by` (`reported_by`),
  KEY `idx_integrity_reports_student` (`student_id`),
  KEY `idx_integrity_reports_quiz` (`quiz_id`),
  KEY `idx_integrity_reports_attempt` (`attempt_id`),
  KEY `idx_integrity_reports_status` (`status`),
  KEY `idx_integrity_reports_severity` (`severity`),
  CONSTRAINT `fk_integrity_reports_reported_by`
    FOREIGN KEY (`reported_by`) REFERENCES `users` (`id`)
    ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT `fk_integrity_reports_student`
    FOREIGN KEY (`student_id`) REFERENCES `users` (`id`)
    ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT `fk_integrity_reports_quiz`
    FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`)
    ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT `fk_integrity_reports_attempt`
    FOREIGN KEY (`attempt_id`) REFERENCES `attempts` (`id`)
    ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT `fk_integrity_reports_resolved_by`
    FOREIGN KEY (`resolved_by`) REFERENCES `users` (`id`)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `audit_logs` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` INT UNSIGNED DEFAULT NULL,
  `action` VARCHAR(100) NOT NULL,
  `entity_type` VARCHAR(100) NOT NULL,
  `entity_id` INT UNSIGNED DEFAULT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `user_agent` VARCHAR(255) DEFAULT NULL,
  `details` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_audit_logs_admin` (`admin_id`),
  KEY `idx_audit_logs_action` (`action`),
  KEY `idx_audit_logs_entity` (`entity_type`, `entity_id`),
  KEY `idx_audit_logs_created_at` (`created_at`),
  CONSTRAINT `fk_audit_logs_admin`
    FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users`
(`id`, `username`, `name`, `email`, `password_hash`, `phone`, `role`, `profile_pic`, `student_id`, `program`, `department`, `bio`, `approval_status`, `is_active`, `created_at`)
VALUES
(1, 'tamim', 'Tamim Admin', 'admin@example.com', '$2y$10$qrLkQ1ldOJncaWxXsowPmOwtz4rCo08/yDIBbcFnv6uPoKCWjvMn6', '01710000001', 'admin', NULL, NULL, NULL, 'Administration', 'Platform administrator account.', 'approved', 1, '2026-01-05 09:00:00'),
(2, 'ayesha', 'Ayesha Rahman', 'ayesha.student@example.com', '$2y$10$NxPnR.t4/H3fdRuct0YIG.yzGkSen2P2SWQ6l450jufC3h6Wh2tCu', '01710000002', 'student', NULL, 'STU-1001', 'BSc in CSE', NULL, NULL, 'approved', 1, '2026-01-06 10:00:00'),
(3, 'rafi', 'Rafi Hasan', 'rafi.student@example.com', '$2y$10$NxPnR.t4/H3fdRuct0YIG.yzGkSen2P2SWQ6l450jufC3h6Wh2tCu', '01710000003', 'student', NULL, 'STU-1002', 'BSc in Mathematics', NULL, NULL, 'approved', 1, '2026-01-06 10:15:00'),
(4, 'nabila', 'Nabila Karim', 'nabila.student@example.com', '$2y$10$NxPnR.t4/H3fdRuct0YIG.yzGkSen2P2SWQ6l450jufC3h6Wh2tCu', '01710000004', 'student', NULL, 'STU-1003', 'BBA', NULL, NULL, 'approved', 0, '2026-01-06 10:30:00'),
(5, 'farhan', 'Dr. Farhan Ahmed', 'farhan.instructor@example.com', '$2y$10$0n7YzS8hxWgW3xrFWLetsOGDpEtT.TiunQOL6NAMLQs1VreF.C6KO', '01710000005', 'instructor', NULL, NULL, NULL, 'Computer Science', 'Instructor for programming and database systems.', 'approved', 1, '2026-01-07 11:00:00'),
(6, 'nadia', 'Dr. Nadia Islam', 'nadia.instructor@example.com', '$2y$10$0n7YzS8hxWgW3xrFWLetsOGDpEtT.TiunQOL6NAMLQs1VreF.C6KO', '01710000006', 'instructor', NULL, NULL, NULL, 'Mathematics', 'Instructor for discrete mathematics and writing support.', 'approved', 1, '2026-01-07 11:30:00'),
(7, 'sami', 'Sami Chowdhury', 'sami.ta@example.com', '$2y$10$bDaCv6v3NMn3D1LuckVAtuZPijuwAXVyLhFQ/1dbfboNZPgLkxz1u', '01710000007', 'ta', NULL, NULL, NULL, 'Computer Science', 'Teaching assistant for programming labs.', 'approved', 1, '2026-01-08 12:00:00'),
(8, 'jarin', 'Jarin Sultana', 'jarin.ta@example.com', '$2y$10$bDaCv6v3NMn3D1LuckVAtuZPijuwAXVyLhFQ/1dbfboNZPgLkxz1u', '01710000008', 'ta', NULL, NULL, NULL, 'Mathematics', 'Teaching assistant for problem solving sessions.', 'approved', 1, '2026-01-08 12:30:00'),
(9, 'pending_farid', 'Farid Uddin', 'pending.instructor1@example.com', '$2y$10$deCfS8i/YYVUdTyTO.POUeAP8i6TPyd0EOlDa5x5mneGZElBhxsXS', '01710000009', 'instructor', NULL, NULL, NULL, 'Business Studies', 'Pending instructor approval example.', 'pending', 0, '2026-01-09 13:00:00'),
(10, 'pending_maria', 'Maria Akter', 'pending.instructor2@example.com', '$2y$10$deCfS8i/YYVUdTyTO.POUeAP8i6TPyd0EOlDa5x5mneGZElBhxsXS', '01710000010', 'instructor', NULL, NULL, NULL, 'English Language', 'Pending instructor approval example.', 'pending', 0, '2026-01-09 13:15:00');

INSERT INTO `subjects` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'Mathematics', 'Courses related to mathematics, logic, statistics, and quantitative reasoning.', '2026-01-10 09:00:00'),
(2, 'Computer Science', 'Programming, database, web development, and computing fundamentals.', '2026-01-10 09:05:00'),
(3, 'Business Studies', 'Business analytics, management, accounting, and entrepreneurship.', '2026-01-10 09:10:00'),
(4, 'English Language', 'Academic writing, communication, and language skills.', '2026-01-10 09:15:00');

INSERT INTO `courses`
(`id`, `instructor_id`, `subject_id`, `title`, `description`, `enrollment_type`, `max_students`, `status`, `created_at`)
VALUES
(1, 5, 2, 'Introduction to Programming', 'Learn programming basics using PHP examples and problem solving.', 'open', 80, 'active', '2026-01-12 09:00:00'),
(2, 6, 1, 'Discrete Mathematics', 'Logic, sets, functions, relations, and proof techniques.', 'approval', 60, 'active', '2026-01-12 09:30:00'),
(3, 5, 2, 'Database Systems', 'Relational database design, SQL, normalization, and transactions.', 'approval', 50, 'draft', '2026-01-12 10:00:00'),
(4, 6, 4, 'Academic Writing Essentials', 'Archived writing course with essays, references, and feedback.', 'open', 40, 'archived', '2026-01-12 10:30:00'),
(5, 5, 3, 'Business Analytics Basics', 'Introductory analytics course for business decision making.', 'open', 70, 'active', '2026-01-12 11:00:00');

INSERT INTO `course_tas` (`id`, `course_id`, `ta_id`, `assigned_at`) VALUES
(1, 1, 7, '2026-01-13 09:00:00'),
(2, 2, 8, '2026-01-13 09:15:00'),
(3, 5, 7, '2026-01-13 09:30:00');

INSERT INTO `enrollments` (`id`, `student_id`, `course_id`, `status`, `enrolled_at`) VALUES
(1, 2, 1, 'active', '2026-01-15 10:00:00'),
(2, 3, 1, 'active', '2026-01-15 10:10:00'),
(3, 4, 1, 'dropped', '2026-01-15 10:20:00'),
(4, 2, 2, 'pending', '2026-01-15 10:30:00'),
(5, 3, 2, 'active', '2026-01-15 10:40:00'),
(6, 2, 5, 'active', '2026-01-15 10:50:00'),
(7, 3, 5, 'active', '2026-01-15 11:00:00');

INSERT INTO `quizzes`
(`id`, `course_id`, `created_by`, `title`, `description`, `time_limit_minutes`, `total_marks`, `pass_mark`, `quiz_type`, `status`, `available_from`, `available_until`, `created_at`)
VALUES
(1, 1, 5, 'PHP Fundamentals Quiz', 'Graded quiz on PHP basics and secure database queries.', 30, 10.00, 6.00, 'graded', 'published', '2026-02-01 08:00:00', '2026-06-30 23:59:00', '2026-01-20 09:00:00'),
(2, 1, 7, 'Programming Practice Set', 'Practice quiz prepared by the TA.', 20, 5.00, 0.00, 'practice', 'published', '2026-02-01 08:00:00', '2026-06-30 23:59:00', '2026-01-20 09:15:00'),
(3, 2, 6, 'Logic and Sets Quiz', 'Graded quiz on basic logic and set theory.', 35, 10.00, 6.00, 'graded', 'published', '2026-02-05 08:00:00', '2026-06-30 23:59:00', '2026-01-20 09:30:00'),
(4, 5, 5, 'Business Metrics Practice', 'Draft practice quiz on business metrics.', 25, 5.00, 0.00, 'practice', 'draft', NULL, NULL, '2026-01-20 09:45:00'),
(5, 3, 5, 'SQL Joins Quiz', 'Draft graded quiz for the database systems course.', 40, 5.00, 3.00, 'graded', 'draft', NULL, NULL, '2026-01-20 10:00:00');

INSERT INTO `questions`
(`id`, `quiz_id`, `question_text`, `marks`, `order_index`, `created_by`, `created_at`)
VALUES
(1, 1, 'What does PHP stand for?', 5.00, 1, 5, '2026-01-21 09:00:00'),
(2, 1, 'Which mysqli feature should be used to safely insert user input?', 5.00, 2, 5, '2026-01-21 09:05:00'),
(3, 2, 'Which loop checks the condition before each iteration?', 5.00, 1, 7, '2026-01-21 09:10:00'),
(4, 3, 'What is a set?', 5.00, 1, 6, '2026-01-21 09:15:00'),
(5, 3, 'When is the statement p AND q true?', 5.00, 2, 6, '2026-01-21 09:20:00'),
(6, 4, 'Which metric measures visitors who complete a desired action?', 5.00, 1, 5, '2026-01-21 09:25:00'),
(7, 5, 'Which SQL join returns only matching rows from both tables?', 5.00, 1, 5, '2026-01-21 09:30:00');

INSERT INTO `options` (`id`, `question_id`, `option_text`, `is_correct`, `created_at`) VALUES
(1, 1, 'Private Home Page', 0, '2026-01-21 10:00:00'),
(2, 1, 'Hypertext Preprocessor', 1, '2026-01-21 10:01:00'),
(3, 1, 'Personal Hyper Processor', 0, '2026-01-21 10:02:00'),
(4, 1, 'Program Hosting Protocol', 0, '2026-01-21 10:03:00'),

(5, 2, 'String concatenation', 0, '2026-01-21 10:04:00'),
(6, 2, 'Prepared statements', 1, '2026-01-21 10:05:00'),
(7, 2, 'Hidden input fields', 0, '2026-01-21 10:06:00'),
(8, 2, 'Plain SQL text', 0, '2026-01-21 10:07:00'),

(9, 3, 'for loop', 0, '2026-01-21 10:08:00'),
(10, 3, 'while loop', 1, '2026-01-21 10:09:00'),
(11, 3, 'do while loop', 0, '2026-01-21 10:10:00'),
(12, 3, 'switch block', 0, '2026-01-21 10:11:00'),

(13, 4, 'A collection of distinct elements', 1, '2026-01-21 10:12:00'),
(14, 4, 'A sequence with repeated values only', 0, '2026-01-21 10:13:00'),
(15, 4, 'A database table', 0, '2026-01-21 10:14:00'),
(16, 4, 'A programming language', 0, '2026-01-21 10:15:00'),

(17, 5, 'Only p is true', 0, '2026-01-21 10:16:00'),
(18, 5, 'Only q is true', 0, '2026-01-21 10:17:00'),
(19, 5, 'Both p and q are true', 1, '2026-01-21 10:18:00'),
(20, 5, 'Both p and q are false', 0, '2026-01-21 10:19:00'),

(21, 6, 'Bounce rate', 0, '2026-01-21 10:20:00'),
(22, 6, 'Conversion rate', 1, '2026-01-21 10:21:00'),
(23, 6, 'Inventory turnover', 0, '2026-01-21 10:22:00'),
(24, 6, 'Gross margin', 0, '2026-01-21 10:23:00'),

(25, 7, 'INNER JOIN', 1, '2026-01-21 10:24:00'),
(26, 7, 'LEFT JOIN', 0, '2026-01-21 10:25:00'),
(27, 7, 'RIGHT JOIN', 0, '2026-01-21 10:26:00'),
(28, 7, 'FULL OUTER JOIN', 0, '2026-01-21 10:27:00');

INSERT INTO `attempts`
(`id`, `quiz_id`, `student_id`, `score`, `started_at`, `completed_at`, `is_graded`)
VALUES
(1, 1, 2, 10.00, '2026-02-03 09:00:00', '2026-02-03 09:18:00', 1),
(2, 1, 3, 5.00, '2026-02-03 10:00:00', '2026-02-03 10:24:00', 1),
(3, 2, 2, 5.00, '2026-02-04 11:00:00', '2026-02-04 11:08:00', 1),
(4, 3, 3, 5.00, '2026-02-06 09:00:00', '2026-02-06 09:30:00', 1),
(5, 3, 2, 5.00, '2026-02-06 10:00:00', '2026-02-06 10:27:00', 1);

INSERT INTO `answers` (`id`, `attempt_id`, `question_id`, `selected_option_id`, `created_at`) VALUES
(1, 1, 1, 2, '2026-02-03 09:10:00'),
(2, 1, 2, 6, '2026-02-03 09:17:00'),
(3, 2, 1, 2, '2026-02-03 10:10:00'),
(4, 2, 2, 8, '2026-02-03 10:22:00'),
(5, 3, 3, 10, '2026-02-04 11:07:00'),
(6, 4, 4, 13, '2026-02-06 09:15:00'),
(7, 4, 5, 18, '2026-02-06 09:28:00'),
(8, 5, 4, 15, '2026-02-06 10:12:00'),
(9, 5, 5, 19, '2026-02-06 10:25:00');

INSERT INTO `course_materials`
(`id`, `course_id`, `uploaded_by`, `title`, `file_path`, `material_type`, `created_at`)
VALUES
(1, 1, 5, 'Lecture 1 Slides', 'uploads/materials/programming-lecture-1.pdf', 'document', '2026-01-25 09:00:00'),
(2, 1, 7, 'Extra PHP Practice Link', 'https://example.com/php-practice', 'link', '2026-01-25 09:15:00'),
(3, 2, 6, 'Set Theory Notes', 'uploads/materials/set-theory-notes.pdf', 'document', '2026-01-25 09:30:00'),
(4, 5, 5, 'Analytics Intro Video', 'https://example.com/business-analytics-video', 'video', '2026-01-25 09:45:00'),
(5, 4, 6, 'Writing Rubric', 'uploads/materials/writing-rubric.pdf', 'document', '2026-01-25 10:00:00');

INSERT INTO `announcements`
(`id`, `course_id`, `author_id`, `title`, `body`, `created_at`)
VALUES
(1, 1, 5, 'Welcome to Introduction to Programming', 'Please review the first lecture slides before class.', '2026-01-26 09:00:00'),
(2, 1, 7, 'Practice Set Posted', 'A practice quiz is now available for PHP basics.', '2026-01-27 09:00:00'),
(3, 2, 6, 'Logic Quiz Schedule', 'The first graded quiz will be available this week.', '2026-01-28 09:00:00'),
(4, 5, 5, 'Business Analytics Course Started', 'Welcome to the course. Please read the first material.', '2026-01-29 09:00:00');

INSERT INTO `qa_questions`
(`id`, `course_id`, `student_id`, `title`, `body`, `is_resolved`, `created_at`)
VALUES
(1, 1, 2, 'How do I debug PHP notices?', 'I am seeing undefined index notices in my form handler.', 0, '2026-02-01 14:00:00'),
(2, 1, 3, 'Can we retry practice quizzes?', 'I want to know if practice quiz attempts are unlimited.', 1, '2026-02-01 14:20:00'),
(3, 2, 3, 'Subset vs proper subset', 'What is the difference between subset and proper subset?', 0, '2026-02-02 15:00:00'),
(4, 5, 2, 'Best chart for conversions', 'Which chart should I use to show conversion rates?', 0, '2026-02-02 15:30:00');

INSERT INTO `qa_answers`
(`id`, `qa_question_id`, `author_id`, `body`, `is_endorsed`, `created_at`)
VALUES
(1, 1, 7, 'Check whether the array key exists before reading it, then validate the value on the server.', 1, '2026-02-01 15:00:00'),
(2, 2, 5, 'Yes, practice quizzes can be attempted multiple times.', 1, '2026-02-01 15:20:00'),
(3, 3, 8, 'A proper subset excludes the case where both sets are exactly equal.', 0, '2026-02-02 16:00:00'),
(4, 4, 5, 'A line chart works well for changes over time; a bar chart works well for comparing campaigns.', 0, '2026-02-02 16:20:00');

INSERT INTO `doubt_sessions`
(`id`, `course_id`, `ta_id`, `title`, `scheduled_at`, `duration_minutes`, `location_or_link`, `max_attendees`, `status`, `created_at`)
VALUES
(1, 1, 7, 'PHP Basics Clinic', '2026-06-01 16:00:00', 60, 'https://meet.example.com/php-basics', 20, 'scheduled', '2026-02-10 09:00:00'),
(2, 2, 8, 'Logic Problem Solving', '2026-06-03 15:00:00', 90, 'Room 402, Academic Building', 15, 'scheduled', '2026-02-10 09:15:00'),
(3, 1, 7, 'Past Practice Review', '2026-02-15 16:00:00', 60, 'https://meet.example.com/past-practice', 20, 'completed', '2026-02-10 09:30:00');

INSERT INTO `doubt_session_bookings`
(`id`, `doubt_session_id`, `student_id`, `booking_status`, `booked_at`)
VALUES
(1, 1, 2, 'booked', '2026-02-11 10:00:00'),
(2, 1, 3, 'booked', '2026-02-11 10:10:00'),
(3, 2, 3, 'booked', '2026-02-11 10:20:00'),
(4, 3, 2, 'attended', '2026-02-12 10:00:00');

INSERT INTO `platform_settings`
(`id`, `setting_key`, `setting_value`, `setting_type`, `description`, `updated_by`, `created_at`)
VALUES
(1, 'default_max_students_per_course', '50', 'integer', 'Default maximum student limit for newly created courses.', 1, '2026-01-30 09:00:00'),
(2, 'max_quiz_duration_minutes', '120', 'integer', 'Maximum allowed duration for a quiz.', 1, '2026-01-30 09:05:00'),
(3, 'at_risk_score_threshold', '40', 'integer', 'Score percentage below which students are considered at risk.', 1, '2026-01-30 09:10:00'),
(4, 'allow_student_registration', 'true', 'boolean', 'Controls whether students can self-register.', 1, '2026-01-30 09:15:00'),
(5, 'current_semester', 'Spring 2026', 'string', 'Current semester label for reports.', 1, '2026-01-30 09:20:00');

INSERT INTO `platform_announcements`
(`id`, `author_id`, `title`, `body`, `audience`, `status`, `publish_from`, `publish_until`, `created_at`)
VALUES
(1, 1, 'Scheduled Maintenance', 'The platform will be under maintenance on Friday from 10 PM to 11 PM.', 'all', 'published', '2026-02-01 08:00:00', '2026-02-08 23:59:00', '2026-01-31 09:00:00'),
(2, 1, 'Instructor Onboarding', 'New instructors should complete profile setup before creating courses.', 'instructors', 'published', '2026-02-01 08:00:00', NULL, '2026-01-31 09:10:00'),
(3, 1, 'Student Survey Draft', 'A platform feedback survey will be shared soon.', 'students', 'draft', NULL, NULL, '2026-01-31 09:20:00');

INSERT INTO `integrity_reports`
(`id`, `reported_by`, `student_id`, `quiz_id`, `attempt_id`, `report_type`, `severity`, `status`, `title`, `description`, `resolution_notes`, `resolved_by`, `resolved_at`, `created_at`)
VALUES
(1, 5, 3, 1, 2, 'suspicious_activity', 'medium', 'open', 'Unusual tab switching pattern', 'Instructor noticed repeated focus changes during the quiz window.', NULL, NULL, NULL, '2026-02-03 11:00:00'),
(2, 7, 2, 3, 5, 'rapid_submission', 'low', 'under_review', 'Very fast answer submission', 'Attempt was completed faster than expected for the quiz length.', NULL, NULL, NULL, '2026-02-06 11:00:00'),
(3, 1, 4, NULL, NULL, 'policy_violation', 'high', 'resolved', 'Inactive account review', 'Inactive student account was reviewed for access policy compliance.', 'Account remains inactive until student office confirms status.', 1, '2026-02-07 12:00:00', '2026-02-07 10:00:00');

INSERT INTO `audit_logs`
(`id`, `admin_id`, `action`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `details`, `created_at`)
VALUES
(1, 1, 'create_user', 'users', 8, '127.0.0.1', 'XAMPP Local Admin Panel', '{"created_role":"ta","email":"jarin.ta@example.com"}', '2026-01-08 12:31:00'),
(2, 1, 'deactivate_user', 'users', 4, '127.0.0.1', 'XAMPP Local Admin Panel', '{"reason":"sample inactive student for admin testing"}', '2026-01-09 09:00:00'),
(3, 1, 'update_setting', 'platform_settings', 2, '127.0.0.1', 'XAMPP Local Admin Panel', '{"setting_key":"max_quiz_duration_minutes","new_value":"120"}', '2026-01-30 09:06:00'),
(4, 1, 'publish_platform_announcement', 'platform_announcements', 1, '127.0.0.1', 'XAMPP Local Admin Panel', '{"status":"published"}', '2026-01-31 09:01:00'),
(5, 1, 'review_integrity_report', 'integrity_reports', 3, '127.0.0.1', 'XAMPP Local Admin Panel', '{"status":"resolved"}', '2026-02-07 12:01:00');
