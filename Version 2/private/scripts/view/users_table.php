<?php

function display_users_table() {
    $users = run_query("SELECT username, permissions, id FROM users");
?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ENET: Users</title>
    </head>
    <body>
        <style>
            #user-card {
                background-color: white;
                width: 10em;
                height: 10em;
                margin: .5em;
            }
            #user-card:hover {
                background-color: lightgreen;
            }
        </style>
        <script>
            function visit_user($user) {
                window.location.assign("/?url=users&user=" + $user);
            }
        </script>
        
        <h3>All Users</h3>
        <div style="display: flexbox;">
        <?php
            foreach ($users as $user) { ?>
                <button id='user-card' onclick="visit_user('<?php echo $user['username']; ?>')">
                    <h2>
                        <?php echo $user['username']; ?>
                    </h2>
                    <?php

                    $active_session = run_query('SELECT game_version_id FROM sessions WHERE user_id = :id AND last_ping > NOW() - INTERVAL 10 SECOND;', [':id'=> $user['id']]);
                    if ($active_session) {
                        $game_version_id = $active_session[0]['game_version_id'];
                        $game_id = run_query("SELECT game_id FROM game_versions WHERE id = :id", [':id' => $game_version_id])[0]["game_id"];
                        $game_name = run_query("SELECT name FROM games WHERE id = :id;", [":id"=> $game_id])[0]['name'];
                        echo("<strong><p style='color: green;'>" . $game_name . "</p></strong>");
                    } else {
                        echo("<strong><p style='color: red;'>Offline</p></strong>");

                    }
                
                    if ($user['permissions'] == 0) {
                        ?> 
                        <p style="color: red;">Admin</p>
                        <?php
                    }

                    ?>
                </button>
            <?php } ?>
        </div>
        
    </body>
    </html>


<?php }