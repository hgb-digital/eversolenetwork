<?php

// types:
// internal - server message

function notify(string $user, string $body, string $type, string $link = null) {
    $user_id = run_query("SELECT id FROM users where username = :username", [':username' => $user])[0]["id"];
    run_query("INSERT INTO notifications (message, link, type, user_id) VALUES (:msg, :link, :type, :user_id)", [":msg" => $body, ":link" => $link, ":type" => $type, ":user_id" => $user_id]);
}

function getNotificationsCount(string $user) {
    $id = run_query("SELECT id FROM users where username = :username;", [':username' => $user])[0]["id"];
    return run_query("SELECT COUNT(*) FROM notifications WHERE user_id = :id AND seen = 0;", [':id' => $id])[0]["COUNT(*)"];
}

function getNotifications(string $user) {
    $id = run_query("SELECT id FROM users where username = :username", [':username' => $user])[0]["id"];
    return run_query("SELECT * FROM notifications WHERE user_id = :id;", [':id' => $id]);
}

function setSeen(string $user) {
    $id = run_query("SELECT id FROM users where username = :username", [':username' => $user])[0]["id"];
    run_query("UPDATE notifications SET seen = 1 WHERE user_id = :id;", [':id' => $id]);
}

function deleteNotification(int $id) {
    return run_query("DELETE FROM notifications WHERE id = :id", [':id' => $id]);
}

function notifications($data) {

    //add code to handle individual users and admin
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/features/config_session.php");

    $request = $data['action'];

    if (!isset($_SESSION['user_id'])){
        echo json_encode(['errors' => ['internal_server']]);
        die();
    }


    if ($request == "count") {
        $username = $_SESSION['user_username'];
        $count = getNotificationsCount($username);
        echo json_encode([$count]);
        die();
    }

    if ($request == "get") {
        $username = $_SESSION['user_username'];
        setSeen($username);
        echo json_encode([getNotifications($username)]);
        die();
    }

    if ($request == "delete") {

        $id = $data['id'];
        echo json_encode(deleteNotification($id));
        die();
    }

}