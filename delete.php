<?php
// Start session to check login status (if needed)
session_start();
if (!isset($_SESSION['username'])) {
    echo "You need to <a href='login.php'>login</a> first.";
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "employee");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if an 'id' is provided in the URL
if (isset($_GET['Employee ID'])) {
    $id = $_GET['Employee ID'];

    // SQL query to delete the record
    $sql = "DELETE FROM education WHERE `Employee ID` = ?";

    // Prepare the statement to prevent SQL injection
    if ($stmt = $conn->prepare($sql)) {
        // Bind the parameter and execute the statement
        $stmt->bind_param("i", $id);  // "i" means the id is an integer
        if ($stmt->execute()) {
            echo "Record deleted successfully. <a href='view_data.php'>Back to records</a>";
        } else {
            echo "Error deleting record: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing query: " . $conn->error;
    }
} else {
    echo "Invalid request. No record ID specified.";
}

$conn->close();
?>
