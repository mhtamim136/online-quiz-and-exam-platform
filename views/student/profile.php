<h1>My Profile</h1>

<?php if (!empty($errors)): ?>
    <div class="student-alert error">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo studentE($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<section class="student-grid two">
    <div class="student-card">
        <h2>Profile Information</h2>
        <?php if (!empty($student['profile_pic'])): ?>
            <img class="student-profile-pic" src="<?php echo studentE($student['profile_pic']); ?>" alt="Profile picture">
        <?php else: ?>
            <div class="student-profile-pic"></div>
        <?php endif; ?>

        <p><strong>Email:</strong> <?php echo studentE($student['email']); ?></p>
        <p><strong>Student ID:</strong> <?php echo studentE($student['student_id']); ?></p>
        <p><strong>Joined:</strong> <?php echo studentE($student['created_at']); ?></p>

        <div id="studentProfileErrors" class="student-js-errors"></div>
        <form id="studentProfileForm" method="post" action="student.php?action=profile" novalidate>
            <input type="hidden" name="profile_action" value="update_profile">

            <label for="profile_name">Name</label>
            <input id="profile_name" name="name" type="text" value="<?php echo studentE($student['name']); ?>">

            <label for="profile_phone">Phone</label>
            <input id="profile_phone" name="phone" type="text" value="<?php echo studentE($student['phone']); ?>">

            <label for="profile_program">Program</label>
            <input id="profile_program" name="program" type="text" value="<?php echo studentE($student['program']); ?>">

            <button type="submit" class="student-btn primary">Update Profile</button>
        </form>
    </div>

    <div>
        <section class="student-card">
            <h2>Upload Picture</h2>
            <form method="post" action="student.php?action=profile" enctype="multipart/form-data">
                <input type="hidden" name="profile_action" value="upload_picture">
                <label for="profile_pic">Profile Picture</label>
                <input id="profile_pic" name="profile_pic" type="file">
                <button type="submit" class="student-btn secondary">Upload Picture</button>
            </form>
        </section>

        <section class="student-card">
            <h2>Change Password</h2>
            <div id="studentPasswordErrors" class="student-js-errors"></div>
            <form id="studentPasswordForm" method="post" action="student.php?action=profile" novalidate>
                <input type="hidden" name="profile_action" value="change_password">

                <label for="password_current">Current Password</label>
                <input id="password_current" name="current_password" type="password">

                <label for="password_new">New Password</label>
                <input id="password_new" name="new_password" type="password">

                <label for="password_confirm">Confirm Password</label>
                <input id="password_confirm" name="confirm_password" type="password">

                <button type="submit" class="student-btn primary">Change Password</button>
            </form>
        </section>
    </div>
</section>
