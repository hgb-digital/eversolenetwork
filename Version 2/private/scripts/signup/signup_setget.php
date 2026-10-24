<?php

declare(strict_types=1);

function get_username(string $username) {
    $result = run_query("SELECT username FROM users WHERE username = :username;", [':username' => $username]);
    if (isset($result[0])) {
        return $result[0];
    }
    return false;
}

function get_email(string $email) {
    $result = run_query("SELECT username FROM users WHERE email = :email;", [':email' => $email]);
    if (isset($result[0])) {
        return $result[0];
    }
    return false;
}

function set_user(string $username, string $pwd, string $email) {
    $options = [
        'cost' => 12
    ];

    $hashedPwd = password_hash($pwd, PASSWORD_BCRYPT, $options);

    run_query("INSERT INTO users (username, password, email) VALUES (:username, :pwd, :email)", [':username' => $username, ':pwd'=>$hashedPwd, ':email'=>$email]);

    require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/features/functions.php");
    $user_id = run_query("SELECT LAST_INSERT_ID() as id;")[0]["id"];
    obtain_achievement(1, $user_id);
}