<?php

function display_user() {
    $user = run_query("SELECT username, permissions, email, id, created, xp, coins FROM users WHERE username = :username", ['username' => $_GET['user']]);
    if (!$user) {
        ?> <title>ENET: Users</title>
        <p>User not found!</p>
        <a href="/?url=users"><button>Back</button></a>
        <?php
    } else { 
        $user = $user[0];
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>ENET: <?php echo $_GET['user'] ?></title>
        </head>
        <body>
            <style>
                    #achievement {
                        padding: .5em;
                        border: .1em;
                        border-color: black;
                        border-style: solid;
                        width: fit-content;
                        margin-bottom: .5em;

                    }

                    .sections {
                        display: flex;
                        flex-wrap: wrap;
                        gap: 1rem;
                    }

                    .section {
                        flex: 1 1 300px;
                        padding: 1rem;
                        border: .25rem solid black;
                        box-sizing: border-box;
                    }
            </style>

            <h1><?php echo $_GET['user']; if (isset($_SESSION['user_id']) && $user['username'] === $_SESSION['user_username']) {echo " (you)";}?></h1>

            <?php
                $active_session = run_query('SELECT game_version_id FROM sessions WHERE user_id = :id AND last_ping > NOW() - INTERVAL 10 SECOND;', [':id'=> $user['id']]);
                if ($active_session) {
                    $game_version_id = $active_session[0]['game_version_id'];
                    $game_id = run_query("SELECT game_id FROM game_versions WHERE id = :id", [':id' => $game_version_id])[0]["game_id"];
                    $game_name = run_query("SELECT name FROM games WHERE id = :id;", [":id"=> $game_id])[0]['name'];
                    echo("<strong><h3 style='color: green;'>Playing " . $game_name . "</h3></strong>");
                } else {
                    echo("<strong><h3 style='color: red;'>Offline</h3></strong>");

                }
            ?>

            <div class="sections">
                <div class="section">
                    <h2>User Info</h2>
                    <strong><p>
                        <?php
                            echo $user['xp'] . " XP - " . $user['coins'] . " Coins";
                        ?>
                    </p></strong>

                    <?php if (isset($_SESSION['user_id']) && ($_SESSION['user_permissions'] == 0)) {
                        $username = $user['username'];
                        $user_id = $user['id'];
                        echo ('<form action="/handle_POST.php" method="post">
                        <button>Add</button>
                       <input type="number" max="999999" min="0" value="10" name="amount">
                       <select name="reward">
                        <option>Coins</option>
                        <option>XP</option>
                       </select>
                       
                        <input type="hidden" name="user_id" value="' . $user_id . '">
                        <input type="hidden" name="request" value="reward_user">
                        <input type="hidden" name="redirect" value="users&user=' . $username . '">
                       </form>');
                    }

                    if ($user['permissions'] == 0) { ?>       
                        <p style="color: red;">Admin</p>
                    <?php } ?>

                    <p>User ID: <?php echo $user['id'] ?></p>
                    <p>Email: <?php echo $user['email'] ?></p>
                    <p>Joined: <?php echo $user['created'] ?></p>
                </div>
                <div class="section">
                    <h2>Achievements</h3>
                    <?php

                        $achieved = run_query("SELECT achievement_id FROM user_achieved WHERE user_id = :id;", [":id" => $user['id']]);
                        if ($achieved) {
                            $achieved = array_column($achieved,'achievement_id');
                            foreach ($achieved as $a) {
                                $data = run_query("SELECT * FROM achievements WHERE id = :id", ["id" => $a])[0];
                                echo "<div id='achievement'>" . $data['name'] . "</div>";
                            }
                        } else {
                            echo "No obtained achievements";
                        }

                    ?>
                </div>
            </div>
        </body>
        </html>
    <?php }
}