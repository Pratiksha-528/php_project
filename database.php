<?php
// Database connection settings
$servername = "localhost";  // Database server (usually localhost)
$username = "root";         // Database username
$password = "";             // Database password (empty for localhost with default MySQL)
$dbname = "employee";  // Your database name

// Create a connection to the MySQL database using MySQLi
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    // If there's an error with the connection, display the error message and stop execution
    die("Connection failed: " . $conn->connect_error);
}

// If successful, the $conn object is now available to interact with the database
?>
