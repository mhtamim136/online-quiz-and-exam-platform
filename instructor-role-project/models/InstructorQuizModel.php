<?php
function instructorQuizAllByCourse($courseId, $instructorId)
{
    $sql = 'SELECT q.*
            FROM quizzes q
            INNER JOIN courses c ON c.id = q.course_id
            WHERE q.course_id = ? AND c.instructor_id = ?
            ORDER BY q.created_at DESC';
    return instructorDbFetchAll($sql, 'ii', array($courseId, $instructorId));
}

function instructorQuizFind($quizId, $instructorId)
{
    $sql = 'SELECT q.*
            FROM quizzes q
            INNER JOIN courses c ON c.id = q.course_id
            WHERE q.id = ? AND c.instructor_id = ?
            LIMIT 1';
    return instructorDbFetchOne($sql, 'ii', array($quizId, $instructorId));
}

function instructorQuizCreate($courseId, $instructorId, $title, $description, $quizType, $timeLimit, $totalMarks, $passMark, $availableFrom, $availableTo)
{
    $course = instructorCourseFind($courseId, $instructorId);
    if (!$course) {
        return 0;
    }
    $sql = 'INSERT INTO quizzes (course_id, created_by, title, description, quiz_type, time_limit_minutes, total_marks, pass_mark, available_from, available_until, status, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())';
    instructorDbExecute($sql, 'iisssiddsss', array($courseId, $instructorId, $title, $description, $quizType, $timeLimit, $totalMarks, $passMark, $availableFrom, $availableTo, 'draft'));
    return instructorDbLastId();
}

function instructorQuizUpdate($quizId, $instructorId, $title, $description, $quizType, $timeLimit, $totalMarks, $passMark, $availableFrom, $availableTo)
{
    $sql = 'UPDATE quizzes q
            INNER JOIN courses c ON c.id = q.course_id
            SET q.title = ?, q.description = ?, q.quiz_type = ?, q.time_limit_minutes = ?, q.total_marks = ?, q.pass_mark = ?, q.available_from = ?, q.available_until = ?
            WHERE q.id = ? AND c.instructor_id = ?';
    return instructorDbExecute($sql, 'sssiddssii', array($title, $description, $quizType, $timeLimit, $totalMarks, $passMark, $availableFrom, $availableTo, $quizId, $instructorId));
}

function instructorQuizTogglePublished($quizId, $instructorId)
{
    $quiz = instructorQuizFind($quizId, $instructorId);
    if (!$quiz) {
        return 0;
    }
    $newStatus = $quiz['status'] === 'published' ? 'draft' : 'published';
    return instructorDbExecute('UPDATE quizzes SET status = ? WHERE id = ?', 'si', array($newStatus, $quizId));
}
