<?php
session_start();

if (!isset($_SESSION['security_answer_correct']) || !$_SESSION['security_answer_correct']) {
    header("Location: forgot_password.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "employee");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_SESSION['username'];
    $new_password = trim($_POST['new_password']);
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    
    // Update the password in the database
    $stmt = $conn->prepare("UPDATE info SET password = ? WHERE username = ?");
    $stmt->bind_param("ss", $hashed_password, $username);
    if ($stmt->execute()) {
        $message = "Your password has been successfully updated.";
        unset($_SESSION['security_answer_correct']);  // Clear session after password reset
    } else {
        $message = "Error updating password.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
</head>
<body>

<h1>Change Password</h1>

<?php if (!empty($message)) echo "<p>$message</p>"; ?>

<form action="change_password.php" method="POST">
    <label for="new_password">Enter your new password:</label><br>
    <input type="password" id="new_password" name="new_password" required><br><br>
    <input type="submit" value="Change Password">
</form>

</body>
</html>
