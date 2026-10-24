<?php

function get_error_names($errors) {
    $json_error_messages = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/error_text.json");
    $error_messages = json_decode($json_error_messages, true);
    
    $return = [];
    foreach ($errors as $error) {
        $return[] = array_key_exists($error, $error_messages) ? $error_messages[$error] : $error_messages['internal_server'];
    }
    unset($_SESSION['form_errors']);

    return $return;
}

function validate_gamepwd(int $pwd, int $game_id) {
    $result = run_query("SELECT id FROM games WHERE id = :id AND password = :password;", [':password' => $pwd, ':id'=> $game_id]);

    if ($result) {
        return true;
    } else {
        return false;
    }
}

function get_game(int $game_id) {
    $result = run_query("SELECT * FROM games WHERE id = :game_id;", [':game_id' => $game_id]);
    $result = $result[0];

    if ($result) {
        return $result;
    } else {
        return false;
    }
}

function get_username_from_id(int $user_id) {
    $result = run_query("SELECT username FROM users WHERE id = :id", [':id' => $user_id]);
    $result = $result[0];

    if ($result) {
        return $result['username'];
    } else {
        return false;
    }
}

function get_session(int $session_id) {
    $result = run_query("SELECT * FROM sessions WHERE session_identifier = :session_id AND last_ping > NOW() - INTERVAL 10 SECOND;", [':session_id' => $session_id]);
    $result = $result[0];
    
    if ($result) {
        $game_id = run_query("SELECT game_id FROM game_versions WHERE id = :id", [':id' => $result['game_version_id']])[0]["game_id"];
        $result['game_id'] = $game_id;
        return $result;
    } else {
        return false;
    }
}

function get_session_id_from_identifier(int $session_identifier) {
    $return = run_query("SELECT id FROM sessions WHERE session_identifier = :id;", [":id" => $session_identifier]);
    if ($return) {
        return $return[0]['id'];
    } else {
        return false;
    }
}

function check_game_exists(string $game) {
    $result = run_query("SELECT name FROM games WHERE name = :name;", [':name' => $game]);

    if ($result) {
        return true;
    }
    return false;
}

function check_version_exists(int $version) {
    $result = run_query("SELECT id FROM game_versions WHERE version_number = :version;", [':version' => $version]);
    
    if ($result) {
        return true;
    }
    return false;
}

function check_empty(array $args) {
    foreach ($args as $value) {
        if (empty($value)) {
            return true;
        }
    }
    return false;
}

function addGame($game_name, $game_owner, $check_only = false) {
    try {

        // ERROR HANDLERS
        $errors = [];

        function invalid_characters($name) {
            if (!preg_match('/[^A-Za-z0-9]/', $name)) {
                return false;
            }
            return true;
        }

        function owner_exists(string $owner) {
            $result = run_query("SELECT username FROM users WHERE username = :username;", [':username' => $owner]);
            $result = $result[0];
            if ($result) {
                return true;
            } else {
                return false;
            }
        }

        function check_uid(string $uid) {
            $result = run_query("SELECT password FROM games WHERE password = :password;", [':password'=>$uid]);
            $result = $result[0];
        
            if ($result) {
                return true;
            } else {
                return false;
            }
        }
        
        function create_game(string $name, string $owner) {
            $uuid = rand(1000000, 9999999);
            while (check_uid($uuid)) {
                $uuid = rand(1000000, 9999999);
            }
            $owner_id = run_query("SELECT id FROM users WHERE username = :username", [":username" => $owner])[0]["id"];
            run_query("INSERT INTO games (name, owner_id, password) VALUES (:game_name, :owner_id, :game_password)", [':game_name' => $name, ':owner_id'=> $owner_id, ':game_password'=> $uuid]);
            $game_id = run_query("SELECT id FROM games WHERE name = :name", [":name" => $name])[0]["id"];
            run_query("INSERT INTO game_versions (game_id, version_number) VALUES (:id, :version)", [':id' => $game_id, ':version'=> 1]);

            obtain_achievement(3, $owner_id);
        }

        if (check_empty([$game_name, $game_owner])) {
            $errors[] = "empty_input";
        }
        if (check_game_exists($game_name)) {
            $errors[] = "name_taken";
        }
        if (!owner_exists($game_owner)) {
            $errors[] = "owner_exists";
        }
        if (invalid_characters($game_name)) {
            $errors[] = "character_error";
        }

        if ($errors) {
            return ["errors" => $errors];
        }

        if (!$check_only) {
            create_game($game_name, $game_owner);
        }


        return true;

    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}

function addGameVersion($game_id, $game_version, $check_only = false) {

    try {

        // ERROR HANDLERS
        $errors = [];

        function version_exists(int $game_id, int $version) {
            $versions = run_query("SELECT version_number FROM game_versions WHERE game_id = :id", [":id" => $game_id]);
            foreach ($versions as $row) {
                if ($row['version_number'] == $version) {
                    return true;
                }
            }
            return false;
        }
        
        function set_game_version(int $game_id, int $version) {

            run_query("INSERT INTO game_versions (game_id, version_number) VALUES (:game_id, :version)", [':game_id' => $game_id, ":version" => $version]);
        }
 
        if (check_empty([$game_id, $game_version])) {
            $errors[] = "empty_input";
        }

        if (version_exists($game_id, $game_version)) {
            $errors[] = "version_exists";
        }

        if ($errors) {
            return ["errors" => $errors];
        }

        if (!$check_only) {
            set_game_version($game_id, $game_version);
        }

        return true;
        

    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }

}

function obtain_achievement(int $achievement_id, int $user_id, int $session_id = null) {
    #Check to ensure user hasn't already achieved the achievement
    if (run_query("SELECT id FROM user_achieved WHERE user_id = :uid AND achievement_id = :aid;", [":uid" => $user_id, ":aid" => $achievement_id])) {
        return false;
    }
    
    $rewards = run_query("SELECT coins, xp FROM achievements WHERE id = :id;", [":id" => $achievement_id]);
    if (!$rewards) {
        return false;
    }
    $rewards = $rewards[0];

    #obtain achievement
    run_query("INSERT INTO user_achieved (achievement_id, user_id) VALUES (:aid, :uid);", [":aid"=> $achievement_id,"uid"=> $user_id]);

    #reward coins and xp
    reward('coins', $user_id, $rewards['coins'], $session_id);
    reward('xp', $user_id, $rewards['xp'], $session_id);

    return true;
}

function reward(string $reward, int $user_id, int $amount, int $session_id = null) {
    include_once($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/web_managment/reward_user.php");
    $data = ["reward" => $reward,"amount"=> $amount, "user_id" => $user_id];

    if ($session_id) {
        if ($reward === "coins") {
            run_query("UPDATE sessions SET earned_coins = earned_coins + :amt WHERE id = :id;", [":amt" => $amount, ":id" => $session_id]);
        } else {
            run_query("UPDATE sessions SET earned_xp = earned_xp + :amt WHERE id = :id;", [":amt" => $amount, ":id" => $session_id]);
        }
    }

    return reward_user($data);
}

function display_form_errors($success_message = null) {
    if (isset($_SESSION["form_errors"])) {
        $messages = get_error_names($_SESSION['form_errors']);
        
        foreach ($messages as $msg) {
            echo '<p class="form-error">' . $msg . '</p>';
            echo "<br>";
        }

    }
    else if (isset($_GET['result']) && $_GET['result'] === "success") {
        if ($success_message) {
            echo '<p class="form-success">' . $success_message . '</p>';
        } else {
            echo '<p class="form-success">Success!</p>';
        }
        echo '<br>';
    }
}