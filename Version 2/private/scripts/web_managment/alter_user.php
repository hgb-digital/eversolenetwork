<?php

function alter_user($data) {
    $user_id = $data["user_id"];

    #columns in the users table
    $attribute = $data["attr"];
    $state = $data["state"];

    //users can only change specific values of their own
    if (isset($data['user_alter'])) {
        $allowed_changes = ['username', 'password', 'email'];
    } else {
        $allowed_changes = ['username', 'password', 'email', 'permissions', 'authorized'];
    }

    if ($user_id == 1) {
        return false;
    }

    if (!in_array($attribute, $allowed_changes)) {
        return false;
    }

    try {
        if ($attribute == 'authorized') {
            run_query("UPDATE users SET authorized = :a WHERE id = :id;", [":a" => $state, ":id" => $user_id]);
        } elseif ($attribute == "permissions") {
            run_query("UPDATE users SET permissions = :a WHERE id = :id;", [":a" => $state, ":id" => $user_id]);
        } elseif ($attribute == "email") {
            //check that email doesn't already exist
            require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/../private/scripts/signup/signup_errors.php');
            if (is_email_invalid($state)) {
                return ['errors' => ['invalid_email']];
            }
            if (is_email_registered($state)) {
                return ['errors' => ['email_used']];
            }

            run_query("UPDATE users SET email = :e WHERE id = :id;", [":e" => $state, ":id" => $user_id]);

            //remove any email_verification attempts
            run_query("DELETE FROM email_verification WHERE user_id = :id AND type='verify_email';", [':id' => $user_id]);

            //reverify
            require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/../private/scripts/login/email_verification.php');
            send_verification($user_id);
        } elseif ($attribute == 'password') {
            $options = ['cost' => 12];
            $hashedPwd = password_hash($state, PASSWORD_BCRYPT, $options);

            run_query("UPDATE users SET password = :p WHERE id = :id;", [":p" => $hashedPwd, ":id" => $user_id]);
            run_query("DELETE FROM email_verification WHERE user_id = :id AND type='reset_password';", [':id' => $user_id]);

            //remove password reset attmepts
        }
        return true;
        
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}