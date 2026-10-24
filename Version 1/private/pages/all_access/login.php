<?php
require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/login/login_view.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ENET: Login</title>
</head>
<body>
    <?php
    if (!isset($_SESSION["user_id"])) {?>

        <h3>Login</h3>
        <form action="handle_POST.php" method="post">
            <input type="text" name="username" placeholder="Username">
            <input type="password" name="pwd" placeholder="Password">
            <input type="hidden" name="request" value="login">
            <input type="hidden" name="redirect" value="login">
            <button>Login</button>
        </form>

        <p>New User? <a href="/?url=signup">Signup</a></p>
    <?php } else {
        header("Location: ../../index.php?");
        die();
    }
    check_login_errors();
    ?>
    
</body>
</html>