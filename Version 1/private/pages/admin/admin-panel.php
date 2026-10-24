<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ENET: Admin Panel</title>
</head>
<body>
    <h1>Admin Panel</h1>
    <a href="/admin/?url=game-manager"><button>Game Manager</button></a>
    <a href="/admin/?url=active-connections"><button>Connections</button></a>
    <a href="/admin/?url=user-list"><button>User List</button></a>

    <br>
    <h3>Server Messages: </h3>

    <?php
    if (isset($_GET['result']) && $_GET['result'] == "success") {
        echo '<p style="color: green;">Post Success!</p>';
    }
    ?>

    <form action="/handle_POST.php" method="post">
        <input type="text" name="msg" placeholder="Message of the day">

        <input type="hidden" name="request" value="update_server_messages">
        <input type="hidden" name="redirect" value="admin.admin-panel">
        <input type="hidden" name="type" value="motd">
        <button>Post</button>
    </form>
    <form action="/handle_POST.php" method="post">
        <input type="text" name="msg" placeholder="Server Alert">

        <input type="hidden" name="request" value="update_server_messages">
        <input type="hidden" name="redirect" value="admin.admin-panel">
        <input type="hidden" name="type" value="alert">
        <button>Post</button>
    </form>

    <h3>Downloads</h3>
    <a href="/admin/?url=download-file&file=client.zip"><button>Client Loader</button></a>
    <p>Download the zip file, extract, and paste the contents into your project (including the ENET folder). Go into the project settings and add the TSCN as a singleton/global. The variable name HAS TO BE _enet for this to work.
        The loader should automatically start now. All the UI for now is made to work with 1920 x 1080 ONLY. This is WINDOWS only for now.
    </p>
</body>
</html>