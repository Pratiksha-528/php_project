<?php
session_start();

// Check if the user is logged in and is a manager
if (!isset($_SESSION['username']) || $_SESSION['user_role'] != 'manager') {
    header("Location: reset_login.php"); // Redirect to login page if not logged in or not a manager
    exit();
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    $conn = new mysqli("localhost", "root", "", "employee");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Delete the user
    $stmt = $conn->prepare("DELETE FROM info WHERE id = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        header("Location: manager_dashboard.php"); // Redirect back to the dashboard
        exit();
    } else {
        echo "Error deleting user.";
    }

    $stmt->close();
    $conn->close();
}
?>
