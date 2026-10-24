<?php

function save_state($data) {
    include_once realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/features/functions.php");

    $session = get_session($data['session_id']);

    #decide whether it's post or get
    if (isset($data['data'])) {
        run_query("INSERT INTO game_saves (save_data, game_version_id, user_id) VALUES (:data, :id, :uid);", [
            ":data" => $data['data'], ':id' => $session['game_version_id'], ":uid" => $session['user_id']
    ]);
        return True;
    }

    $result = run_query('SELECT save_data FROM game_saves WHERE game_version_id = :id AND user_id = :uid ORDER BY timestamp DESC', [
        ":id" => $session['game_version_id'], ":uid" => $session['user_id']
    ]);

    
    if (empty($result)){
        return ["data" => ["save_data" => null]];
    }

    return ['data' => $result[0]];

}