<?php
function login($data) {
    try {
        $username = $data['username'];
        $pwd = $data['pwd'];

        require_once 'login_get.php';
        require_once 'login_check.php';
        require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/features/functions.php");

        // ERROR HANDLERS
        $errors = [];

        if (check_empty([$username, $pwd])) {
            return ['errors' => ['empty_input']];
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
        
        
        if ($errors) {
            return ['errors' => $errors];
        }

        require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/features/config_session.php");
        
        $_SESSION["user_id"] = $result["id"];
        $_SESSION["user_username"] = htmlspecialchars($result["username"]);
        //permissions    0 - admin/full,    1 - standard user
        $_SESSION["user_permissions"] = $result["permissions"];
        
        $_SESSION["last_regeneration"] = time();
        
        return true;

    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }

} 