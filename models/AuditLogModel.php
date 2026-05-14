<?php
function auditLogGetAll($conn, $action, $admin_id)
{
    $sql = "SELECT audit_logs.*, users.name AS admin_name
            FROM audit_logs
            LEFT JOIN users ON users.id = audit_logs.admin_id";
    $where = array();
    $types = '';
    $params = array();

    if ($action !== '') {
        $where[] = "audit_logs.action = ?";
        $types .= 's';
        $params[] = $action;
    }

    if ($admin_id > 0) {
        $where[] = "audit_logs.admin_id = ?";
        $types .= 'i';
        $params[] = (int)$admin_id;
    }

    if (count($where) > 0) {
        $sql .= ' WHERE ' . implode(' AND ', $where);
    }

    $sql .= " ORDER BY audit_logs.created_at DESC LIMIT 200";
    return dbSelectAll($conn, $sql, $types, $params);
}

function auditLogGetActions($conn)
{
    return dbSelectAll($conn, "SELECT DISTINCT action FROM audit_logs ORDER BY action ASC");
}

function auditLogGetAdmins($conn)
{
    return dbSelectAll(
        $conn,
        "SELECT DISTINCT users.id, users.name
         FROM audit_logs
         INNER JOIN users ON users.id = audit_logs.admin_id
         ORDER BY users.name ASC"
    );
}
