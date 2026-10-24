<?php
if (isset($_SESSION['user_id']) && !$_SESSION['user_verified']) {
    include_once(realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/features/functions.php"));

    $last_request = run_query("SELECT expires FROM email_verification WHERE user_id = :id AND type = 'verify_email';", [":id" => $_SESSION['user_id']]);

    $new_request = true;
    if ($last_request && new DateTime() < new DateTime($last_request[0]['expires'])) {
        $new_request = false;
    }

    $user_email = run_query("SELECT email FROM users WHERE id = :id;", [":id" => $_SESSION['user_id']])[0]['email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ENET: Verify Email</title>
</head>
<body>
    <h3>Your current email: <?php echo $user_email; ?></h3>
    <?php if ($new_request) {
        echo "<form action='handle_POST.php' method='post'>
            <button>Resend Code</button>

            <input type='hidden' name='request' value='resend_verification'>
            <input type='hidden' name='redirect' value='verify-email'>
        </form>";
    } else {
        echo '<p>A valid verification code has been sent to this address aleady!</p>
        <p>Requests expire after 5 minutes</p>
        <p>You can submit a new one at: ' . $last_request[0]['expires'] . '</p>
        <br>';

    } 

    echo "<p>Want to change your email?</p>
    <form action='/handle_POST.php' method='post'>
        <input type='text' name='state' placeholder='New email'>
        <input type='password' name='pwd' placeholder='Confirm password'>
        <button>Change</button>

        <input type='hidden' name='request' value='alter_user'>
        <input type='hidden' name='redirect' value='verify-email'>
        <input type='hidden' name='attr' value='email'>
    </form>";
    
    display_form_errors();
    
    ?>
</body>
</html>

<?php } else {
    header("Location: /");
}