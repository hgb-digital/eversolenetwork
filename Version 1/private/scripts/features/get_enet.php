<?php

function get_enet($data) {
    $enet = realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/enet_download/current.pck");
    if (file_exists($enet)) {
        //Get file type and set it as Content Type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        header('Content-Type: ' . finfo_file($finfo, $enet));
        finfo_close($finfo);
        
        //Use Content-Disposition: attachment to specify the filename
        header('Content-Disposition: attachment; filename='.basename($enet));
        
        //No cache
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        
        //Define file size
        header('Content-Length: ' . filesize($enet));
        
        ob_clean();
        flush();
        readfile($enet);
        exit;
    }
}