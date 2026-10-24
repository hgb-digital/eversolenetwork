<?php

declare(strict_types=1);

function signup_inputs() {

    if (isset($_SESSION["form_sendback"]["username"]) && !in_array('username_taken', $_SESSION['form_errors'])) {
        echo '<input type="text" name="username" placeholder="Username" value="'. $_SESSION["form_sendback"]["username"] .'">';
    } else {
        echo '<input type="text" name="username" placeholder="Username">';
    }

    echo '<input type="password" name="pwd" placeholder="Password">';

    if (isset($_SESSION["form_sendback"]["email"]) && !!in_array('email_used', $_SESSION['form_errors']) && !in_array('invalid_email', $_SESSION['form_errors'])) {
        echo '<input type="text" name="email" placeholder="E-Mail" value="'. $_SESSION["form_sendback"]["email"] .'">';
    } else {
        echo '<input type="text" name="email" placeholder="E-Mail">';
    }

    echo '<input type="hidden" name="request" value="signup">';
    echo '<input type="hidden" name="redirect" value="signup">';
}

function check_signup_errors() {
    if (isset($_SESSION["form_errors"])) {
        require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/../private/scripts/features/functions.php');
        $messages = get_error_names($_SESSION['form_errors']);
        
        foreach ($messages as $msg) {
            echo "<br>";
            echo '<p class="form-error">' . $msg . '</p>';
        }

    }
    else if (isset($_GET['result']) && $_GET['result'] === "success") {
        echo '<br>';
        echo '<p class="form-success">Login success!</p>';
    }
}