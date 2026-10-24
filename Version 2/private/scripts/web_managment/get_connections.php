<?php

function get_connections($data) {
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/features/functions.php");

    $result = run_query("SELECT * FROM sessions WHERE last_ping > NOW() - INTERVAL 10 SECOND;");

    $connections = [];
    if ($result) {
        foreach ($result as $row) {
            $game_id = run_query("SELECT game_id FROM game_versions WHERE id = :id;", [":id" => $row["game_version_id"]])[0]["game_id"];
            $row["game"] = get_game($game_id)['name'];
            if ($row["user_id"]) {
                $row["username"] = get_username_from_id($row["user_id"]);
            } else {
                $row['username'] = "None";
            }
            $row['earned'] = $row['logged_scores'] . " Scores - " . $row['earned_xp'] . " XP - " . $row['earned_coins'] . " Coins";

            $connections[] = $row;
        }
    }

    return ['data' => $connections];
}