<?php


function get_owned_games($data) {
    $owner = $data['owner'];

    $result = run_query('SELECT name FROM games WHERE owner = :owner', [":owner"=>$owner]);

    $owned_games = [];
    foreach ($result as $row) {
        if (!in_array($row['game_name'], $owned_games, true)) {
            $owned_games[] = $row['game_name'];
        }
    }

    return ['data' => $owned_games];
}