<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "employee";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = "uploads/";
        $filename = basename($_FILES['file']['name']);
        $filepath = $uploadDir . $filename;

        // Ensure the uploads directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($_FILES['file']['tmp_name'], $filepath)) {
            $stmt = $conn->prepare("INSERT INTO documents (filename, filepath) VALUES (?, ?)");
            $stmt->bind_param("ss", $filename, $filepath);  // Correct binding of parameters
            $stmt->execute();
            $stmt->close();
            echo "File uploaded successfully. <a href='view_data.php'>Go back</a>";
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "No file uploaded or an error occurred.";
    }
}

$conn->close();
?>
