<?php


function get_owned_games($data) {
    $owner = $data['owner'];

    $user_id = run_query("SELECT id FROM users WHERE username = :u;", [':u' => $data['owner']]);
    if (!$user_id) {
        return false;
    }
    $user_id = $user_id[0]['id'];

    $result = run_query('SELECT name FROM games WHERE owner_id = :owner', [":owner"=>$user_id]);

    $owned_games = [];
    foreach ($result as $row) {
        if (!in_array($row['name'], $owned_games, true)) {
            $owned_games[] = $row['name'];
        }
    }

    return ['data' => $owned_games];
}