<?php
function studentGetSubjects()
{
    global $studentConn;

    $sql = "SELECT id, name FROM subjects ORDER BY name ASC";
    $stmt = mysqli_prepare($studentConn, $sql);
    return studentFetchAll($stmt);
}

function studentGetActiveCourses($keyword, $subjectId, $studentId)
{
    global $studentConn;

    $sql = "SELECT c.id, c.title, c.description, c.enrollment_type, c.max_students,
                   s.name AS subject_name, u.name AS instructor_name,
                   COUNT(CASE WHEN e_count.status = 'active' THEN 1 END) AS enrolled_count,
                   my_e.status AS my_status
            FROM courses c
            INNER JOIN subjects s ON s.id = c.subject_id
            INNER JOIN users u ON u.id = c.instructor_id
            LEFT JOIN enrollments e_count ON e_count.course_id = c.id
            LEFT JOIN enrollments my_e ON my_e.course_id = c.id AND my_e.student_id = ?
            WHERE c.status = 'active'";

    $types = 'i';
    $params = [$studentId];

    if ($keyword !== '') {
        $sql .= " AND (c.title LIKE ? OR c.description LIKE ? OR u.name LIKE ?)";
        $like = '%' . $keyword . '%';
        $types .= 'sss';
        $params[] = $like;
        $params[] = $like;
        $params[] = $like;
    }

    if ($subjectId > 0) {
        $sql .= " AND c.subject_id = ?";
        $types .= 'i';
        $params[] = $subjectId;
    }

    $sql .= " GROUP BY c.id, c.title, c.description, c.enrollment_type, c.max_students, s.name, u.name, my_e.status
              ORDER BY c.title ASC";

    $stmt = mysqli_prepare($studentConn, $sql);
    studentBindParams($stmt, $types, $params);
    return studentFetchAll($stmt);
}

function studentGetCourseById($courseId, $studentId)
{
    global $studentConn;

    $sql = "SELECT c.id, c.title, c.description, c.enrollment_type, c.max_students, c.status,
                   s.name AS subject_name, u.name AS instructor_name, u.email AS instructor_email,
                   my_e.status AS my_status,
                   COUNT(CASE WHEN e_count.status = 'active' THEN 1 END) AS enrolled_count
            FROM courses c
            INNER JOIN subjects s ON s.id = c.subject_id
            INNER JOIN users u ON u.id = c.instructor_id
            LEFT JOIN enrollments my_e ON my_e.course_id = c.id AND my_e.student_id = ?
            LEFT JOIN enrollments e_count ON e_count.course_id = c.id
            WHERE c.id = ? AND c.status = 'active'
            GROUP BY c.id, c.title, c.description, c.enrollment_type, c.max_students, c.status,
                     s.name, u.name, u.email, my_e.status
            LIMIT 1";
    $stmt = mysqli_prepare($studentConn, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $studentId, $courseId);
    return studentFetchOne($stmt);
}

function studentGetCourseTas($courseId)
{
    global $studentConn;

    $sql = "SELECT u.name, u.email
            FROM course_tas ct
            INNER JOIN users u ON u.id = ct.ta_id
            WHERE ct.course_id = ?
            ORDER BY u.name ASC";
    $stmt = mysqli_prepare($studentConn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $courseId);
    return studentFetchAll($stmt);
}

function studentGetEnrollment($studentId, $courseId)
{
    global $studentConn;

    $sql = "SELECT id, status FROM enrollments WHERE student_id = ? AND course_id = ? LIMIT 1";
    $stmt = mysqli_prepare($studentConn, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $studentId, $courseId);
    return studentFetchOne($stmt);
}

function studentEnrollCourse($studentId, $courseId)
{
    global $studentConn;

    $courseSql = "SELECT id, enrollment_type FROM courses WHERE id = ? AND status = 'active' LIMIT 1";
    $stmt = mysqli_prepare($studentConn, $courseSql);
    mysqli_stmt_bind_param($stmt, 'i', $courseId);
    $course = studentFetchOne($stmt);

    if (!$course) {
        return ['success' => false, 'message' => 'Course was not found or is not active.'];
    }

    $existing = studentGetEnrollment($studentId, $courseId);
    if ($existing && $existing['status'] !== 'dropped') {
        return ['success' => false, 'message' => 'You already have an enrollment record for this course.'];
    }

    if ($existing && $existing['status'] === 'dropped') {
        return ['success' => false, 'message' => 'This course was previously dropped and cannot be enrolled again in this demo.'];
    }

    $status = $course['enrollment_type'] === 'open' ? 'active' : 'pending';
    $sql = "INSERT INTO enrollments (student_id, course_id, status, enrolled_at) VALUES (?, ?, ?, NOW())";
    $stmt = mysqli_prepare($studentConn, $sql);
    mysqli_stmt_bind_param($stmt, 'iis', $studentId, $courseId, $status);
    $done = mysqli_stmt_execute($stmt);

    if (!$done) {
        return ['success' => false, 'message' => 'Enrollment could not be saved.'];
    }

    if ($status === 'active') {
        return ['success' => true, 'message' => 'You are now enrolled in this course.'];
    }

    return ['success' => true, 'message' => 'Enrollment request submitted for approval.'];
}

function studentHasCompletedGradedQuizInCourse($studentId, $courseId)
{
    global $studentConn;

    $sql = "SELECT a.id
            FROM attempts a
            INNER JOIN quizzes q ON q.id = a.quiz_id
            WHERE a.student_id = ?
              AND q.course_id = ?
              AND q.quiz_type = 'graded'
              AND a.completed_at IS NOT NULL
            LIMIT 1";
    $stmt = mysqli_prepare($studentConn, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $studentId, $courseId);
    $row = studentFetchOne($stmt);
    return $row ? true : false;
}

function studentDropCourse($studentId, $courseId)
{
    global $studentConn;

    $enrollment = studentGetEnrollment($studentId, $courseId);

    if (!$enrollment || $enrollment['status'] !== 'active') {
        return ['success' => false, 'message' => 'Only active enrolled courses can be dropped.'];
    }

    if (studentHasCompletedGradedQuizInCourse($studentId, $courseId)) {
        return ['success' => false, 'message' => 'You cannot drop this course because a graded quiz has already been completed.'];
    }

    $sql = "UPDATE enrollments SET status = 'dropped' WHERE student_id = ? AND course_id = ?";
    $stmt = mysqli_prepare($studentConn, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $studentId, $courseId);
    $done = mysqli_stmt_execute($stmt);

    return [
        'success' => $done,
        'message' => $done ? 'Course dropped successfully.' : 'Course could not be dropped.'
    ];
}

function studentIsActiveInCourse($studentId, $courseId)
{
    $enrollment = studentGetEnrollment($studentId, $courseId);
    return $enrollment && $enrollment['status'] === 'active';
}

function studentGetEnrolledCourses($studentId)
{
    global $studentConn;

    $sql = "SELECT e.status, e.enrolled_at, c.id, c.title, c.description,
                   s.name AS subject_name, u.name AS instructor_name,
                   (
                       SELECT q.title
                       FROM quizzes q
                       WHERE q.course_id = c.id
                         AND q.status = 'published'
                         AND (q.available_from IS NULL OR q.available_from <= NOW())
                         AND (q.available_until IS NULL OR q.available_until >= NOW())
                       ORDER BY q.available_until IS NULL, q.available_until ASC
                       LIMIT 1
                   ) AS next_quiz
            FROM enrollments e
            INNER JOIN courses c ON c.id = e.course_id
            INNER JOIN subjects s ON s.id = c.subject_id
            INNER JOIN users u ON u.id = c.instructor_id
            WHERE e.student_id = ?
            ORDER BY e.enrolled_at DESC";
    $stmt = mysqli_prepare($studentConn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $studentId);
    return studentFetchAll($stmt);
}

function studentGetCourseMaterials($courseId, $studentId)
{
    global $studentConn;

    $sql = "SELECT m.id, m.title, m.file_path, m.material_type, m.created_at, u.name AS uploaded_by_name
            FROM course_materials m
            INNER JOIN users u ON u.id = m.uploaded_by
            INNER JOIN enrollments e ON e.course_id = m.course_id
            WHERE m.course_id = ?
              AND e.student_id = ?
              AND e.status = 'active'
            ORDER BY m.created_at DESC";
    $stmt = mysqli_prepare($studentConn, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $courseId, $studentId);
    return studentFetchAll($stmt);
}

function studentGetCourseAnnouncements($courseId, $studentId, $limit)
{
    global $studentConn;

    $sql = "SELECT a.id, a.title, a.body, a.created_at, u.name AS author_name
            FROM announcements a
            INNER JOIN users u ON u.id = a.author_id
            INNER JOIN enrollments e ON e.course_id = a.course_id
            WHERE a.course_id = ?
              AND e.student_id = ?
              AND e.status = 'active'
            ORDER BY a.created_at DESC
            LIMIT ?";
    $stmt = mysqli_prepare($studentConn, $sql);
    mysqli_stmt_bind_param($stmt, 'iii', $courseId, $studentId, $limit);
    return studentFetchAll($stmt);
}
?>
