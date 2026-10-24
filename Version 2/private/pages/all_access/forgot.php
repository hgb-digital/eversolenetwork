<?php if (!isset($_SESSION['user_id'])) { 
    include_once($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/features/functions.php");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ENET: Forgot Password</title>
</head>
<body>
    <h3>Password Reset</h3>
    <form action="/handle_POST.php" method="post">
        <input type="text" name="email" placeholder="Email">
        <button>Send Reset Code</button>
        
        <input type="hidden" name="request" value="send_password_reset">
        <input type="hidden" name="redirect" value="forgot">
    </form>
    <?php display_form_errors(); ?>
</body>
</html>

<?php } else {
    header("Location: /");
}