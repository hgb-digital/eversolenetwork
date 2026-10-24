<?php

$file = isset($_GET['file']) ? $_GET['file'] : false;

if ($file) {
    if (file_exists(realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/enet_download/" . $file))) {
        header("Content-Description: File Transfer");
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"" . basename(realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/enet_download/" . $file)) . "\"");
        header("Content-Length: " . filesize(realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/enet_download/" . $file)));
        header("Pragma: public");
        header("Cache-Control: must-revalidate");
        header("Expires: 0");

        ob_clean();
        ob_flush();

        readfile(realpath($_SERVER['DOCUMENT_ROOT'] . "/../private/enet_download/" . $file));
        exit;
    }
} else {
    header("Location: /");
}