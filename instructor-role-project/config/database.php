<?php
function instructorDb()
{
    static $connection = null;

    if ($connection === null) {
        $connection = mysqli_connect('localhost', 'root', '', 'online_quiz_exam_platform');
        if (!$connection) {
            die('Database connection failed.');
        }
        mysqli_set_charset($connection, 'utf8mb4');
    }

    return $connection;
}

function instructorDbStatement($sql, $types = '', $params = array())
{
    $connection = instructorDb();
    $statement = mysqli_prepare($connection, $sql);

    if (!$statement) {
        die('Database statement failed.');
    }

    if ($types !== '' && count($params) > 0) {
        $bindValues = array($statement, $types);
        foreach ($params as $key => $value) {
            $bindValues[] = &$params[$key];
        }
        call_user_func_array('mysqli_stmt_bind_param', $bindValues);
    }

    mysqli_stmt_execute($statement);
    return $statement;
}

function instructorDbFetchAll($sql, $types = '', $params = array())
{
    $statement = instructorDbStatement($sql, $types, $params);
    $result = mysqli_stmt_get_result($statement);
    $rows = array();

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
    }

    mysqli_stmt_close($statement);
    return $rows;
}

function instructorDbFetchOne($sql, $types = '', $params = array())
{
    $rows = instructorDbFetchAll($sql, $types, $params);
    return isset($rows[0]) ? $rows[0] : null;
}

function instructorDbExecute($sql, $types = '', $params = array())
{
    $statement = instructorDbStatement($sql, $types, $params);
    $affectedRows = mysqli_stmt_affected_rows($statement);
    mysqli_stmt_close($statement);
    return $affectedRows;
}

function instructorDbLastId()
{
    return mysqli_insert_id(instructorDb());
}
