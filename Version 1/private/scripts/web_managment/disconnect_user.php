<?php

function disconnect_user($data) {

    $id = $data['id'];

    run_query("UPDATE sessions SET last_ping = '2000-01-01 00:00:00' WHERE id = :session_id;", [':session_id' => $id]);
    return true;
}