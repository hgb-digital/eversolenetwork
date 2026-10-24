<?php
// serve.php

// Get query parameters
$type = $_GET['type'] ?? '';
$file = $_GET['file'] ?? '';

// Validate file name (prevent directory traversal)
if (!preg_match('/^[a-zA-Z0-9_\-]+\.[a-zA-Z0-9]+$/', $file)) {
    http_response_code(400);
    echo "Invalid file name.";
    exit;
}

// Map type to directory and content type
$baseDir = realpath(__DIR__ . '/../private/scripts/view/resources/');
$mimeTypes = [
    'css' => ['dir' => 'css', 'type' => 'text/css'],
    'js' => ['dir' => 'javascript', 'type' => 'application/javascript'],
    'img' => ['dir' => 'images', 'type' => null], // We'll detect image type dynamically
];

// Check valid type
if (!isset($mimeTypes[$type])) {
    http_response_code(400);
    echo "Invalid type.";
    exit;
}

// Build full path
$resourcePath = realpath("$baseDir/{$mimeTypes[$type]['dir']}/$file");

// Ensure it's inside the private directory
if (!$resourcePath || strpos($resourcePath, $baseDir) !== 0 || !file_exists($resourcePath)) {
    http_response_code(404);
    echo "File not found.";
    exit;
}

// Set content type
if ($type === 'img') {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $resourcePath);
    finfo_close($finfo);
    header("Content-Type: $mime");
} else {
    header("Content-Type: {$mimeTypes[$type]['type']}");
}

// Serve file
readfile($resourcePath);
exit;
