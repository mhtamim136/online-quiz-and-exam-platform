# Instructor Role Module

Standalone procedural PHP instructor module for the university Online Quiz & Exam Platform.

## Database

Use the provided database script:

`/Users/applemacbookair/Downloads/dbcode (1).sql`

Database name:

`online_quiz_exam_platform`

The module uses the existing shared tables and exact column names from that script. No shared table is renamed.

## Run

Place `instructor-role-project` inside your PHP server directory and open:

`instructor_login.php`

Sample instructor login:

Email: `farhan.instructor@example.com`

Password: `instructor123`

## Rules Followed

- Procedural PHP only
- MySQL with `mysqli`
- Prepared statements for database queries
- PHP sessions
- MVC folder structure
- Instructor-prefixed files for merge safety
- JavaScript validation plus PHP validation
- XMLHttpRequest AJAX for enrollment approval
- No PDO, Composer, npm, Bootstrap, Tailwind, jQuery, Fetch API, Laravel, React, or Vue
