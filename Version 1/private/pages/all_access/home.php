<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ENET</title>
</head>
<style>
    #alert {
        background-color: yellow;
    }
</style>
<body>

    <div id="alert">
        <?php
            $msg = file_get_contents(realpath($_SERVER['DOCUMENT_ROOT'] . '/../private/scripts/view/messages/alert.txt'));
            if ($msg) {
                echo "<p>" . $msg . "</p>";
            }
        ?>
    </div>

    <h1>Welcome to Eversole Network</h1>
    <p>Developed and Operated by HB</p>

    <br>
    <p>Server Message: </p>
    <?php
        $motd = file_get_contents(realpath($_SERVER['DOCUMENT_ROOT'] . '/../private/scripts/view/messages/motd.txt'));
        echo '<p>' . $motd . '</p>';
    ?>

</body>
</html>