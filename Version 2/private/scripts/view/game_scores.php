<?php

function displayGame($game_name, $game_id, $version) {
    $game_version_id = run_query("SELECT id FROM game_versions WHERE game_id = :id AND version_number = :version;", [":id" => $game_id, ":version" => $version])[0]["id"];
    
    $total_scores = run_query("SELECT COUNT(*) FROM scores WHERE game_version_id = :id;", ["id" => $game_version_id])[0]["COUNT(*)"];
    
    $owner = false;
    
    $owner_id = run_query("SELECT owner_id FROM games WHERE name = :game_name;", [':game_name' => $game_name])[0]["owner_id"];
    $owner_username = run_query("SELECT username FROM users WHERE id = :id", [":id" => $owner_id])[0]["username"];
    if (isset($_SESSION["user_id"]) && $owner_id == $_SESSION["user_id"]) {
        $owner = true;
    }
    $limit = 15;
    $page = 1;
    if (isset($_GET['limit']) && intval($_GET['limit'])) {
        $limit = $_GET['limit'];
    }
    if (isset($_GET['page']) && intval($_GET['page'])) {
        $page = $_GET['page'];
    }
    if ($page < 1) {
        $page = 1;
    }
    $offset = $limit * ($page - 1);
    if ($offset >= $total_scores) {
        $page = ceil($total_scores / $limit);
        $offset = $limit * ($page - 1);
    }
    if ($total_scores > 0) {
        $game = $_GET['game'];
        $all_scores = run_query("SELECT score, user_id, achieved_at, id FROM scores WHERE game_version_id = :id ORDER BY score DESC;", [':id' => $game_version_id]);
        $temp = $all_scores;
        $scores = array_splice($temp, $offset, $limit);
        $temp = [];
        foreach ($all_scores as $score) {
            $temp[] = $score['score'];
        }
        $all_scores = $temp;
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ENET: <?php echo $game_name . " v"  . $version; ?> </title>
        <link rel="stylesheet" href="/serve_resources.php?type=css&file=table_style.css">
    </head>
    <?php if (isset($_SESSION["user_id"]) && (($_SESSION["user_permissions"] == 0))) {?>
    <script>
        function delete_score(id) {
            const response = fetch("/handle_POST.php", {
            method: "POST",
            credentials: "include",
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({request: "delete_score", data: { score_id: id}})
            });
            const row = document.getElementById(id);
            const new_row = document.createElement('tr');
            const text = document.createElement('span');
            new_row.appendChild(text);
            text.innerText = 'Score deleted';
            row.replaceWith(new_row);
            
        }
    </script>
    <?php } ?>
    <body>
        <?php if($total_scores > 0) { ?>
        <p>Showing scores <?php  echo ($offset + 1) . " - " . (sizeof($scores) + $offset) . " out of " . $total_scores;  ?></p>
        <span>Page <?php echo $page . " / " . ceil($total_scores / $limit); ?></span>
        <a href=<?php echo "/?url=game&game=" . $_GET['game'] . "&page=" . ($page + 1) . "&limit=" . $limit; ?>><button>Next</button></a>
        <a href=<?php echo "/?url=game&game=" . $_GET['game'] . "&page=" . ($page - 1) . "&limit=" . $limit; ?>><button>Previous</button></a>
        <form action="/?url=game">
            <span>Limit: </span>
            <input type="number" name="limit" value=<?php echo $limit; ?>>
            <input type="hidden" name="page" value=<?php echo $page; ?>>
            <input type="hidden" name="game" value=<?php echo $_GET['game']; ?>>
            <input type="hidden" name="url" value="game">
            <button>Go!</button>
        </form>
        <br>
        <table border=1 frame=void rules=rows>
            <tr>
                <th>Score</th>
                <th>User</th>
                <th>Time</th>
                <th>Rank</th>
                <?php if (isset($_SESSION["user_id"]) && (($_SESSION["user_permissions"] == 0))) {?>
                <th>Delete</th>
                <?php } ?>
            </tr>
            <?php
                $rank = $offset;
                foreach ($scores as $s) {
                    ?> <tr id='<?php echo $s['id']; ?>'> <?php
                    $rank += 1;
                    foreach ($s as $d => $detail) {
                        if ($d !== 'id') {
                        ?> <td> <?php
                        if ($d == "user_id") {
                            echo '<a href="/?url=users&user=' . $owner_username .'"><p>' . $owner_username . '</p></a>';
                        } elseif ($d == 'score') {
                            if (strpos(strval($detail), ".") !== false) {
                                $digits = strlen(rtrim(substr(strrchr($detail, '.'), 1), '0'));
                                echo number_format($detail, $digits);
                            } else {
                                echo number_format($detail);
                            }
                        }
                        else {
                            echo $detail;
                        }
                        ?> </td> <?php }
                    } 
                    #Get new rank
                    $new_rank = $rank;
                    if (!($rank == 1)) {
                        if ($all_scores[$rank - 1] == $all_scores[$rank - 2]) {
                            $new_rank = array_search($all_scores[$rank - 1], $all_scores) + 1;
                        }
                    }
                    
                    
                    ?>
                    <td><?php echo $new_rank; ?></td>
                    <?php if (isset($_SESSION["user_id"]) && (($_SESSION["user_permissions"] == 0))) {?>
                    <td><button onclick="delete_score(<?php echo $s['id']; ?>)">Delete</button></td>
                    </tr> <?php }
                }
            ?>
        </table>
        <?php } else {
            ?> 
            <p>No scores recorded</p>
            <?php
        }
        ?>
    </body>
    </html>

<?php }