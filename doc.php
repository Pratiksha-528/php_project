<?php
// Ensure the file path is safe
if (isset($_GET['file'])) {
    $file = $_GET['file'];

    // Validate the file path
    $allowedExtensions = ['pdf', 'docx', 'jpg', 'png'];  // Add other allowed file types
    $fileExtension = pathinfo($file, PATHINFO_EXTENSION);
    
    if (in_array($fileExtension, $allowedExtensions) && file_exists($file)) {
        // Secure the download process
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: inline; filename="' . basename($file) . '"');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit();
    } else {
        echo "File not found or unsupported file type.";
    }
} else {
    echo "No file specified.";
}
?>
