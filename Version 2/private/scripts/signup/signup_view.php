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