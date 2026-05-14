<?php
function platformSettingGetAll($conn)
{
    $sql = "SELECT platform_settings.*, users.name AS updated_by_name
            FROM platform_settings
            LEFT JOIN users ON users.id = platform_settings.updated_by
            ORDER BY setting_key ASC";
    return dbSelectAll($conn, $sql);
}

function platformSettingFindById($conn, $id)
{
    $sql = "SELECT * FROM platform_settings WHERE id = ? LIMIT 1";
    return dbSelectOne($conn, $sql, 'i', array((int)$id));
}

function platformSettingUpdate($conn, $id, $setting_value, $updated_by)
{
    $sql = "UPDATE platform_settings
            SET setting_value = ?, updated_by = ?
            WHERE id = ?";
    return dbExecute($conn, $sql, 'sii', array($setting_value, (int)$updated_by, (int)$id));
}
