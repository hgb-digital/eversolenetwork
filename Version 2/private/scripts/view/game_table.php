<?php

function displayTable() { 
    
    $games = run_query("SELECT name, owner_id, created_at, id FROM games;");

    $score_counts = [];

    if ($games) {
        foreach ( $games as $row ) {
            $score_counts[$row['name']] = run_query("SELECT COUNT(*) FROM scores WHERE game_id = :id", [':id' => $row['id']])[0]["COUNT(*)"];
        }
    }

    //get your games
    if (isset($_SESSION["user_id"])) {
        $owned_games = run_query("SELECT id, name, password, created_at FROM games WHERE owner_id = :owner;", [':owner' => $_SESSION['user_id']]);
        $score_counts_owned = [];
        if ($owned_games) {
            foreach ( $owned_games as $row ) {
                $score_counts_owned[$row['name']] = run_query("SELECT COUNT(*) FROM scores WHERE game_id = :id", [":id" => $row['id']])[0]["COUNT(*)"];
        
            }
        }
    }
?>


    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ENET: Game List</title>
        <link rel="stylesheet" href="/serve_resources.php?type=css&file=table_style.css">
    </head>
    <body>

        <?php if (isset($owned_games)) {?>
            <h3>Your Games</h3>

            <?php if (count($owned_games) > 0) { ?>

                <script>
                    function showpassword(id) {
                        const button = document.getElementById('showpassword_'.concat(id));
                        const pass_element = document.createElement('span');
                        pass_element.innerHTML = game_data[id]['password'];
                        button.replaceWith(pass_element);
                    }
                    let game_data = {};
                </script>
                <table border=1 frame=void rules=rows>
                <tr>
                    <th>Name</th>
                    <th>ID</th>
                    <th>Created</th>
                    <th>Scores</th>
                    <th>Password</th>
                </tr>
                <?php
                foreach ( $owned_games as $row ) {
                    ?> 
                    <tr>
                        <td><a href="/?url=game&game=<?php echo $row['name'] ?>&version=1"><?php echo $row['name']; ?></a></td>
                        <td><?php echo $row['id'] ?></td>
                        <td><?php echo $row['created_at'] ?></td>
                        <td><?php echo $score_counts_owned[$row['name']] ?></td>
                        <td><input id=<?php echo "showpassword_" . $row['id'] ?> type='button' value='Show Password' onclick='showpassword(<?php echo $row['id'] ?>);' /></td>
                        <script>
                        game_data[<?php echo $row['id']; ?>] = {};
                        game_data[<?php echo $row['id']; ?>]['password'] = <?php echo $row['password']; ?>;
                        game_data[<?php echo $row['id']; ?>]['name'] = '<?php echo $row['id']; ?>';
                        </script>
                    </tr>
                    <?php } ?>
                </table>

            <?php } else { ?>
                <p>You don't own any games</p>
            <?php } ?>

            <p>--</p>
        <?php } ?>
        
        <h3>All Games</h3>
        <table border=1 frame=void rules=rows>
            <tr>
                <th>Name</th>
                <th>Owner</th>
                <th>Created</th>
                <th>Scores</th>
            </tr>
            <?php
            if ($games) {
                foreach ($games as $g) {
                    ?> <tr> <?php
                    foreach ($g as $d => $detail) {
                        if ($d === "id") {
                            continue;
                        }
                        ?> <td> <?php
                        if ($d == "name") {
                            ?> <a href="/?url=game&game=<?php echo $detail; ?>&version=1"><?php echo $detail; ?></a> <?php
                        } elseif ($d == "owner_id") {
                            $owner_name = run_query("SELECT username FROM users WHERE id = :id;", [":id" => $detail])[0]["username"];
                            echo $owner_name;
                        } else {
                            echo $detail;
                        }
                        ?> </td> <?php
                    }
                    ?> <td><?php echo $score_counts[$g['name']]; ?></td> <?php
                    ?> </tr> <?php
                }
            }
            ?>
        </table>
        <p>----</p>
        <p>Want to add your game?</p>
        <a href="/?url=request-game"><button>Request Game</button></a>
    </body>
    </html>


<?php }