<h1>Course Leaderboard</h1>
<p class="student-muted">Top student scores for the selected quiz.</p>

<section class="student-card">
    <label for="leaderboardQuizSelect">Select Quiz</label>
    <select id="leaderboardQuizSelect">
        <?php foreach ($quizChoices as $quiz): ?>
            <option value="<?php echo studentE($quiz['id']); ?>" <?php echo (int)$selectedQuizId === (int)$quiz['id'] ? 'selected' : ''; ?>>
                <?php echo studentE($quiz['course_title']); ?> - <?php echo studentE($quiz['title']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <p id="leaderboardMessage" class="student-muted"></p>

    <table class="student-table">
        <thead>
        <tr>
            <th>Rank</th>
            <th>Student</th>
            <th>Student ID</th>
            <th>Best Score</th>
            <th>Attempts</th>
        </tr>
        </thead>
        <tbody id="leaderboardBody"></tbody>
    </table>
</section>
