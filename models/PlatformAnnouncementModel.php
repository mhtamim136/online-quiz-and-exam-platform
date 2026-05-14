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
