<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ENET: User List (ADMIN)</title>
    <link rel="stylesheet" href="/serve_resources.php?type=css&file=table_style.css">
</head>
<?php
    //get all user data
    $users = run_query("SELECT id, username, email, created, coins, xp, permissions, authorized FROM users");
?>
<body>
    <style>
        .selected {
            background-color: orangered;
        }
    </style>
    <h2>Master User List</h2>
    <table border=1 frame=void rules=rows>
        <tr>
            <th>ID</td>
            <th>Username</th>
            <th>Email</th>
            <th>Joined</th>
            <th>Coins</th>
            <th>XP</th>
            <th>Perm</th>
            <th>Auth</th>
    </tr>
        <?php
        foreach ($users as $user) {
            echo "<tr>";
            foreach ($user as $key=>$detail) {
                if ($key == "authorized") {
                    if ($user['id'] == 1 || $user['id'] == $_SESSION['user_id']) {
                        if ($detail == 1) {
                            echo "<td>Yes</td>";
                        } else {
                            echo "<td>No</td>";
                        }
                    } else {
                        echo "<td style='background: orangered;'><input type='checkbox' " . ($detail ? "checked" : "") . " class='authorized_toggle' data-user-id='" . $user['id'] . "'></td>";
                    }
                }   
                elseif ($key == "permissions") {
                    if ($user['id'] == 1 || $user['id'] == $_SESSION['user_id']) {
                        if ($detail == 1) {
                            echo '<td>USR</td>';
                        } elseif ($detail == 0) {
                            echo '<td style="color: orangered;">ADMIN</td>';
                        }
                    } else {
                        echo "<td><button class='admin_toggle" . (!$detail ? " selected" : "") . "' data-user-id='" . $user['id'] . "'>Admin</button></td>";
                    }
                } elseif ($key == "username") {
                    echo '<td><a href="/?url=users&user=' . $detail . '">' . $detail . "</a></td>";
                }
                else {
                    echo '<td>' . $detail . '</td>';
                }
            }
            echo "</tr>";
        }
        ?>
    </table>

    <script>

        const authorized_boxes = document.querySelectorAll(".authorized_toggle");
        authorized_boxes.forEach(box => {
            box.addEventListener("change", function() {
                const response = fetch("/handle_POST.php", {
                method: "POST",
                credentials: "include",
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({request: "alter_user", data: { user_id: this.dataset.userId, state: Number(this.checked), attr: "auth"}})
                }).then(() => {window.location.reload();});

                })
        });

        const admin_toggles = document.querySelectorAll(".admin_toggle");
        admin_toggles.forEach(box => {
            box.addEventListener("click", function() {
                var perm = 0;
                if (this.classList.contains("selected")) {
                    perm = 1;
                }
                const response = fetch("/handle_POST.php", {
                method: "POST",
                credentials: "include",
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({request: "alter_user", data: { user_id: this.dataset.userId, state: perm, attr: "perm"}})
                }).then(() => {window.location.reload();});

                })
        });

    </script>
</body>
</html>