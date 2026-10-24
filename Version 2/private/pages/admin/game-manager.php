<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ENET: Game Manager (ADMIN)</title>
    <link rel="stylesheet" href="/serve_resources.php?type=css&file=table_style.css">
</head>
<body>
    <h2>Game Manager</h2>
    <script>
        function showpassword(id) {
            const button = document.getElementById('showpassword_'.concat(id));
            const pass_element = document.createElement('span');
            pass_element.innerHTML = game_data[id]['password'];
            button.replaceWith(pass_element);
        }
        function delete_game(id) {
            if (confirm('Delete '.concat(game_data[id]['name']).concat("?"))) {
                const response = fetch("/handle_POST.php", {
                method: "POST",
                credentials: "include",
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({request: "delete_game", data: {id: id, version: game_data[id]['version']}}),
                }).then((response) => {location.reload();})
            }
        }
        let game_data = {};
    </script>

    <style>
        .sections {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin: .25em;
        .section {
            flex: 1 1 300px;
            padding: 1rem;
            border: .25rem solid black;
            box-sizing: border-box;
        }
        .full-section {
            flex: 1 1 100%;
        }
    </style>

        <?php
        $games = run_query("SELECT * FROM games");
        ?> 
        <div class="sections">
        <div class="section full-section"> 
            <?php require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/../private/scripts/view/admin_games.php'); ?>
        </div>

    <div class="section">
    <h1>Add Game</h1>
    <form action="/handle_POST.php" method="post">
        <p id='game_name'>Game Name:</p>
        <input type="text" name="game_name" placeholder="Game Name">
        <p>Owner:</p>
        <select name="game_owner" id="game_owner">
            <?php
            $result = run_query("SELECT username FROM users;");

            foreach ($result as $row) { ?>
                    <option value = <?php echo $row['username'] ?>><?php echo $row['username'] ?></option>
            <?php } ?>

        </select>
    <input type="hidden" name="request" value="add_game">
    <input type="hidden" name="redirect" value="admin.game-manager">
    <button>Add</button>
    </form>
            <?php 
                if (isset($_SESSION['error_creategame'])) {
                    $errors = $_SESSION['error_creategame'];
                    echo("<br>");
                    
                    foreach ($errors as $error) {
                        echo '<p class="form-error">'. $error .' </p>';
                    }
                    
                    unset($_SESSION['error_creategame']);
                } else if (isset($_GET["create"]) && $_GET["create"] === "success") {
                    echo "<br>";
                    echo '<p class="form-error">Game Created!</p>';
                }
                ?> 
    </div>
    
    <div class="section">
    <h1>Add Game Version</h1>
    <form action="/handle_POST.php" method="post">
        <p id='game_name'>Game Name:</p>
        <select name="game_name" id="game_name">
            <?php

            $result = run_query("SELECT * FROM games");
            
            foreach ($result as $row) { ?>
                    <option value = <?php echo $row['name'] ?>><?php echo $row['name'] . " - " . $owner ?></option>
                    <?php } ?>

        </select>
        <p id='game_version'>Game Version:</p>
        <input type="number" id="game_version" name="game_version" min="0" max="999" value="1">
        <input type="hidden" value="add_game_version" name="request">
        <input type="hidden" value="admin.game-manager" name="redirect">
        <button>Add</button>
    </form>
            <?php 
                if (isset($_SESSION['form_errors'])) {
                    include_once(realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/features/functions.php"));
                    $errors = $_SESSION['form_errors'];
                    $text_errors = get_error_names($errors);
                    echo("<br>");
                    
                    foreach ($text_errors as $error) {
                        echo '<p class="form-error">'. $error .' </p>';
                    }
                    
                    unset($_SESSION['form_errors']);
                } else if (isset($_GET["version"]) && $_GET["version"] === "success") {
                    echo "<br>";
                    echo '<p class="form-error">New version added!</p>';
                }
                ?> 
    </div>

    <div class="section full-section">
        <h1>Requests</h1>
        <?php require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/../private/scripts/view/game_requests.php'); ?>

    </div>
    </div>
</body>
</html>