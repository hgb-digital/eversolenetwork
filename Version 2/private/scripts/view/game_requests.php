<style>
        #game_name, #game_version {
            padding-right: 5.0em;
        }
        .approved {
            background: greenyellow;
        }
        .rejected {
            background: orangered;
        }
        .pending {
            background: yellow;
        }
        .deselected {
            background-color: white;
        }
        .selected {
            background-color: yellowgreen;
        }
    </style>

<?php
    function load_requests(string $type) {
        if ($type === "new_game") {
            $requests = run_query("SELECT id, user_id, game_name, created_at, status FROM game_requests WHERE type = 'new_game' ORDER BY status ASC");
        } else {
            $requests = run_query("SELECT id, user_id, game_id, game_version, created_at, status FROM game_requests WHERE type = 'new_version' ORDER BY status ASC");
        }
        $return_HTML = "";
        if ($requests) {
            foreach ($requests as $request) {
                $return_HTML .= "<tr id ='" . $request['id'] . "'>";
                $pending = false;
                foreach($request as $title=>$detail) {
                    if ($title === "status") {
                        $return_HTML .= "<td class='" . $detail . "'>" . strtoupper($detail) . "</td>";
                        if ($detail === "pending") {
                            $pending = true;
                        }
                    } elseif ($title === "user_id") {
                        $username = run_query("SELECT username FROM users WHERE id = :id;", [":id" => $detail])[0]["username"];
                        $return_HTML .= "<td>" . $username . "</td>";
                    } elseif ($title === 'game_id') {
                        $game_name = run_query("SELECT name FROM games WHERE id = :id;", [":id" => $detail])[0]["name"];
                        $return_HTML .= "<td>" . $game_name . "</td>";
                    }
                    else {
                        $return_HTML .= "<td>" . $detail . "</td>";
                    }
                }
                
                if (!$pending) {
                    $return_HTML .= "<td><button onclick='" . "delete_request(" . strval($request['id']) . ")'>" . "Delete" . "</button></td>";
                } else {
                    $return_HTML .= '
                    <td>
                    <form action="/handle_POST.php" method="post"><button>Accept</button>
                    <input type="hidden" value="' . strval($request['id']) . '" name="id">
                    <input type="hidden" value="accept" name="edit">
                    <input type="hidden" value="edit_request" name="request">
                    <input type="hidden" value="admin.game-manager" name="redirect">
                    </form>
                    
                    <form action="/handle_POST.php" method="post"><button>Reject</button>
                    <input type="hidden" value="' . strval($request['id']) .'" name="id">
                    <input type="hidden" value="reject" name="edit">
                    <input type="hidden" value="admin.game-manager" name="redirect">
                    <input type="hidden" value="edit_request" name="request">
                    </form>
                    </td>
                    ';
                }
                $return_HTML .= "</tr>";
            }
        } else {
            $return_HTML .= "<tr><td>No new game requests</td></tr>";
        }
        return $return_HTML;
    }

    $new_games_count = run_query("SELECT COUNT(*) FROM game_requests WHERE type = 'new_game' AND status = 'pending';")[0]["COUNT(*)"];
    $new_version_count = run_query("SELECT COUNT(*) FROM game_requests WHERE type = 'new_version' AND status = 'pending';")[0]["COUNT(*)"];
    ?>

    <button id="game_button" class="deselected">New Games (<?php echo $new_games_count; ?>)</button>
    <button id="version_button" class="deselected">New Versions (<?php echo $new_version_count; ?>)</button>

    <p>----</p>

    <div id='table_holder'></div>

    <script>
        
        function delete_request(id) {
            const response = fetch("/handle_POST.php", {
                method: "POST",
                credentials: "include",
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({data: {id: id, edit: 'delete'},  request: "edit_request"})
            }).then(response => {location.reload();});
            
        }
        
        function new_game_requests() {
            document.querySelector("#game_button").classList = ["selected"];
            document.querySelector("#version_button").classList = ["deselected"];

            $table_holder = document.getElementById("table_holder");
            $table_holder.innerHTML = `
            <table border=1 frame=void rules=rows>
            <tr>
            <td>ID</td>
            <td>User</td> 
            <td>Game Name</td>
            <td>Requested</td>
            <td>Status</td>
            <td>Options</td>
            </tr>
            <?php echo load_requests("new_game"); ?>
            </table>
            
            `;
            
        }

        function new_version_requests() {
            document.querySelector("#version_button").classList = ["selected"];
            document.querySelector("#game_button").classList = ["deslected"];

            $table_holder = document.getElementById("table_holder");
            $table_holder.innerHTML = `
            <table border=1 frame=void rules=rows>
            <tr>
            <td>ID</td>
            <td>User</td> 
            <td>Game Name</td>
            <td>Version</td>
            <td>Requested</td>
            <td>Status</td>
            <td>Options</td>
            </tr>
            <?php echo load_requests("new_version"); ?>
            </table>
            
            `;
        }

        new_game_requests();

        document.querySelector('#version_button').addEventListener('click', new_version_requests)
        document.querySelector('#game_button').addEventListener('click', new_game_requests)
        </script>