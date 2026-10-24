<?php

function update_server_messages($data) {
    #motd or alert
    $type = $data['type'];
    $msg = $data['msg'];

    if (!in_array($type, ['motd', 'alert'])) {
        return true;
    }

    file_put_contents(realpath($_SERVER['DOCUMENT_ROOT'] . '/../private/scripts/view/messages/' . $type . '.txt'), $msg);
    return true;
}