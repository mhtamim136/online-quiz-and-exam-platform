<h1>Doubt Sessions</h1>
<p class="student-muted">Upcoming sessions for your active courses.</p>

<?php if (empty($sessions)): ?>
    <div class="student-card">
        <p class="student-muted">No upcoming doubt sessions are available.</p>
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
            <th>Seats</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($sessions as $session): ?>
            <tr>
                <td><?php echo studentE($session['title']); ?></td>
                <td><?php echo studentE($session['course_title']); ?></td>
                <td><?php echo studentE($session['ta_name']); ?></td>
                <td><?php echo studentE($session['scheduled_at']); ?> (<?php echo studentE($session['duration_minutes']); ?> mins)</td>
                <td><?php echo studentE($session['location_or_link']); ?></td>
                <td><?php echo studentE($session['booked_count']); ?> / <?php echo studentE($session['max_attendees']); ?></td>
                <td>
                    <?php if ($session['my_booking_status'] === 'booked'): ?>
                        <span class="student-badge good">Booked</span>
                    <?php else: ?>
                        <form class="studentBookingForm" method="post" action="student.php?action=doubt_sessions">
                            <input type="hidden" name="session_id" value="<?php echo studentE($session['id']); ?>">
                            <button type="submit" class="student-btn small-btn primary">Book</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
