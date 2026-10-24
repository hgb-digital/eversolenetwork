<?php

function reward_user($data){

    $reward = strtolower($data["reward"]);
    if (!in_array($reward, ['coins', 'xp'])) {
        return ["errors" => "internal_server"];
    }

    $amount = $data["amount"];
    $user_id = $data["user_id"];

    if ($reward == "coins") {
        run_query("UPDATE users SET coins = coins + :c WHERE id = :id;", [":id" => $user_id, ":c" => $amount]);
        run_query("INSERT INTO transactions (value, user_id) VALUES (:v, :id);", [':v'=> $amount, ":id"=>$user_id]);
    } else {
        run_query("UPDATE users SET xp = xp + :x WHERE id = :id;", [":id" => $user_id, ":x" => $amount]);
    }

    return true;

}