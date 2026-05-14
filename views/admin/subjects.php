<?php require __DIR__ . '/../partials/admin_header.php'; ?>

<div class="panel">
    <h2><?php echo $editSubject ? 'Edit Subject' : 'Add Subject'; ?></h2>
    <div id="formError" class="form-error"></div>

    <?php if (!empty($errors)): ?>
        <div class="form-error server-errors">
            <?php foreach ($errors as $error): ?>
                <div><?php echo e($error); ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form class="subject-form" method="post" action="admin.php?action=subjects">
        <input type="hidden" name="subject_action" value="<?php echo $editSubject ? 'edit' : 'add'; ?>">
        <?php if ($editSubject): ?>
            <input type="hidden" name="subject_id" value="<?php echo e($editSubject['id']); ?>">
        <?php endif; ?>
        <div class="form-grid">
            <div class="form-row">
                <label for="subjectName">Subject Name</label>
                <input id="subjectName" class="subject-name" name="name" type="text" value="<?php echo $editSubject ? e($editSubject['name']) : ''; ?>">
            </div>
            <div class="form-row">
                <label for="subjectDescription">Description</label>
                <input id="subjectDescription" name="description" type="text" value="<?php echo $editSubject ? e($editSubject['description']) : ''; ?>">
            </div>
        </div>
        <button class="primary"><?php echo $editSubject ? 'Update Subject' : 'Add Subject'; ?></button>
        <?php if ($editSubject): ?>
            <a class="btn" href="admin.php?action=subjects">Cancel</a>
        <?php endif; ?>
    </form>
</div>

<div class="panel">
    <h2>Subjects</h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Courses</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($subjects)): ?>
                    <tr><td colspan="5">No subjects found.</td></tr>
                <?php endif; ?>
                <?php foreach ($subjects as $subject): ?>
                    <tr>
                        <td><?php echo e($subject['id']); ?></td>
                        <td><?php echo e($subject['name']); ?></td>
                        <td><?php echo e($subject['description']); ?></td>
                        <td><?php echo e($subject['course_count']); ?></td>
                        <td>
                            <a class="btn small" href="admin.php?action=subjects&edit_id=<?php echo e($subject['id']); ?>">Edit</a>
                            <?php if ((int)$subject['course_count'] === 0): ?>
                                <form class="inline-form confirm-form" data-confirm="Delete this subject?" method="post" action="admin.php?action=subjects">
                                    <input type="hidden" name="subject_action" value="delete">
                                    <input type="hidden" name="subject_id" value="<?php echo e($subject['id']); ?>">
                                    <button class="small danger">Delete</button>
                                </form>
                            <?php else: ?>
                                <span class="muted">In use</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/../partials/admin_footer.php'; ?>
