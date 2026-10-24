<?php

function alter_user($data) {
    $user_id = $data["user_id"];

    #perm or auth
    $attribute = $data["attr"];
    $state = $data["state"];

    if ($user_id == 1) {
        return false;
    }

    if (!in_array($attribute, ['perm', 'auth'])) {
        return false;
    }

    try {
        if ($attribute == 'auth') {
            run_query("UPDATE users SET authorized = :a WHERE id = :id;", [":a" => $state, "id" => $user_id]);
        } else {
            run_query("UPDATE users SET permissions = :a WHERE id = :id;", [":a" => $state, "id" => $user_id]);
        }
        return true;
        
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}