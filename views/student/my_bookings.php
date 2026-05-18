<h1>My Doubt Session Bookings</h1>

<?php if (empty($bookings)): ?>
    <div class="student-card">
        <p class="student-muted">No doubt session bookings found.</p>
    </div>
<?php else: ?>
    <table class="student-table">
        <thead>
        <tr>
            <th>Session</th>
            <th>Course</th>
            <th>TA</th>
            <th>Schedule</th>
            <th>Location/Link</th>
            <th>Booking Status</th>
            <th>Session Status</th>
            <th>Booked At</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($bookings as $booking): ?>
            <tr>
                <td><?php echo studentE($booking['title']); ?></td>
                <td><?php echo studentE($booking['course_title']); ?></td>
                <td><?php echo studentE($booking['ta_name']); ?></td>
                <td><?php echo studentE($booking['scheduled_at']); ?> (<?php echo studentE($booking['duration_minutes']); ?> mins)</td>
                <td><?php echo studentE($booking['location_or_link']); ?></td>
                <td><?php echo studentE($booking['booking_status']); ?></td>
                <td><?php echo studentE($booking['session_status']); ?></td>
                <td><?php echo studentE($booking['booked_at']); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
