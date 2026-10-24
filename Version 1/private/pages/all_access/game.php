<?php


require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/view/game_table.php");
require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/view/game_scores.php");

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/features/functions.php");


if (isset($_GET['game'])){
    
    if (check_game_exists($_GET['game'])) {
        $game_id = run_query("SELECT id FROM games WHERE name = :name", [':name' => $_GET['game']])[0]["id"];
        $versions = run_query("SELECT version_number FROM game_versions WHERE game_id = :id;", [":id" => $game_id]);
        $versions = array_column($versions, "version_number");

        $owner_id = run_query("SELECT owner_id FROM games WHERE name = :name", [":name" => $_GET['game']])[0]["owner_id"];

        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $owner_id) {
            $owner = "YOU";
        } else {
            $owner = run_query("SELECT username FROM users WHERE id = :id", [":id" => $owner_id])[0]["username"];
        }

        if (!isset($_GET['version'])) {
            $display_version = "1";
        } else {
            $display_version = $_GET['version'];
        }

        if (!in_array($display_version, $versions)) {
            $display_version = $versions[0];
        }



        ?> 
        <h2><?php echo $_GET['game'] . " v" . $display_version; ?></h2>
        <strong><p>Owner: <?php echo $owner; ?></p></strong>
        <span>Version:</span> 
        <?php

        foreach ($versions as $v) {
            ?> <a href="/?url=game&game=<?php echo $_GET['game']; ?>&version=<?php echo $v; ?>"><button style="background-color:<?php echo ($v == $display_version) ? "yellowgreen" : "white"; ?>;"><?php echo $v; ?></button></a> <?php
        }

        ?> <br><br> <?php

        displayGame($_GET['game'], $game_id, $display_version);

    } else {
        if (isset($_SESSION["user_id"]) && ($_SESSION["user_permissions"] == 0)) {
            header("Location: admin/?url=game-manager");
            die();
        }
        displayTable();
    }

} else {
    if (isset($_SESSION["user_id"]) && ($_SESSION["user_permissions"] == 0)) {
        header("Location: admin/?url=game-manager");
        die();
    }
    displayTable();
}