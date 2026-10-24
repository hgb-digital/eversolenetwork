<?php

function delete_score($data) {
    $score_id = $data['score_id'];

    try {
        run_query("DELETE FROM scores WHERE id=:id;", [":id"=>$score_id]);
        return true;
        
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}