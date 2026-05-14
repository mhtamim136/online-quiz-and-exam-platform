<?php
function userFindByEmail($conn, $email)
{
    $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
    return dbSelectOne($conn, $sql, 's', array($email));
}

function userFindById($conn, $id)
{
    $sql = "SELECT * FROM users WHERE id = ? LIMIT 1";
    return dbSelectOne($conn, $sql, 'i', array((int)$id));
}

function userEmailExists($conn, $email, $exclude_id = 0)
{
    $sql = "SELECT id FROM users WHERE email = ? AND id <> ? LIMIT 1";
    $row = dbSelectOne($conn, $sql, 'si', array($email, (int)$exclude_id));
    return $row !== null;
}

function userUsernameExists($conn, $username, $exclude_id = 0)
{
    $sql = "SELECT id FROM users WHERE username = ? AND id <> ? LIMIT 1";
    $row = dbSelectOne($conn, $sql, 'si', array($username, (int)$exclude_id));
    return $row !== null;
}

function userGetAll($conn, $keyword, $role, $active)
{
    $sql = "SELECT id, username, name, email, phone, role, student_id, program, department,
                   approval_status, is_active, created_at
            FROM users";
    $where = array();
    $types = '';
    $params = array();

    if ($keyword !== '') {
        $like = '%' . $keyword . '%';
        $where[] = "(name LIKE ? OR email LIKE ? OR username LIKE ? OR student_id LIKE ? OR phone LIKE ?)";
        $types .= 'sssss';
        $params[] = $like;
        $params[] = $like;
        $params[] = $like;
        $params[] = $like;
        $params[] = $like;
    }

    if ($role !== '') {
        $where[] = "role = ?";
        $types .= 's';
        $params[] = $role;
    }

    if ($active !== '') {
        $where[] = "is_active = ?";
        $types .= 'i';
        $params[] = (int)$active;
    }

    if (count($where) > 0) {
        $sql .= ' WHERE ' . implode(' AND ', $where);
    }

    $sql .= " ORDER BY created_at DESC, id DESC";

    return dbSelectAll($conn, $sql, $types, $params);
}

function userSearchForAjax($conn, $keyword, $role, $active)
{
    $sql = "SELECT id, username, name, email, phone, role, student_id, approval_status, is_active, created_at
            FROM users";
    $where = array();
    $types = '';
    $params = array();

    if ($keyword !== '') {
        $like = '%' . $keyword . '%';
        $where[] = "(name LIKE ? OR email LIKE ? OR username LIKE ? OR student_id LIKE ? OR phone LIKE ?)";
        $types .= 'sssss';
        $params[] = $like;
        $params[] = $like;
        $params[] = $like;
        $params[] = $like;
        $params[] = $like;
    }

    if ($role !== '') {
        $where[] = "role = ?";
        $types .= 's';
        $params[] = $role;
    }

    if ($active !== '') {
        $where[] = "is_active = ?";
        $types .= 'i';
        $params[] = (int)$active;
    }

    if (count($where) > 0) {
        $sql .= ' WHERE ' . implode(' AND ', $where);
    }

    $sql .= " ORDER BY created_at DESC, id DESC LIMIT 50";

    return dbSelectAll($conn, $sql, $types, $params);
}

function userSetActiveStatus($conn, $user_id, $is_active)
{
    $sql = "UPDATE users SET is_active = ? WHERE id = ?";
    return dbExecute($conn, $sql, 'ii', array((int)$is_active, (int)$user_id));
}

function userChangeRole($conn, $user_id, $role)
{
    $sql = "UPDATE users SET role = ? WHERE id = ?";
    return dbExecute($conn, $sql, 'si', array($role, (int)$user_id));
}

function userUpdateInstructorApproval($conn, $user_id, $approval_status, $is_active)
{
    $sql = "UPDATE users
            SET approval_status = ?, is_active = ?
            WHERE id = ? AND role = 'instructor'";
    return dbExecute($conn, $sql, 'sii', array($approval_status, (int)$is_active, (int)$user_id));
}

function userGetPendingInstructors($conn)
{
    $sql = "SELECT id, username, name, email, phone, department, bio, created_at
            FROM users
            WHERE role = 'instructor' AND approval_status = 'pending'
            ORDER BY created_at DESC";
    return dbSelectAll($conn, $sql);
}

function userCreateTa($conn, $data)
{
    $sql = "INSERT INTO users
            (username, name, email, password_hash, phone, role, department, bio, approval_status, is_active)
            VALUES (?, ?, ?, ?, ?, 'ta', ?, ?, 'approved', 1)";

    return dbExecute(
        $conn,
        $sql,
        'sssssss',
        array(
            $data['username'],
            $data['name'],
            $data['email'],
            $data['password_hash'],
            $data['phone'],
            $data['department'],
            $data['bio']
        )
    );
}

function userGetStudentsByKeyword($conn, $keyword)
{
    $like = '%' . $keyword . '%';
    $sql = "SELECT id, name, email, student_id, program, phone, is_active
            FROM users
            WHERE role = 'student'
              AND (name LIKE ? OR student_id LIKE ? OR email LIKE ?)
            ORDER BY name ASC
            LIMIT 20";
    return dbSelectAll($conn, $sql, 'sss', array($like, $like, $like));
}
