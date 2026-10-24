<?php

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/signup/signup_view.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ENET: Signup</title>
</head>
<body>
    <?php
    if (!isset($_SESSION["user_id"])) {?>
        <h3>Signup</h3>
            <form action="handle_POST.php" method="post">
                <?php
                signup_inputs();
                ?>
                <button>Signup</button>
            </form>
        <?php } else {
            header("Location: /");
            die();
        }
                check_signup_errors();
        ?>
</body>
</html>

