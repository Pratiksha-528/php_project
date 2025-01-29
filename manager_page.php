<?php
session_start();  // Start the session

// Check if the user is logged in and has the 'manager' role
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'manager') {
    // Redirect to login page if not logged in or not a manager
    header("Location: login.php");
    exit(); // End script execution
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Page</title>
</head>
<body>
    <h1>Welcome to the Manager Page!</h1>
    
    <p>Only managers have access to this page.</p>
    
    <!-- Optionally add manager-specific content -->
    <h2>Manage User Accounts</h2>
    <a href="reset_register.php">Create a New User</a><br><br>
    <a href="logout.php">Logout</a>
</body>
</html>
    