<?php
function instructorAuthFindByEmail($email)
{
    $sql = 'SELECT id, name, email, password_hash, role, approval_status, is_active FROM users WHERE email = ? LIMIT 1';
    return instructorDbFetchOne($sql, 's', array($email));
}
