<?php

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/login/logout.php");
logout();
header("Location: /");