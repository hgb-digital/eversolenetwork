<?php

declare(strict_types=1);

function output_username() {
    if(isset($_SESSION["user_id"])) {
        echo "You are logged in as " . $_SESSION["user_username"] . "  - PERMISSION: " . $_SESSION['user_permissions'];
    } else {
        echo "You are not logged in";
    }
}

function check_login_errors() {
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