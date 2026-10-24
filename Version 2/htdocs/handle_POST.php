<?php

function http_return(string $redirect_var, bool $success = false) {
    $directory = "";
    $split = explode(".", $redirect_var);
    if (sizeof($split) > 1) {
        $directory = $split[0] . "/";
        $redirect_var = $split[1];
    } else {
        $redirect_var = $split[0];
    }

    if ($success) {
        $redirect_var = $redirect_var . "&result=success";
    }

    header('Location: /' . $directory . '?url=' . $redirect_var);
    die();
}

function send_errors($errors, $redirect, $js, $form_sendback = null) {
    if (!$js) {
        $_SESSION['form_errors'] = $errors;
        if (isset($form_sendback)) {
            $_SESSION['form_sendback'] = $form_sendback;
        }

        http_return($redirect);
    } else {
        echo json_encode(["errors" => $errors]);
        die();
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include "../private/maintenance.php";

    if ($maintenance) {
        http_response_code(503);
        die();
    }

    include_once(realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/dbh_manager.php"));
    include_once(realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/features/config_session.php"));
    #error_reporting(0);

    #get data
    $data_to_send = file_get_contents('php://input');

    #convert data to JSON
    $decoded_json = json_decode($data_to_send, true);

    //decides whether a redirect is necessary (js runs behind the scenes)
    $js_request = true;
    
    if ($decoded_json === null) {
        $decoded_json = $_POST;
        $js_request = false;

        $data = [];
        $data['data'] = [];
        foreach ($decoded_json as $detail => $value) {
            if (in_array($detail, ['request', 'redirect'])) {
                $data[$detail] = $value;
            } else {
                $data['data'][$detail] = $value;
            }
        }

    } else {
        $data = $decoded_json;
    }

    include_once(realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/features/functions.php"));

    #call function
    if (!isset($data['request']) || !isset($data['data'])) {
        send_errors(["internal_server"], isset($data['redirect']) ? $data['redirect'] : 'home', $js_request);
    }

    $game_init = false;
    #check who's sending the request
    if (isset($data['session_id']))  {
        $sender = 'game';
    } else {
        $sender = 'web';
    }
    
    //function:user permission (-1 = none, 0 = admin, 1 = basic user, 2 = user authenticated OR admin, 3 = user specific or admin
    $game_functions = ['game_login' => -1, 'game_logout' => -1, 'ping' => -1, 'post_score' => 1, 'pull_data' => -1, 
    'get_enet' => -1, "game_init" => -1, "save_state" => 1, "transactions" => 1];

    $form_functions = ['login' => -1, 'signup' => -1, 'logout' => 1, 'add_game_version' => 0, 'add_game' => 0, 'delete_game' => 0, 'delete_score' => 0, 
    'disconnect_user' => 0, 'edit_request' => 0, 'get_connections' => 0, 'get_owned_games' => -1, 'request_game' => 1, 
    'notifications' => 1, 'reward_user' => 0, 'alter_user' => 2, 'update_server_messages' => 0, "resend_verification" => 3,
    'send_password_reset' => -1];
    
    $functions  = $form_functions;
    if ($sender == 'game') {
        $functions = $game_functions;
    }

    if (!key_exists($data['request'], $functions)) {
        send_errors(["internal_server"], isset($data['redirect']) ? $data['redirect'] : 'home', $js_request);
    }

    //ensure proper permissions
    function load_session($sender, $session_id) {
        if ($sender == "web") {
            return $_SESSION;
        } else {
            $temp = get_session($session_id);
            $user_permissions = run_query("SELECT permissions FROM users WHERE id = :id;", [":id" => $temp['user_id']])[0]["permissions"];
            return ['user_id'=>$temp['user_id'], "user_permissions" => $user_permissions];
        }  
    }

    $authorized = false;
    
    switch ($functions[$data['request']]) {
        case -1:
            $authorized = true;
            break;
        case 1:
            $session = load_session($sender, $data['session_id'] ?? null);
            if (isset($session['user_id']) && (in_array($session['user_permissions'], [0, 1]))) {
                $authorized = true;
            }
            break;

        case 2:
            $session = load_session($sender, $data['session_id'] ?? null);
            if (isset($session['user_id'])) {
                if ($session['user_permissions'] == 0) {
                    $authorized = true;
                } else {
                    if (isset($data['data']['pwd'])) {
                        $user_pwd = run_query("SELECT password FROM users WHERE id = :id;", [":id" => $session['user_id']])[0]['password'];
                        if (password_verify($data['data']['pwd'], $user_pwd)) {
                            $authorized = true;

                            if ($data['request'] == "alter_user") {
                                $data['data']['user_id'] = $session['user_id'];
                                $data['data']['user_alter'] = true;
                            }
                        } else {
                            send_errors(["password_verification_failed"], isset($data['redirect']) ? $data['redirect'] : 'home', $js_request);
                        }
                    }
                }
            }  elseif (isset($data['data']['token'])) {
                    // for now tokens can only be used to alter passwords
                    $request = run_query("SELECT user_id, expires FROM email_verification WHERE token = :t;", [":t" => $data['data']['token']]);
                    if ($request && (new DateTime() < new DateTime($request[0]['expires']))) {
                        if ($data['request'] == 'alter_user') {
                            //password only
                            $data['data']['attr'] = 'password';
                            $data['data']['user_id'] = $request[0]['user_id'];
                        }
                        $authorized = true;
                    }
                }

            break;
        
        case 3:
            $session = load_session($sender, $data['session_id'] ?? null);
            if (isset($session['user_id'])) {
                $authorized = true;
                if ($session['user_permissions'] == 0) {
                    break;
                }

                //ensures that the user is only operating for themselves and NOT another id
                $data['data']['user_id'] = $session['user_id'];
            }
            break;

        case 0:
            $session = load_session($sender, $data['session_id'] ?? null);
            if (isset($session['user_id']) && ($session['user_permissions'] === 0)) {
                $authorized = true;
            }
            break;
    }

    if (!$authorized) {
        send_errors(["not_authorized"], isset($data['redirect']) ? $data['redirect'] : 'home', $js_request);
    }


    switch ($data['request']) {
        case 'login':
            $path = realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/login/login_script.php");
            include($path);
            break;

        case 'signup':
            $path = realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/signup/signup.php");
            include($path);
            break;

        case 'logout':
            $path = realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/login/logout.php");
            include($path);
            break;

        case 'notifications':
            $path = realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/features/notifications.php");
            include($path);
            break;
        
        case "get_enet":
            include(realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/features/get_enet.php"));
            break;
        
        default:
            include(realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/". $sender . '_managment/' . $data['request'] . ".php"));
            break;
    }

    $return = call_user_func($data['request'], $data['data']);
    if ($data['request'] === "get_enet") {
        die();
    }

    //handle errors
    //will return an array of errors and their id (eg. empty_input), 
    //this will be sent to a session variable that the returning file will process
    if (!($return === true)) {
        if (isset($return['errors'])) {
            send_errors($return['errors'], $data['redirect'] ?? null, $js_request, isset($data['form_sendback']) ? $data['form_sendback'] : null);
        } elseif (isset($return['data'])) {
            echo json_encode($return['data']);
        } else {
            send_errors(['internal_server'], $data['redirect'] ?? null, $js_request);
        }
    } else {
        echo json_encode([]);
    }
    
    if (!$js_request) {
        http_return($data['redirect'], true);
    }

    die();

} else {
    header('Location: /');
    die();
}