<?php
function integrityReportGetAll($conn, $status, $severity)
{
    $sql = "SELECT integrity_reports.*, student.name AS student_name, reporter.name AS reporter_name,
                   quizzes.title AS quiz_title
            FROM integrity_reports
            LEFT JOIN users AS student ON student.id = integrity_reports.student_id
            LEFT JOIN users AS reporter ON reporter.id = integrity_reports.reported_by
            LEFT JOIN quizzes ON quizzes.id = integrity_reports.quiz_id";
    $where = array();
    $types = '';
    $params = array();

    if ($status !== '') {
        $where[] = "integrity_reports.status = ?";
        $types .= 's';
        $params[] = $status;
    }

    if ($severity !== '') {
        $where[] = "integrity_reports.severity = ?";
        $types .= 's';
        $params[] = $severity;
    }

    if (count($where) > 0) {
        $sql .= ' WHERE ' . implode(' AND ', $where);
    }

    $sql .= " ORDER BY integrity_reports.created_at DESC";
    return dbSelectAll($conn, $sql, $types, $params);
}

function integrityReportFindById($conn, $id)
{
    $sql = "SELECT integrity_reports.*, student.name AS student_name, reporter.name AS reporter_name,
                   resolver.name AS resolved_by_name, quizzes.title AS quiz_title
            FROM integrity_reports
            LEFT JOIN users AS student ON student.id = integrity_reports.student_id
            LEFT JOIN users AS reporter ON reporter.id = integrity_reports.reported_by
            LEFT JOIN users AS resolver ON resolver.id = integrity_reports.resolved_by
            LEFT JOIN quizzes ON quizzes.id = integrity_reports.quiz_id
            WHERE integrity_reports.id = ?
            LIMIT 1";
    return dbSelectOne($conn, $sql, 'i', array((int)$id));
}

function integrityReportUpdateStatus($conn, $id, $status, $resolution_notes, $resolved_by)
{
    $sql = "UPDATE integrity_reports
            SET status = ?, resolution_notes = ?, resolved_by = ?, resolved_at = NOW()
            WHERE id = ?";
    return dbExecute($conn, $sql, 'ssii', array($status, $resolution_notes, (int)$resolved_by, (int)$id));
}
