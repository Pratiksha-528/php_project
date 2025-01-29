<?php
if (isset($_GET['file'])) {
    $file = urldecode($_GET['file']); // Decode the file path

    // Check if file exists
    if (file_exists($file)) {
        // Set headers for file download
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Content-Length: ' . filesize($file));
        header('Pragma: public');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        readfile($file); // Read and output the file
        exit;
    } else {
        echo "File not found!";
    }
} else {
    echo "Invalid file request.";
}
