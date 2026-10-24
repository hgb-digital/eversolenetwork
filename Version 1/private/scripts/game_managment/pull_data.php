<?php

function pull_data($data) {
    include_once realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/features/functions.php");
    
    if (!(isset($data["request"]) && isset($data["session_id"]))) {
        return ["errors" => ['empty_input']];
    }

    $request = $data['request'];
    $session_id = $data["session_id"];

    $session = get_session($session_id);
    if (!$session) {
        return ["errors" => ['invalid_session']];
    }

    if ($request == "pull_scores") {
        $return = run_query("SELECT score, user_id FROM scores WHERE game_version_id = :version_id ORDER BY score DESC;", [":version_id" => $session['game_version_id']]);
        
        $scores = [];
        foreach ($return as $row) {
            $row['user'] = get_username_from_id($row['user_id']);
            $row['score'] = strval($row['score']);
            $scores[] = $row;
        }
        
        return ["data" => ["scores" => $scores]];

    }
}