<?php

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/view/users_table.php");
require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/view/view_user.php");

if (isset($_GET['user'])) {
    display_user();
} else {
    display_users_table();
}

