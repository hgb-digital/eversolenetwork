<?php

$notification_page = true;

if (isset($_SESSION["user_id"])) {?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ENET: Notifications</title>
</head>
<body>

    <style>
        .notification {
            background-color: yellow;
            border-radius: .5em;
            padding: .2em;
            margin: .4em;
            width: 50%;
            min-width: 20em;
        }
        #notification_container, #notification_container_admin {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: flex-start;
            height: auto;
        }

        #content_wrapper {
            display: flex;
            flex-direction: row;
            gap: 1em;
            align-items: flex-start;
        }

    </style>
    
    <script type="module">
        function clear_notification(id) {
            const response = fetch("/handle_POST.php", {
            method: "POST",
            credentials: "include",
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({data:  {id: id, action: 'delete'}, request: 'notifications'})
            });
            const div = document.getElementById(id);
            const deleted_text = document.createElement('p');
            deleted_text.innerText = 'Notification Cleared';
            div.replaceWith(deleted_text);
            
        }

        function visit_link(id, link) {
            window.location.assign(link);
        }

        function create_notification(container, element) {
            let notification = document.createElement('div');
            notification.id = element['id'];
            notification.classList.add('notification')
            let body = document.createElement('h3');
            body.innerText = element['message'];
            notification.appendChild(body)
            let time = document.createElement('p');
            time.innerText = element['created_at'];
            notification.appendChild(time);
            
            let deleteButton = document.createElement('button');
            deleteButton.innerText = 'Clear'
            deleteButton.addEventListener('click', () => clear_notification(element['id']));
            notification.appendChild(deleteButton);

            if (element['link'] !== null) {
                let linkButton = document.createElement('button');
                linkButton.innerText = 'Visit'
                linkButton.addEventListener('click', () => visit_link(element['id'], element['link']));
                notification.appendChild(linkButton);
            }
            
            container.append(notification);
        }

        async function load_notifications() {
            const result = await fetch("/handle_POST.php", {
            method: "POST",
            credentials: "include",
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({data: {action: 'get'}, request: "notifications"})
            });
            const raw = await result.json();
            const data = raw[0];

            // Handle Notifications
            let container = document.getElementById('notification_container');
            if (data.length > 0) {
                for (let index = 0; index < data.length; index++) {
                    const element = data[index];
                    create_notification(container, element)
                }

            } else {
                let message = document.createElement('p');
                message.innerText = "No Notifications";
                container.appendChild(message);
            }
        }
        load_notifications();
    </script>
    <div id="content_wrapper">
        <div id="notification_container">
            <h3>Notifications</h3>
            
        </div>

    </div>

</body>
</html>

<?php } else {
    header('Location: /index.php');
    die();
};