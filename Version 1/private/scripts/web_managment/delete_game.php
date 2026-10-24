<?php

function delete_game($data) {

    $id = $data["id"];

    try {
        
        run_query("DELETE FROM games WHERE id=:id;", [':id'=> $id]);

        return true;
        
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }

}