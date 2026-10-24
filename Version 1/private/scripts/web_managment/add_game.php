<?php

function add_game($data) {
    include_once(realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/features/functions.php"));
    $result = addGame($data['game_name'], $data["game_owner"]);
    if ($result === true) {
        return true;
    } else {
        return $result;
    }
}