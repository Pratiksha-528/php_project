<?php
session_start();  // Start the session

// Database connection
$conn = new mysqli("localhost", "root", "", "employee");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the form data
$username = $_POST['username'];
$password = $_POST['password'];

// Prepare the query to fetch the user's password and role
$stmt = $conn->prepare("SELECT password, role FROM info WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

// Check if the username exists
if ($stmt->num_rows > 0) {
    $stmt->bind_result($stored_password, $role);
    $stmt->fetch();

    // Compare the plain text password
    if ($password == $stored_password) {
        // Password is correct, set the session variables
        $_SESSION['username'] = $username;  // Store the username in session
        $_SESSION['role'] = $role;          // Store the role in session

        // Redirect to a protected page (e.g., manager-only page)
        header("Location: manager_page.php");
        exit();
    } else {
        echo "Incorrect password.";
    }
} else {
    echo "Username not found.";
}

$stmt->close();
$conn->close();
?>
