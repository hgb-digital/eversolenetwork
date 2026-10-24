<?php

function resend_verification($data) {
    $user_id = $data['user_id'];
    include(realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/login/email_verification.php"));
    return send_verification($user_id);
}