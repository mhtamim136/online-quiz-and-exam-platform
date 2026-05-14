<?php require __DIR__ . '/../partials/admin_header.php'; ?>

<div id="formError" class="form-error"></div>
<?php if (!empty($errors)): ?>
    <div class="form-error server-errors">
        <?php foreach ($errors as $error): ?>
            <div><?php echo e($error); ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="panel">
    <h2>Platform Settings</h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Key</th>
                    <th>Value</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Updated By</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($settings)): ?>
                    <tr><td colspan="5">No settings found.</td></tr>
                <?php endif; ?>
                <?php foreach ($settings as $setting): ?>
                    <tr>
                        <td><?php echo e($setting['setting_key']); ?></td>
                        <td>
                            <form class="setting-form" method="post" action="admin.php?action=settings">
                                <input type="hidden" name="setting_id" value="<?php echo e($setting['id']); ?>">
                                <input class="setting-type" type="hidden" value="<?php echo e($setting['setting_type']); ?>">
                                <textarea class="setting-value" name="setting_value"><?php echo e($setting['setting_value']); ?></textarea>
                                <button class="small primary">Save</button>
                            </form>
                        </td>
                        <td><?php echo e($setting['setting_type']); ?></td>
                        <td><?php echo e($setting['description']); ?></td>
                        <td><?php echo e($setting['updated_by_name']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/../partials/admin_footer.php'; ?>
