<?php include __DIR__ . '/instructor_messages.php'; ?>
<div class="instructor-card">
    <form method="post" class="instructor-form" data-instructor-validate>
        <label>Subject</label>
        <select name="subject_id" data-instructor-label="Subject">
            <option value="">Select subject</option>
            <?php foreach ($subjects as $subject) { ?>
                <option value="<?php echo instructorE($subject['id']); ?>" <?php echo (int) $course['subject_id'] === (int) $subject['id'] ? 'selected' : ''; ?>>
                    <?php echo instructorE($subject['name']); ?>
                </option>
            <?php } ?>
        </select>
        <label>Course Title</label>
        <input name="title" value="<?php echo instructorE($course['title']); ?>" data-instructor-label="Course title">
        <label>Enrollment Type</label>
        <select name="enrollment_type">
            <option value="open" <?php echo $course['enrollment_type'] === 'open' ? 'selected' : ''; ?>>Open</option>
            <option value="approval" <?php echo $course['enrollment_type'] === 'approval' ? 'selected' : ''; ?>>Approval</option>
        </select>
        <label>Maximum Students</label>
        <input name="max_students" value="<?php echo instructorE($course['max_students']); ?>" data-instructor-label="Maximum students">
        <label>Status</label>
        <select name="status">
            <option value="draft" <?php echo $course['status'] === 'draft' ? 'selected' : ''; ?>>Draft</option>
            <option value="active" <?php echo $course['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
            <option value="archived" <?php echo $course['status'] === 'archived' ? 'selected' : ''; ?>>Archived</option>
        </select>
        <label>Description</label>
        <textarea name="description"><?php echo instructorE($course['description']); ?></textarea>
        <div class="instructor-actions"><button type="submit">Save Course</button></div>
    </form>
</div>
