<?php

declare(strict_types=1);

function is_username_wrong($result) {
    if (!$result) {
        return true;
    } else {
        return false;
    }
}

function is_password_wrong(string $result, string $hashedpwd) {
    if (!password_verify($result, $hashedpwd)) {
        return true;
    } else {
        return false;
    }
}