<?php

include "../../private/maintenance.php";

if (!$maintenance) {
    include "../../private/dbh_manager.php";
    include "../../private/scripts/features/config_session.php";

    if (isset($_SESSION['user_id']) && ($_SESSION['user_permissions'] === 0)) {
        include "../../private/scripts/view/header.php";

        #Open Main Page
        $page = isset($_GET['url']) ? $_GET['url'] : 'home';
        
        
        $folder = "../../private/pages/admin";
        
        $files = glob($folder . "/*.php");
        $file = $folder . "/" . $page . ".php";
        
        
        if (in_array($file, $files)) {
            include $file;
        } else {
            include $folder . "/../404.php";
        }

        include "../../private/scripts/view/footer.php";
    } else {
        header("Location: ../");
    }
} else {
    echo "The server is undergoing maintenance";
}
