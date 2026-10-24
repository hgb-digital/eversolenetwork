<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ENET: Request Game</title>
    <link rel="stylesheet" href="/serve_resources.php?type=css&file=table_style.css">
</head>
<body>
    <style>
        .form-error {
            color: red;
        }
    </style>
    <h1>Request Game</h1>
    <?php
        if (isset($_GET['result'])) {
            ?>
            <p>Sucessfully Sent Request</p>
            <a href="/?url=request-game"><button>Submit Another</button></a>
            <?php
        } elseif (isset($_SESSION["user_id"])) {

            if ($_SESSION['user_permissions'] === 0) {
                ?>
                <p>So how'd we get here, admin??? Just go to game manager to add a game.</p>
                <a href="admin/gameManager.php"><button>Game Manager</button></a>
                <?php
            } else {
                if (isset($_SESSION["form_errors"])) {
                    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/../private/scripts/features/functions.php');
                    $messages = get_error_names($_SESSION['form_errors']);
                    
                    foreach ($messages as $msg) {
                        echo "<br>";
                        echo '<p class="form-error">' . $msg . '</p>';
                    }
            
                }
                ?>
                <style>
                    .deselected {
                        background-color: white;
                    }
                    .selected {
                        background-color: yellowgreen;
                    }
                    #game_name, #game_version {
                        padding-right: 5.0em;
                    }
                    .approved {
                        background: greenyellow;
                    }
                    .rejected {
                        background: orangered;
                    }
                    .pending {
                        background: yellow;
                    }
                </style>
                <p>Select Request:</p>
                <button id="game_button" class="deselected">New Game</button>
                <button id="version_button" class="deselected">Add New Version</button>
                <div id="form"></div>
                <p>----</p>
                <h2>Your requests</h2>
                <table border=1 frame=void rules=rows>
                    <tr>
                        <td>Game Name</td>
                        <td>Game Version</td>
                        <td>Type</td>
                        <td>Requested</td>
                        <td>Status</td>
                    </tr>
                    <?php
                        $requests = run_query('SELECT name, version, request_type, created_at, status from game_requests WHERE user_id = :id', [':id'=>$_SESSION['user_id']]);
                        if ($requests) {
                            foreach ($requests as $request) {
                                ?> <tr> <?php
                                foreach ($request as $title=>$detail) {
                                    if ($title == 'type') {

                                        if  ($detail === "new_game") {
                                            ?> <td>New game</td> <?php
                                        } else {
                                            ?> <td>Add version</td> <?php
                                        }

                                    } elseif ($title == 'status') {

                                        ?> <td class='<?php echo $detail; ?>'><?php echo strtoupper($detail); ?></td> <?php

                                    } else { ?>

                                    <td><?php echo $detail; ?></td>

                                    <?php }
                                }
                                ?> </tr> <?php
                            }
                        } else {
                            echo '<tr><td>No requests</td></tr>';
                        }
                    ?>
                </table>

                <script type="module">
                    document.querySelector('#version_button').addEventListener('click', generateVersionForm)
                    document.querySelector('#game_button').addEventListener('click', generateGameForm)

                    async function get_owned_games() {
                        const result = await fetch("/handle_POST.php", {
                        method: "POST",
                        credentials: "include",
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify({ request: "get_owned_games", data: {owner: '<?php echo $_SESSION['user_username']; ?>' }})
                        });
                        const data = await result.json();
                        return data;
                    }

                    async function generateVersionForm() {
                        let games = await get_owned_games();
                        let elements = [];

                        document.getElementById('form').innerHTML = ` <form method="post" action="/handle_POST.php">
                        <p>----</p>
                        <p>You can only do this for games you own</p>
                        <br>
                        <span>Game Name: </span>
                        <span id='select_holder'></span>
                        <p>Owner: you</p>
                        <span>New Version: </span>
                        <input type="number" max="999" min="0" value="1" name='game_version'>
                        <input type='hidden' value='version' name='type'>
                        <input type='hidden' value='request_game' name='request'>
                        <input type='hidden' name='redirect' value='request-game'>
                        <br>
                        <button>Request</button>
                        </form>`;

                        let game_name = document.createElement("select");
                        game_name.name = 'game_name';

                        for (let index = 0; index < games.length; index++) {
                            let game = document.createElement("option");
                            game.value = games[index];
                            game.innerText = games[index];
                            game_name.appendChild(game);
                        }
                        document.getElementById('select_holder').appendChild(game_name);


                        document.getElementById('version_button').classList.add('selected');
                        document.getElementById('game_button').classList.remove('selected');
                    }

                    function generateGameForm() {
                        document.getElementById('form').innerHTML = `
                        <form method="post" action="handle_POST.php">
                            <p>----</p>
                            <span>Game Name: </span>
                            <input type="text" name="game_name" placeholder="Game Name">
                            <input type='hidden' value='game' name='type'>
                            <input type='hidden' value='request_game' name='request'>
                            <input type='hidden' name='redirect' value='request-game'>
                            <p>Owner: you</p>
                            <p>Version: 1</p>
                            <button>Request</button>
                        </form>
                        `;
                        

                        document.getElementById('game_button').classList.add('selected');
                        document.getElementById('version_button').classList.remove('selected');
                    }
                </script>

                <?php
            }

        } else {
            ?> 
            <p>You must be logged in to request a game.</p>
            <a href="/?url=login"><button>Login</button></a>
            <a href="/?url=signup"><button>Signup</button></a>
            <?php
        }
        
    ?>
</body>
</html>

