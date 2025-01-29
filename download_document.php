<?php
// Start session or authentication if needed
session_start();

// Get the file path from the query string
if (!isset($_GET['file']) || empty($_GET['file'])) {
    echo "No file specified.";
    exit();
}

$file_path = urldecode($_GET['file']);  // Decode the URL-encoded file path

// Ensure the file exists
if (!file_exists($file_path)) {
    echo "File not found.";
    exit();
}

// Force the file to be downloaded
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
header('Content-Length: ' . filesize($file_path));
readfile($file_path);
exit();
?>
