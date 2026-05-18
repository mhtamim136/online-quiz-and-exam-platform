<div class="instructor-card"><h2><?php echo instructorE($course['title']); ?></h2><p>Approve or drop pending enrollment requests without page reload.</p></div>
<table class="instructor-table">
    <tr><th>Student</th><th>Email</th><th>Requested At</th><th>Action</th></tr>
    <?php foreach ($requests as $request) { ?>
        <tr>
            <td><?php echo instructorE($request['student_name']); ?></td>
            <td><?php echo instructorE($request['student_email']); ?></td>
            <td><?php echo instructorE($request['enrolled_at']); ?></td>
            <td>
                <button data-enrollment-id="<?php echo instructorE($request['id']); ?>" data-enrollment-action="active">Approve</button>
                <button class="instructor-btn danger" data-enrollment-id="<?php echo instructorE($request['id']); ?>" data-enrollment-action="dropped">Drop</button>
            </td>
        </tr>
    <?php } ?>
</table>
