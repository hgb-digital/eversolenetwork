<?php

function add_game_version($data) {
    $result = addGameVersion($data["game_name"], $data["game_version"]);
    if ($result === true) {
        return true;
    } else {
        return $result;
    }
}