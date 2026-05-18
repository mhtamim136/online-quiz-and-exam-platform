<?php
function taGetCourseSummaryReport($conn, $courseId, $taId, $threshold)
{
    return array(
        'total_students' => dbSelectOne($conn, "SELECT COUNT(*) AS total FROM enrollments WHERE course_id = ? AND status = 'active'", 'i', array((int)$courseId)),
        'total_quizzes' => dbSelectOne($conn, "SELECT COUNT(*) AS total FROM quizzes WHERE course_id = ?", 'i', array((int)$courseId)),
        'practice_quizzes' => dbSelectOne($conn, "SELECT COUNT(*) AS total FROM quizzes WHERE course_id = ? AND created_by = ? AND quiz_type = 'practice'", 'ii', array((int)$courseId, (int)$taId)),
        'attempts' => dbSelectOne($conn, "SELECT COUNT(attempts.id) AS total, AVG(CASE WHEN quizzes.total_marks > 0 THEN (attempts.score / quizzes.total_marks) * 100 ELSE attempts.score END) AS average_score FROM attempts INNER JOIN quizzes ON quizzes.id = attempts.quiz_id WHERE quizzes.course_id = ?", 'i', array((int)$courseId)),
        'at_risk' => dbSelectOne($conn, "SELECT COUNT(DISTINCT attempts.student_id) AS total FROM attempts INNER JOIN quizzes ON quizzes.id = attempts.quiz_id WHERE quizzes.course_id = ? AND (CASE WHEN quizzes.total_marks > 0 THEN (attempts.score / quizzes.total_marks) * 100 ELSE attempts.score END) < ?", 'id', array((int)$courseId, (float)$threshold)),
        'qa_questions' => dbSelectOne($conn, "SELECT COUNT(*) AS total FROM qa_questions WHERE course_id = ?", 'i', array((int)$courseId)),
        'doubt_sessions' => dbSelectOne($conn, "SELECT COUNT(*) AS total FROM doubt_sessions WHERE course_id = ? AND ta_id = ?", 'ii', array((int)$courseId, (int)$taId)),
        'booked_seats' => dbSelectOne($conn, "SELECT COUNT(doubt_session_bookings.id) AS total FROM doubt_session_bookings INNER JOIN doubt_sessions ON doubt_sessions.id = doubt_session_bookings.doubt_session_id WHERE doubt_sessions.course_id = ? AND doubt_sessions.ta_id = ? AND doubt_session_bookings.booking_status <> 'cancelled'", 'ii', array((int)$courseId, (int)$taId))
    );
}
