<?php if (isset($errors) && count($errors) > 0) { ?>
    <div class="instructor-alert error">
        <?php foreach ($errors as $error) { ?><div><?php echo instructorE($error); ?></div><?php } ?>
    </div>
<?php } ?>
<?php if (isset($success) && $success !== '') { ?>
    <div class="instructor-alert success"><?php echo instructorE($success); ?></div>
<?php } ?>
