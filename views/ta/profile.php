<?php require __DIR__ . '/../partials/ta_header.php'; ?>

<div class="ta-card">
    <h2>Profile Information</h2>
    <?php if (!empty($profile['profile_pic'])): ?>
        <img class="ta-profile-pic" src="<?php echo taE($profile['profile_pic']); ?>" alt="Profile picture">
    <?php endif; ?>
    <form id="taProfileForm" method="post" action="ta.php?action=update_profile" enctype="multipart/form-data">
        <div id="profileError" class="ta-inline-error"></div>
        <div class="ta-form-grid">
            <div class="ta-form-row">
                <label for="name">Name</label>
                <input id="name" name="name" type="text" value="<?php echo taE($profile['name']); ?>">
            </div>
            <div class="ta-form-row">
                <label>Email</label>
                <input type="text" value="<?php echo taE($profile['email']); ?>" readonly>
            </div>
            <div class="ta-form-row">
                <label for="phone">Phone</label>
                <input id="phone" name="phone" type="text" value="<?php echo taE($profile['phone']); ?>">
            </div>
            <div class="ta-form-row">
                <label for="department">Department</label>
                <input id="department" name="department" type="text" value="<?php echo taE($profile['department']); ?>">
            </div>
            <div class="ta-form-row full">
                <label for="bio">Bio</label>
                <textarea id="bio" name="bio"><?php echo taE($profile['bio']); ?></textarea>
            </div>
            <div class="ta-form-row">
                <label for="profile_pic">Profile Picture</label>
                <input id="profile_pic" name="profile_pic" type="file">
            </div>
        </div>
        <button class="ta-button">Update Profile</button>
    </form>
</div>

<div class="ta-card">
    <h2>Change Password</h2>
    <form id="taPasswordForm" method="post" action="ta.php?action=change_password">
        <div id="passwordError" class="ta-inline-error"></div>
        <div class="ta-form-grid">
            <div class="ta-form-row">
                <label for="current_password">Current Password</label>
                <input id="current_password" name="current_password" type="password">
            </div>
            <div class="ta-form-row">
                <label for="new_password">New Password</label>
                <input id="new_password" name="new_password" type="password">
            </div>
            <div class="ta-form-row">
                <label for="confirm_password">Confirm Password</label>
                <input id="confirm_password" name="confirm_password" type="password">
            </div>
        </div>
        <button class="ta-button">Change Password</button>
    </form>
</div>

<?php require __DIR__ . '/../partials/ta_footer.php'; ?>
