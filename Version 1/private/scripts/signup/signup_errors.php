<?php

declare(strict_types=1);

function is_email_invalid(string $email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}

function is_username_taken(string $username) {
    if (get_username($username)) {
        return true;
    } else {
        return false;
    }
}

function is_email_registered(string $email) {
    if (get_email($email)) {
        return true;
    } else {
        return false;
    }
}

function create_user(string $username, string $pwd, string $email) {
    set_user($username, $pwd, $email);
}