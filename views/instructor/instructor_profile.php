<?php include __DIR__ . '/instructor_messages.php'; ?>
<div class="instructor-card">
    <form method="post" enctype="multipart/form-data" class="instructor-form" data-instructor-validate>
        <label>Name</label>
        <input name="name" value="<?php echo instructorE($profile['name']); ?>" data-instructor-label="Name">
        <label>Email</label>
        <input value="<?php echo instructorE($profile['email']); ?>" readonly>
        <label>Department</label>
        <input name="department" value="<?php echo instructorE($profile['department']); ?>" data-instructor-label="Department">
        <label>Bio</label>
        <textarea name="bio"><?php echo instructorE($profile['bio']); ?></textarea>
        <label>Profile Picture</label>
        <input name="profile_pic" type="file">
        <div class="instructor-actions"><button type="submit">Update Profile</button></div>
    </form>
</div>
