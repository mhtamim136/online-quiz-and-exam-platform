<?php include __DIR__ . '/instructor_messages.php'; ?>
<div class="instructor-card">
    <h2><?php echo instructorE($course['title']); ?></h2>
    <form method="post" class="instructor-form" data-instructor-validate>
        <label>Select TA</label>
        <select name="ta_id" data-instructor-label="TA">
            <option value="">Select TA</option>
            <?php foreach ($tas as $ta) { ?>
                <option value="<?php echo instructorE($ta['id']); ?>"><?php echo instructorE($ta['name'] . ' - ' . $ta['email']); ?></option>
            <?php } ?>
        </select>
        <div class="instructor-actions"><button type="submit">Assign TA</button></div>
    </form>
</div>
<table class="instructor-table">
    <tr><th>Assigned TA</th><th>Email</th></tr>
    <?php foreach ($assigned as $ta) { ?><tr><td><?php echo instructorE($ta['name']); ?></td><td><?php echo instructorE($ta['email']); ?></td></tr><?php } ?>
</table>
