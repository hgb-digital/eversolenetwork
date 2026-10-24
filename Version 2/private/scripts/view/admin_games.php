<h1>Games</h1> 
        <table border=1 frame=void rules=rows>
        <tr>
            <th>Name</th>
            <th>Owner</th>
            <th>ID</th>
            <th>Created</th>
            <th>Scores</th>
            <th>Password</th>
            <th>Options</th>
        </tr>
        <?php
        $empty = true;
        foreach ( $games as $row ) {
            $empty = false;
            $counts = run_query("SELECT COUNT(*) FROM scores WHERE game_id = :id;", [":id" => $row['id']])[0]["COUNT(*)"];
            $owner = run_query("SELECT username FROM users WHERE id = :id", [":id" => $row['owner_id']])[0]['username'];
            
            ?> 
            <tr>
                <td><a href="../?url=game&game=<?php echo $row['name']; ?>"><?php echo $row['name']; ?></a></td>
                <td><?php echo $owner ?></td>
                <td><?php echo $row['id'] ?></td>
                <td><?php echo $row['created_at'] ?></td>
                <td><?php echo $counts ?></td>
                <td><input id=<?php echo "showpassword_" . $row['id'] ?> type='button' value='Show Password' onclick='showpassword(<?php echo $row['id'] ?>);' /></td>
                <td><input id=<?php echo "delete_" . $row['id'] ?> type='button' value='Delete' onclick='delete_game(<?php echo $row['id'] ?>);' /></td>
                <script>
                game_data[<?php echo $row['id']; ?>] = {};
                game_data[<?php echo $row['id']; ?>]['password'] = <?php echo $row['password']; ?>;
                game_data[<?php echo $row['id']; ?>]['name'] = '<?php echo $row['name']; ?>';
                </script>
            </tr>
        <?php } 
        if ($empty) {
            ?> <tr><td>No Games</td></tr> <?php
        }
        ?>
</table> 