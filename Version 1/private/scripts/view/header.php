<link rel="stylesheet" href="/serve_resources.php?type=css&file=table_style.css">
<link rel="stylesheet" href="/serve_resources.php?type=css&file=universal_styles.css">
<?php
$notification_page = null; 
if (isset($_GET['url']) && ($_GET['url'] == "notifications")) {
    $notification_page = true;
} ?>
<script type="module">
    <?php if (empty($notification_page) && isset($_SESSION['user_username'])) { ?>
        async function get_notifications(username) {
            const result = await fetch("/handle_POST.php", {
            method: "POST",
            credentials: "include",
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({data: {action: "count"}, request: "notifications"})
            });
            const data = await result.json();
            let button = document.getElementById('notifications');
            let text = "Notifications (".concat(data[0]).concat(')');
            button.innerText = text;
        }
        get_notifications();
    <?php } ?>
</script>
<style>
    .navigation {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #333;
        padding: 10px 20px;
        margin: 0px;
    }
    .nav-links {
        list-style: none;
        display: flex;
        gap: 20px;
        padding-left: 5px;
    }
    .nav-links li a {
        color: white;
        text-decoration: none;
        font-size: 1em;
        transition: color 0.3s ease;
    }
    .nav-links li a:hover {
        color: lightgreen;
    }
    .acc-dropdown {
        position: relative;
        display: inline-block;
    }
    .acc-dropdown a {
        color: white;
        font-size: 16px;
        border: none;
        cursor: pointer;
        border-radius: 4px;
    }
    .acc-content {
        background-color: #333;
        display: none;
        position: absolute;
        z-index: 1;
    }           
    .acc-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        
    }
    .acc-dropdown:hover .acc-content {
        display: block;
    }
</style>
<header>
<nav class="navigation">
    <ul class="nav-links">
            
            <li><a href="/">Home</a></li>
            <li><a href="/?url=game">Games</a></li>
            <li><a href="/?url=users">Users</a></li>
            <?php
            if (isset($_SESSION['user_id'])) {
                if ($_SESSION["user_permissions"] == 0) { ?>
                    <li ><a href="/admin?url=admin-panel">Admin Panel</a></li>
                    <?php }
                    
                } ?>
    </ul>
    <?php if (isset($_SESSION['user_id'])) { ?>
        <ul class="nav-links">
            <li class="acc-dropdown">
                <a href="/?url=users&user=<?php echo $_SESSION['user_username']; ?>" class="nav-account"><?php echo $_SESSION['user_username']; ?></a>
                <div class="acc-content">
                    <a href="/?url=logout">Logout</a>
                </div>
            </li>
            <?php if (!(!empty($notification_page) && $notification_page)) { ?>
                <li><a href="/?url=notifications" id="notifications">Fetching notifications...</a></li>
                <?php } ?>
            </ul>
    <?php } else {?>
            <ul class="nav-links">
            <li><a href="/?url=login">Login</a></li>
            </ul>
    <?php } ?>
            
</nav>
</header>