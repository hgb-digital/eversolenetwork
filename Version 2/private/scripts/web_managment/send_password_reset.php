<?php

function send_password_reset($data) {
    $email = $data['email'];
    include(realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/login/email_verification.php"));
    return send_reset($email);
}