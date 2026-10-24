<?php

function game_logout($data) {
    header('Content-Type: application/json; charset=utf-8');

    if (isset($data)) {
        $result = run_query("UPDATE sessions SET user_id = NULL WHERE session_identifier = :session_id;", [':session_id' => $data["session_id"]]);   
    }
    return true;
}