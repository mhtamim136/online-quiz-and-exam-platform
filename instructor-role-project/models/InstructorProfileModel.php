<?php
function instructorProfileFind($instructorId)
{
    return instructorDbFetchOne('SELECT id, name, email, department, bio, profile_pic, created_at FROM users WHERE id = ? AND role = ?', 'is', array($instructorId, 'instructor'));
}

function instructorProfileUpdate($instructorId, $name, $department, $bio, $profilePic)
{
    if ($profilePic !== '') {
        return instructorDbExecute('UPDATE users SET name = ?, department = ?, bio = ?, profile_pic = ? WHERE id = ? AND role = ?', 'ssssis', array($name, $department, $bio, $profilePic, $instructorId, 'instructor'));
    }
    return instructorDbExecute('UPDATE users SET name = ?, department = ?, bio = ? WHERE id = ? AND role = ?', 'sssis', array($name, $department, $bio, $instructorId, 'instructor'));
}
