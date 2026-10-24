
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ENET: Email Link</title>
</head>
<body>

<?php

#check to make sure token exists and is valid
if (!isset($_GET['token'])) {
    header("Location: /");
    die();
}

$token = $_GET['token'];

require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/../private/scripts/login/email_verification.php');
echo evaluate_token($token);

?>
    
</body>
</html>