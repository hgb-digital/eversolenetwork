<?php

function game_login($data) {

    $username = $data['username'];
    $pwd = $data['pwd'];
    $session_id = $data['session_id'];

    //login
    try {
        require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/login/login_get.php");
        require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/login/login_check.php");
        include_once(realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/features/functions.php"));

        // ERROR HANDLERS
        $errors = [];
        
        if (check_empty([$username, $pwd, $session_id])) {
            $errors[] = "empty_input";
            return ["errors" => $errors];
        }

        $result = get_user($username);

        if (is_username_wrong($result)) {
            $errors[] = "login_incorrect";
        }
        if (!is_username_wrong($result) && is_password_wrong($pwd, $result["password"])) {
            $errors[] = "login_incorrect";
        }

        if (!$result['authorized']) {
            $errors[] = 'unauthorized_user';
        }

        if (!$result['email_verified']) {
            $errors[] = "email_not_verified";
        }

        if ($errors) {
            return ["errors" => $errors];
        }
        
        obtain_achievement(2, $result['id'], get_session_id_from_identifier($session_id));

        run_query("UPDATE sessions SET user_id = :user_id WHERE session_identifier = :id;", 
        [':id' => $session_id, ':user_id'=>$result["id"]]);

        return ["data" => ["id" => $result['id'], "username" => $username, 'coins' => strval($result['coins']), 'xp' => strval($result['xp'])]];

    } catch (PDOException $e) {
        echo $e;
        die();
    }
}