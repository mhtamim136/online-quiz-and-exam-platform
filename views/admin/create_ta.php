<?php require __DIR__ . '/../partials/admin_header.php'; ?>

<div class="panel">
    <h2>Create Teaching Assistant Account</h2>

    <div id="formError" class="form-error"></div>

    <?php if (!empty($errors)): ?>
        <div class="form-error server-errors">
            <?php foreach ($errors as $error): ?>
                <div><?php echo e($error); ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form id="createTaForm" method="post" action="admin.php?action=create_ta">
        <div class="form-grid">
            <div class="form-row">
                <label for="name">Name</label>
                <input id="name" name="name" type="text" value="<?php echo e($old['name']); ?>">
            </div>
            <div class="form-row">
                <label for="username">Username</label>
                <input id="username" name="username" type="text" value="<?php echo e($old['username']); ?>">
            </div>
            <div class="form-row">
                <label for="email">Email</label>
                <input id="email" name="email" type="text" value="<?php echo e($old['email']); ?>">
            </div>
            <div class="form-row">
                <label for="phone">Phone</label>
                <input id="phone" name="phone" type="text" value="<?php echo e($old['phone']); ?>">
            </div>
            <div class="form-row">
                <label for="department">Department</label>
                <input id="department" name="department" type="text" value="<?php echo e($old['department']); ?>">
            </div>
            <div class="form-row">
                <label for="password">Password</label>
                <input id="password" name="password" type="password">
            </div>
            <div class="form-row">
                <label for="confirm_password">Confirm Password</label>
                <input id="confirm_password" name="confirm_password" type="password">
            </div>
        </div>

        <div class="form-row">
            <label for="bio">Bio</label>
            <textarea id="bio" name="bio"><?php echo e($old['bio']); ?></textarea>
        </div>

        <button class="primary">Create TA Account</button>
    </form>
</div>

<?php require __DIR__ . '/../partials/admin_footer.php'; ?>
