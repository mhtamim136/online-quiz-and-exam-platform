<main class="instructor-main">
    <header class="instructor-topbar">
        <h1><?php echo instructorE($pageTitle); ?></h1>
        <span><?php echo instructorE(isset($_SESSION['instructor_name']) ? $_SESSION['instructor_name'] : 'Instructor'); ?></span>
    </header>
    <section class="instructor-content">
