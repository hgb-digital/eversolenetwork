<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ENET: Active Connections (ADMIN)</title>
    <link rel="stylesheet" href="/serve_resources.php?type=css&file=table_style.css">
</head>
<body>
    <h1>Active Connections</h1>
    <input type="button" value="Refresh" onclick="show_connections();">
    <br><br>
    <div> <table border=1 frame=void rules=rows id="connections_table">
        <tr>
            <th>Session #</th>
            <th>User</th>
            <th>Game</th>
            <th>Stats</th>
            <th>IP</th>
            <th>Session ID</th>
            <th>Uptime</th>
            <th>Last Ping</th>
            <th>Disconnect</th>
        </tr>
    </table> </div>
    
    <script type="module">
        function disconnect_user(id) {
            const response = fetch("/handle_POST.php", {
                method: "POST",
                credentials: "include",
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({data: {id: id}, request: "disconnect_user"}),
                }).then(reload => show_connections());
        }
        window.disconnect_user = disconnect_user;
        window.show_connections = show_connections;
        async function get_connections() {
            const result = await fetch("/handle_POST.php", {
            method: "POST",
            credentials: "include",
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({data: {}, request: "get_connections"})
            });
            const data = await result.json();
            return data;
            
        }
        async function show_connections() {
            for (var c = 0; c < (document.getElementById("connections_table").children.length - 1); c++) {
                document.getElementById('connections_table').removeChild(document.getElementById("connections_table").children[c + 1])
            }
            let connections = await get_connections();
            if (connections.length == 0) {
                const detail = document.createElement('td')
                detail.innerText = "No Active Connections";
                document.getElementById("connections_table").appendChild(detail);
            } else {
                const details = ["id", "username", "game", "earned", "ip", "session_identifier" , "connected", "last_ping"];
                for (var i = 0; i < connections.length; i++) {
                    const row = document.createElement('tr');
                    for (var d = 0; d < details.length; d++) {
                        const detail = document.createElement('td');
                        if (details[d] == "connected") {
                            const ping = new Date(connections[i]["connected"]);
                            const now = new Date();
                            var uptime = (ping.getTime() - now.getTime()) / 1000;
                            uptime /= 60;
                            uptime = Math.abs(Math.round(uptime));
                            detail.innerText = uptime.toString().concat("m");
                        } else {
                            detail.innerText = connections[i][details[d]];
                        }
                        row.appendChild(detail);
                    }
                    let disconnect = document.createElement('input');
                    disconnect.type = 'button';
                    disconnect.value = 'Disconnect';
                    disconnect.setAttribute( "onClick", "disconnect_user("+connections[i]['id']+");");
                    disconnect.id = 'disconnect_'.concat(connections[i]['id']);
                    let container = document.createElement('td');
                    container.appendChild(disconnect);
                    row.appendChild(container);
                    document.getElementById("connections_table").appendChild(row);
                }
            }

        }
        
        window.addEventListener('load', () => {
            setTimeout(() => {
            show_connections();
            }, 100); // 100–200ms is usually enough
        });

        
        setInterval(function(){ 
            show_connections();
        }, 10000);
        
    </script>

</body>
</html>