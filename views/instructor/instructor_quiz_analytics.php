<?php
$totalAttempts = isset($summary['total_attempts']) ? (int) $summary['total_attempts'] : 0;
$passed = isset($summary['passed']) ? (int) $summary['passed'] : 0;
$passRate = $totalAttempts > 0 ? round(($passed / $totalAttempts) * 100, 2) : 0;
$buckets = array('0-39' => 0, '40-59' => 0, '60-79' => 0, '80-100' => 0);
foreach ($attempts as $attempt) {
    $totalMarks = (float) $quiz['total_marks'];
    $score = $totalMarks > 0 ? ((float) $attempt['score'] / $totalMarks) * 100 : (float) $attempt['score'];
    if ($score < 40) { $buckets['0-39']++; }
    elseif ($score < 60) { $buckets['40-59']++; }
    elseif ($score < 80) { $buckets['60-79']++; }
    else { $buckets['80-100']++; }
}
?>
<div class="instructor-grid">
    <div class="instructor-stat"><span>Class Average</span><strong><?php echo instructorE(round((float) $summary['class_average'], 2)); ?></strong></div>
    <div class="instructor-stat"><span>Highest Score</span><strong><?php echo instructorE($summary['highest_score']); ?></strong></div>
    <div class="instructor-stat"><span>Lowest Score</span><strong><?php echo instructorE($summary['lowest_score']); ?></strong></div>
    <div class="instructor-stat"><span>Pass Rate</span><strong><?php echo instructorE($passRate); ?>%</strong></div>
</div>
<div class="instructor-card">
    <h2>Score Distribution</h2>
    <table class="instructor-table">
        <tr><th>Range</th><th>Attempts</th></tr>
        <?php foreach ($buckets as $range => $count) { ?><tr><td><?php echo instructorE($range); ?></td><td><?php echo instructorE($count); ?></td></tr><?php } ?>
    </table>
</div>
