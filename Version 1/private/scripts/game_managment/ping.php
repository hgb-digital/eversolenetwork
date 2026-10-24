<?php

function ping($data) {
    include_once(realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/features/functions.php"));
    
    function check_expired(string $id) {
        $result = run_query("SELECT id FROM sessions WHERE last_ping > NOW() - INTERVAL 10 SECOND AND session_identifier = :session_id;", [':session_id' => $id]);
        if ($result) {
            return false;
        } else {
            return true;
        }
    }
    
    if (!empty($data['session_id'])) {
        if (!check_expired($data['session_id'])) {
            run_query("UPDATE sessions SET last_ping = current_timestamp() WHERE session_identifier = :session_id", [':session_id' => $data['session_id']]);
            $session = get_session($data['session_id']);
            $players_online = run_query("SELECT COUNT(*) FROM sessions WHERE last_ping > NOW() - INTERVAL 10 SECOND AND game_version_id = :id;", [':id'=>$session['game_version_id']])[0]["COUNT(*)"];

            $return_data = ["session_id" => strval($data['session_id']), "players_online" => strval($players_online)];

            #reward user for playing
            if (isset($session['user_id'])) {
                reward("xp", $session['user_id'], 1, $session['id']);

                #get xp, coins if logged in 
                $wealth = run_query('SELECT coins, xp FROM users WHERE id = :id;', [":id" => $session['user_id']])[0];
                $return_data['xp'] = strval($wealth['xp']);
                $return_data['coins'] = strval($wealth['coins']);
            }

            return ["data" => $return_data];
        } else {
            return ["data" => ["disconnect" => true]];
        }
    } else {
        return ["errors" => ['internal_server']];
    }
}