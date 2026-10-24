<?php

function signup($data) {

    $username = $data["username"];
    $pwd = $data["pwd"];
    $email = $data["email"];

    try {
        
        require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/signup/signup_errors.php");
        require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/signup/signup_setget.php");
        require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/features/functions.php");

        // ERROR HANDLERS
        $errors = [];

        if (check_empty([$username, $pwd, $email])) {
            $errors[] = "empty_input";
        }
        if (is_email_invalid($email)) {
            $errors[] = "invalid_email";
        }
        if (is_username_taken($username)) {
            $errors[] = 'username_taken';
        }
        if (is_email_registered($email)) {
            $errors[] = "email_used";
        }
        if (in_array(strtolower($username), ["anonymous", "__system", "you"])) {
            $errors[] = "username_invalid";
        }


        if ($errors) {

            $signupData = [
                "username" => $username,
                "email" => $email
            ];
            return ['errors' => $errors, "form_sendback" => $signupData];
        }

        create_user($username, $pwd, $email);

        include_once(realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/features/config_session.php"));
        unset($_SESSION['signup_data']);

        return true;

    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }

}