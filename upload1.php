<?php
session_start();  // Start the session to check login status

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    echo "You need to <a href='reset_login.php'>login</a> first.";
    exit();
}

// Define the upload directory
$targetDir = "uploads/";
$targetFile = $targetDir . basename($_FILES["file"]["name"]);
$uploadOk = 1;
$fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

// Check if file is a valid document (optional)
if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES["file"]["tmp_name"]);
    if ($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}

// Allow certain file formats (e.g., pdf, docx, jpg, png)
if ($fileType != "pdf" && $fileType != "docx" && $fileType != "jpg" && $fileType != "png") {
    echo "Sorry, only PDF, DOCX, JPG, and PNG files are allowed.";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
} else {
    // Attempt to upload the file
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
        echo "The file " . htmlspecialchars(basename($_FILES["file"]["name"])) . " has been uploaded.";
        
        // Store file path in the database (assuming you have a column for file path)
        $conn = new mysqli("localhost", "root", "", "employee");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $filePath = $targetFile;  // Path to the uploaded file
        $employeeId = $_SESSION['employee_id']; // Assuming employee ID is stored in session

        // Update the database with the uploaded file path
        $sql = "UPDATE education SET `View Document` = '$filePath' WHERE `Employee ID` = '$employeeId'";
        
        if ($conn->query($sql) === TRUE) {
            echo "File path saved successfully in the database.";
        } else {
            echo "Error: " . $conn->error;
        }

        $conn->close();
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>
