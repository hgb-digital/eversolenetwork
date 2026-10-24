<?php

function edit_request($data) {
    
    $id = $data['id'];
    $edit = $data['edit'];
    try {
        include_once(realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/features/functions.php"));
        include_once(realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/features/notifications.php"));
        if ($edit === "delete") {
            run_query("DELETE FROM game_requests WHERE id=:id;", [":id"=>$id]);
        } elseif ($edit === "accept") {
            run_query("UPDATE game_requests SET status = 'approved' WHERE id=:id;", [":id"=>$id]);

            $ignore_post = true;
            include_once(realpath($_SERVER['DOCUMENT_ROOT'] . "../private/scripts/features/notifications.php"));
            
            //get request data from ID
            $request_data = run_query("SELECT * FROM game_requests WHERE id=:id", [':id'=>$id])[0];
            $username = run_query("SELECT username FROM users WHERE id = :id;", [":id" => $request_data["user_id"]])[0]["username"];

            if ($request_data["type"] == "new_game") {

                addGame($request_data['game_name'], $username);
                notify($username, 'Your add game request for "' . $request_data['game_name'] . '" has been accepted!', 'internal', "/?url=game");
            } else {
                addGameVersion($request_data['game_id'], $request_data['game_version']);
                notify($username, 'Your add version request (v' . $request_data['game_version'] . ') for "' . $request_data['game_name'] . '" has been accepted!', 'internal', "/?url=game");
            }

        } elseif ($edit === "reject") {
            run_query("UPDATE game_requests SET status = 'rejected' WHERE id=:id;", [":id"=>$id]);
        } else {
            return false;
        }
        return true;

        
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}