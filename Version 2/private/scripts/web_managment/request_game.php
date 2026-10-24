<?php

function request_game($data) {
    include_once(realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/features/functions.php"));
    include_once(realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/features/config_session.php"));
    
    $game_name = $data['game_name'];
    $type = $data['type'];
    $version = 1;

    if ($type == 'version') {
        $version = $data['game_version'];
    }
    if (isset($_SESSION['user_username'])) {
        $game_owner = $_SESSION['user_username'];
        $game_owner_id = $_SESSION['user_id'];
    } else {
        return ['errors' => ['internal_server']];
    }

    $errors = [];
    if ($type == 'game') {
        $check = addGame($game_name, $game_owner, true);
        if (!($check === true)) {
            $errors = $check;
        }
    } elseif ($type == 'version') {
        $game_id = run_query("SELECT id FROM games WHERE name = :n;", [":n" => $game_name])[0]['id'];

        $check = addGameVersion($game_id, intval($version), true);
        if (!($check === true)) {
            $errors = $check;
        }
    }

    if (isset($errors['errors'])) {
        $errors = $errors['errors'];
    }

    $exists = run_query('SELECT created_at from game_requests where game_name = :name and game_version = :version;', [':name'=>$game_name, ':version'=>$version]);
    if ($exists) {
        $errors[] = "request_exists";
    }
    
    if (!($errors === [])) {
        return ["errors" => $errors];
    }

    $ignoredata = true;
    include_once(realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/features/notifications.php"));
    if ($type == 'version') {
        run_query('INSERT INTO game_requests (game_name, game_version, user_id, type, game_id) VALUES (:name, :version, :owner, "new_version", :id)', [':name'=>$game_name, ':owner'=>$game_owner_id, ':version'=>$version, ':id' => $game_id]);
        notify('admin', 'Add Version Request - ' . $game_name . "(v" . $version . ") by " . $game_owner, 'internal',  '/admin/?url=game-requests');
    } elseif ($type == 'game') {
        run_query('INSERT INTO game_requests (game_name, game_version, user_id, type) VALUES (:name, 1, :owner, "new_game")', [':name'=>$game_name, ':owner'=>$game_owner_id]);
        notify('admin', 'Add Game Request - ' . $game_name . "(v1)" . " by " . $_SESSION['user_username'], 'internal', '/admin/?url=game-requests');
    }
    return true;
}