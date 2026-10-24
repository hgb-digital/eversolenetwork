<?php

function post_score($data) {
    if ($data !== null) {
        require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/scripts/features/functions.php");
        
        if (check_empty([$data['score'], $data['session_id']])) {
            return ["errors" => ["empty_input"]];
        }

        function postScore(float $score, string $session_id) {
            $session = get_session($session_id);
            if ($session) {
                run_query("INSERT INTO scores (score, user_id, game_id, game_version_id) VALUES (:score, :user_id, :game_id, :game_version_id);",
                [":user_id"=>$session['user_id'], ":score"=>$score, ":game_id" => $session['game_id'], ":game_version_id" => $session['game_version_id']]); 
                $score_id = run_query("SELECT LAST_INSERT_ID() AS id;")[0]["id"];

                $query = "
                    WITH ranked_scores AS (
                        SELECT 
                            id,
                            RANK() OVER (ORDER BY score DESC) AS rank
                        FROM 
                            scores
                        WHERE
                            game_version_id = :version_id
                    )
                    SELECT *
                    FROM ranked_scores
                    WHERE id = :id;
                ";

                $result_count = run_query($query, [':id'=>$score_id, ":version_id" => $session['game_version_id']])[0];
                if ($result_count['rank'] == 1) {
                    obtain_achievement(5, $session['user_id'], $session['id']);
                    reward('xp', $session['user_id'], 50, $session['id']);
                    reward('coins', $session['user_id'], 20, $session['id']);
                }
                obtain_achievement(4, $session['user_id'], $session['id']);
                reward('xp', $session['user_id'], 10, $session['id']);
                reward('coins', $session['user_id'], 5, $session['id']);

                $pr = false;

                $query = "
                    WITH ranked_scores AS (
                        SELECT 
                            id,
                            RANK() OVER (ORDER BY score DESC) AS rank
                        FROM 
                            scores
                        WHERE
                            user_id = :user_id AND game_version_id = :version_id
                    )
                    SELECT *
                    FROM ranked_scores
                    WHERE id = :id;
                ";
                $pr_result = run_query($query, [':id'=>$score_id, ":user_id"=>$session['user_id'], ":version_id"=>$session['game_version_id']])[0];
                if ($pr_result['rank'] == 1){
                    $pr = true;
                }
                run_query("UPDATE sessions SET logged_scores = logged_scores + 1 WHERE id = :id;", [":id" => $session['id']]);
                return [get_username_from_id($session['user_id']), $score_id, $result_count['rank'], $pr];

            } else {
                return ["errors" => ['invalid_session']];
            }
        }

        $return = postScore($data['score'], $data['session_id']);
        return ["data" => ['score'=>strval($data['score']), 'rank'=>strval($return[2]), "user"=>$return[0], 'id'=>strval($return[1]), 'pr'=>$return[3]]];
        

    } else {
        return ["errors" => ['internal_server']];
    }
}