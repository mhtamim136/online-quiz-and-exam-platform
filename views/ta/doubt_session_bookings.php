<?php require __DIR__ . '/../partials/ta_header.php'; ?>

<?php
$booked = count($bookings);
$remaining = (int)$session['max_attendees'] - $booked;
if ($remaining < 0) {
    $remaining = 0;
}
?>
<div class="ta-card">
    <h2><?php echo taE($session['title']); ?></h2>
    <p class="ta-muted">Booked seats: <?php echo taE($booked); ?> | Remaining: <?php echo taE($remaining); ?></p>
</div>

<div class="ta-card">
    <table class="ta-table">
        <thead><tr><th>Student</th><th>Email</th><th>Student ID</th><th>Program</th><th>Status</th><th>Booked At</th></tr></thead>
        <tbody>
            <?php foreach ($bookings as $booking): ?>
                <tr>
                    <td><?php echo taE($booking['student_name']); ?></td>
                    <td><?php echo taE($booking['email']); ?></td>
                    <td><?php echo taE($booking['student_id']); ?></td>
                    <td><?php echo taE($booking['program']); ?></td>
                    <td><?php echo taE($booking['booking_status']); ?></td>
                    <td><?php echo taE($booking['booked_at']); ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (count($bookings) === 0): ?>
                <tr><td colspan="6">No bookings found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../partials/ta_footer.php'; ?>
