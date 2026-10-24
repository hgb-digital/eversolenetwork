<?php

function game_init($data) {

    #check valid args
    include_once(realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/features/functions.php"));
    if (check_empty([$data['session_gameid'], $data['session_gamepwd'], $data['session_gameversion'], $data['_version']])) {
        return ["errors" => ['internal_server']];
    }

    include_once(realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/version.php"));
    if ($data['_version'] !== $_SERVER_VERSION) {
            return ["errors" => ['version_mismatch']];
    }

    #useful functions
    function check_sessionid(string $id) {
        $result = run_query("SELECT id FROM sessions WHERE id = :session_id;", [':session_id' => $id]);
    
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    function disconnect_ips(string $ip) {
        run_query("UPDATE sessions SET last_ping = '2000-01-01 00:00:00' WHERE ip = :ip;", [':ip' => $ip]);
    }

    #check valid credentials
    if (validate_gamepwd($data['session_gamepwd'], $data['session_gameid'])) {
        $game_name = get_game($data['session_gameid'])['name'];       
        disconnect_ips($_SERVER['REMOTE_ADDR']);

        $session_id = rand(100000000, 999999999);
        while (check_sessionid($session_id)) {
            $session_id = rand(1000000, 9999999);
        }

        $game_version_id = run_query("SELECT id FROM game_versions WHERE game_id = :id AND version_number = :version;", [':id' => $data['session_gameid'], ":version" => $data["session_gameversion"]])[0]["id"];

        run_query("INSERT INTO sessions (game_version_id, ip, session_identifier) VALUES (:game_id, :ip, :id)", [':game_id' => $game_version_id,':ip' => $_SERVER['REMOTE_ADDR'],':id' => $session_id]);
        $players_online = run_query("SELECT COUNT(*) FROM sessions WHERE last_ping > NOW() - INTERVAL 10 SECOND AND game_version_id = :id;", [':id'=>$game_version_id])[0]["COUNT(*)"];

        #get error messages to cache
        $error_file = file_get_contents(realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/error_text.json"));
        $error_json = json_decode($error_file, true);
        return ["data" => ["session_id" => strval($session_id), "version" => $_SERVER_VERSION, "players_online" => strval($players_online), "game_name" => $game_name,
        "error_messages" => $error_json
        ]];
    } else {
        return ["errors" => ['invalid_game_id']];
    }
}