<?php
session_start();

// Check if the user is a manager
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'manager') {
    die("You must be logged in as a manager to block/unblock users.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $block = $_POST['block']; // Either '1' to block or '0' to unblock

    // Database connection
    $conn = new mysqli("localhost", "root", "", "employee");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update the is_blocked status for the user
    $stmt = $conn->prepare("UPDATE info SET is_blocked = ? WHERE username = ?");
    $stmt->bind_param("is", $block, $username);

    if ($stmt->execute()) {
        echo "User " . ($block == 1 ? "blocked" : "unblocked") . " successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Block User</title>
</head>
<body>
    <h1>Block/Unblock User</h1>
    <form action="block_user.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required><br><br>

        <label for="block">Block User:</label>
        <select name="block" id="block" required>
            <option value="1">Block</option>
            <option value="0">Unblock</option>
        </select><br><br>

        <input type="submit" value="Submit">
    </form>
</body>
</html>
