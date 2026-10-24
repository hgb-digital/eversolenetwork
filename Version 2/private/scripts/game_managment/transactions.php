<?php

function transactions($data) {
    include_once realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/features/functions.php");

    $session = get_session($data['session_id']);
    if (!isset($session['user_id'])) {
        return ["errors" => ['internal_server']];
    }

    $amount = intval($data['amount']);

    if (!isset($amount)) {
        return ["errors" => ['internal_server']];
    }

    #if amount is negative, ensure balance is sufficient
    if ($amount < 0) {
        $coins = run_query("SELECT coins FROM users WHERE id = :id;", [":id"=>$session['user_id']])[0]['coins'];
        $coins = intval($coins);
        if ($coins < -$amount) {
            return ["errors" => ['insufficient_funds']];
        }
    }

    ## add checks to regulate econonmy

    run_query("INSERT INTO transactions (value, vendor_id, user_id, session_id) VALUES (:v, :vid, :uid, :sid);", [":v"=>$amount, ":vid"=>$session['game_version_id'], ":uid"=>$session['user_id'], ":sid"=>$session['id']]);
    run_query("UPDATE users SET coins = coins + :c WHERE id = :id;", [":id" => $session['user_id'], ":c" => $amount]);
    run_query("UPDATE sessions SET earned_coins = earned_coins + :c WHERE session_identifier = :s;", [":s" => $data['session_id'], ":c" => $amount]);
    return ['data' => ["user" => $session['user_id'], "amount" => $amount]];
}