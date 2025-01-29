<?php
session_start();

// Check if the user is logged in and has the "manager" role
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'manager') {
    // Redirect to login page if 
    
    header("Location: create_account.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "employee");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Validate the role (ensure it's one of the allowed roles)
    $allowed_roles = ['edit_user', 'delete_user','admin', 'view'];
    if (!in_array($role, $allowed_roles)) {
        echo "Invalid role selected.";
        exit;
    }

    // Check if the username already exists
    $stmt = $conn->prepare("SELECT * FROM info WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<p style='color:red;'>Username already exists. Please choose a different username.</p>";
    } else {
        // Hash the password securely
        $password = password_hash($password, PASSWORD_DEFAULT);

        // Insert the new user
        $stmt = $conn->prepare("INSERT INTO info (username, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $password, $role);

        if ($stmt->execute()) {
            echo "Account created successfully! <br><br><a href='login.php'>Login here</a>";
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    $stmt->close();
}

$conn->close();
?>
