<?php

declare(strict_types=1);

function get_user(string $username) {
    $result = run_query("SELECT * FROM users WHERE username = :username;", [':username' => $username]);
    $result = $result[0];
    return $result;
}