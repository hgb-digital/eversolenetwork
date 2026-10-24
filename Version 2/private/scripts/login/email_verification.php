<?php
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function send_email($subject, $body, $send_to) {
    $api_key = "INSERT API KEY"; // store your key in env variables
    if (!$api_key) {
        error_log("SMTP2GO API key not set");
        return false;
    }

    $data = [
        "api_key" => $api_key,
        "to" => [$send_to],
        "sender" => "INSERT EMAIL", // must be a verified sender
        "subject" => $subject,
        "text_body" => strip_tags($body),
        "html_body" => $body,
    ];

    $ch = curl_init("https://api.smtp2go.com/v3/email/send");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        error_log("SMTP2GO cURL error: " . curl_error($ch));
        curl_close($ch);
        return false;
    }

    curl_close($ch);

    $resp = json_decode($response, true);
    if (!$resp || empty($resp['data']['succeeded']) || $resp['data']['succeeded'] == 0) {
        error_log("SMTP2GO API error: " . $response);
        return false;
    }

    return true;
}

function send_verification($user_id) {
    //ensure there is no valid email sent, to prevent spamming of the server
    $last_request = run_query("SELECT expires FROM email_verification WHERE user_id = :id AND type = 'verify_email';", [":id" => $user_id]);

    if ($last_request && new DateTime() < new DateTime($last_request[0]['expires'])) {
        return false;
    }

    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/../private/scripts/features/functions.php');
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/../private/scripts/login/login_get.php');
    include realpath($_SERVER['DOCUMENT_ROOT'] . '/../private/maintenance.php');

    if (!get_username_from_id($user_id)) {
        return false;
    }
    
    $user_email = get_user(get_username_from_id($user_id))['email'];
    $website = "https://eversole.network";
    if ($xampp) {
        $website = "https://localhost";
    }
    

    $token = bin2hex(random_bytes(32));
    $expires_at = date('Y-m-d H:i:s', strtotime('+5 minutes'));

    $body = '
            <p>Hello ' . get_username_from_id($user_id) . ',</p>
            <p>Click the link below to verify your email:</p>
            <p><a href="' . $website . '?url=email&token=' . $token . '">Verify Email</a></p>
            <p>This link expires at: ' . $expires_at . '</p>
        ';

    if (send_email("ENET Email Verification", $body, $user_email)) {
        run_query("DELETE FROM email_verification WHERE user_id = :id AND type = 'verify_email';", [":id" => $user_id]);
        run_query("INSERT INTO email_verification (user_id, token, expires, type) VALUES (:u, :t, :e, 'verify_email');", [":u" => $user_id, ":t" => $token, ":e" => $expires_at]);
        return true;
    }

    return false;



}

function send_reset($email) {
    //get user id from email
    $user = run_query("SELECT id, email_verified, username FROM users WHERE email = :e;", [":e" => $email]);
    if (!$user) {
        return ['errors' => ['email_not_found']];
    }
    $user = $user[0];

    //ensure there is no valid email sent, to prevent spamming of the server
    $last_request = run_query("SELECT expires FROM email_verification WHERE user_id = :id AND type = 'reset_password';", [":id" => $user['id']]);

    if ($last_request && new DateTime() < new DateTime($last_request[0]['expires'])) {
        return ['errors' => ['email_request_valid']];
    }

    //check to make sure email verified
    if (!$user['email_verified']) {
        return ['errors' => ['email_not_verified']];
    }

    include realpath($_SERVER['DOCUMENT_ROOT'] . '/../private/maintenance.php');
    $website = "https://eversole.network";
    if ($xampp) {
        $website = "https://localhost";
    }

    $token = bin2hex(random_bytes(32));
    $expires_at = date('Y-m-d H:i:s', strtotime('+5 minutes'));

    $body = '
            <p>Hello ' . $user['username'] . ',</p>
            <p>Click the link below to reset your password:</p>
            <p><a href="' . $website . '?url=email&token=' . $token . '">Reset Password</a></p>
            <p>This link expires at: ' . $expires_at . '</p>
        ';

    if (send_email("ENET Password Reset", $body, $email)) {
        run_query("DELETE FROM email_verification WHERE user_id = :id AND type = 'reset_password';", [":id" => $user['id']]);
        run_query("INSERT INTO email_verification (user_id, token, expires, type) VALUES (:u, :t, :e, 'reset_password');", [":u" => $user['id'], ":t" => $token, ":e" => $expires_at]);
        return true;
    }

    return false;
}

function evaluate_token($token) {
    $token_query = run_query("SELECT * FROM email_verification WHERE token = :t;", [":t" => $token])[0];
    if (!$token_query) {
        return "<h3>Token Doesn't Exist</h3>";
    } else {
        if (new DateTime() > new DateTime($token_query['expires'])) {
            return "<h3>Token Expired</h3>";
        } else {
            if ($token_query['type'] === "verify_email") {
                #verify email and delete token
                run_query("UPDATE users SET email_verified = 1 WHERE id = :id;", [':id' => $token_query['user_id']]);
                run_query("DELETE FROM email_verification WHERE token = :t;", [':t' => $token]);

                return "<h3>Email Verified!</h3>
                <a href='/'><button>Return Home</button></a>
                ";
            } else {
                return "<h3>Set a new password</h3>
                <form action='/handle_POST.php' method='post'>
                    <input name='state' placeholder='New Password' type='password'>
                    <button>Change</button>

                    <input name='request' type='hidden' value='alter_user'>
                    <input name='redirect' type='hidden' value='login'>
                    <input name='token' type='hidden' value='" . $token . "'>
                </form>
                ";

            }
        }
    }
}