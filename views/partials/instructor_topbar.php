<main class="instructor-main">
    <header class="instructor-topbar">
        <h1><?php echo instructorE($pageTitle); ?></h1>
        <div class="instructor-topbar-user">
            <a class="instructor-user-link" href="instructor.php?action=profile"><?php echo instructorE($_SESSION['name'] ?? ($_SESSION['instructor_name'] ?? 'Instructor')); ?></a>
            <a class="instructor-logout-link" href="logout.php">Logout</a>
        </div>
    </header>
    <section class="instructor-content">
