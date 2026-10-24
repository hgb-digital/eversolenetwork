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
        $game_owner = $_SESSION['user_id'];
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
        $check = addGameVersion($game_name, $version, true);
        if (!($check === true)) {
            $errors = $check;
        }
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
        run_query('INSERT INTO game_requests (game_name, game_version, user_id, type) VALUES (:name, :version, :owner, 1)', [':name'=>$game_name, ':owner'=>$game_owner, ':version'=>$version]);
        notify('admin', 'Add Version Request - ' . $game_name . "(v" . $version . ") by " . $game_owner, 'internal', $notification_pdo, '/admin/?url=game-requests');
    } elseif ($type == 'game') {
        run_query('INSERT INTO game_requests (game_name, game_version, user_id, type) VALUES (:name, 1, :owner, 0)', [':name'=>$game_name, ':owner'=>$game_owner]);
        notify('admin', 'Add Game Request - ' . $game_name . "(v1)" . " by " . $_SESSION['user_username'], 'internal', $notification_pdo, '/admin/?url=game-requests');
    }
    return true;
}