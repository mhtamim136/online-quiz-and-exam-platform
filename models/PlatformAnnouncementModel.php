<?php
function platformAnnouncementGetAll($conn)
{
    $sql = "SELECT platform_announcements.*, users.name AS author_name
            FROM platform_announcements
            INNER JOIN users ON users.id = platform_announcements.author_id
            ORDER BY platform_announcements.created_at DESC";
    return dbSelectAll($conn, $sql);
}

function platformAnnouncementFindById($conn, $id)
{
    $sql = "SELECT * FROM platform_announcements WHERE id = ? LIMIT 1";
    return dbSelectOne($conn, $sql, 'i', array((int)$id));
}

function platformAnnouncementCreate($conn, $author_id, $title, $body, $audience, $status, $publish_from, $publish_until)
{
    $sql = "INSERT INTO platform_announcements
            (author_id, title, body, audience, status, publish_from, publish_until)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    return dbExecute($conn, $sql, 'issssss', array((int)$author_id, $title, $body, $audience, $status, $publish_from, $publish_until));
}

function platformAnnouncementUpdate($conn, $id, $title, $body, $audience, $status, $publish_from, $publish_until)
{
    $sql = "UPDATE platform_announcements
            SET title = ?, body = ?, audience = ?, status = ?, publish_from = ?, publish_until = ?
            WHERE id = ?";
    return dbExecute($conn, $sql, 'ssssssi', array($title, $body, $audience, $status, $publish_from, $publish_until, (int)$id));
}

function platformAnnouncementArchive($conn, $id)
{
    $sql = "UPDATE platform_announcements SET status = 'archived' WHERE id = ?";
    return dbExecute($conn, $sql, 'i', array((int)$id));
}

function platformAnnouncementGetVisibleForRole($conn, $role, $limit = 5)
{
    $audience = 'all';

    if ($role === 'student') {
        $audience = 'students';
    } elseif ($role === 'instructor') {
        $audience = 'instructors';
    } elseif ($role === 'ta') {
        $audience = 'tas';
    }

    $limit = (int)$limit;
    if ($limit <= 0) {
        $limit = 5;
    }

    $sql = "SELECT title, body, audience, publish_from, publish_until, created_at
            FROM platform_announcements
            WHERE status = 'published'
              AND audience IN (?, ?)
              AND (publish_from IS NULL OR publish_from <= NOW())
              AND (publish_until IS NULL OR publish_until >= NOW())
            ORDER BY created_at DESC
            LIMIT ?";
    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return array();
    }

    $allAudience = 'all';
    mysqli_stmt_bind_param($stmt, 'ssi', $allAudience, $audience, $limit);

    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        return array();
    }

    $result = mysqli_stmt_get_result($stmt);
    $rows = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    mysqli_stmt_close($stmt);
    return $rows;
}
