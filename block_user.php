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

    // Toggle user status (1 -> 0 or 0 -> 1)
    $result = $conn->query("SELECT status FROM info WHERE id = $user_id");
    $row = $result->fetch_assoc();

    $new_status = ($row['status'] == 1) ? 0 : 1;

    $stmt = $conn->prepare("UPDATE info SET status = ? WHERE id = ?");
    $stmt->bind_param("ii", $new_status, $user_id);

    if ($stmt->execute()) {
        header("Location: manager_dashboard.php"); // Redirect back to the dashboard
        exit();
    } else {
        echo "Error updating user status.";
    }

    $stmt->close();
    $conn->close();
}
?>
