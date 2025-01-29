<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "employee");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_message = "";
$success_message = "";

// Handle form submission to request password reset
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    
    // Sanitize input
    $username = htmlspecialchars($username);

    // Check if the username exists
    $stmt = $conn->prepare("SELECT * FROM info WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Redirect to the password reset page
        $_SESSION['username'] = $username;  // Store the username in session
        header("Location: reset_password.php");  // Redirect to reset password page
        exit();
    } else {
        $error_message = "Username not found.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
             justify-content: left;
            align-items: left;
            height: 100vh;
            font-family: Arial, sans-serif;
        }
        form {
       
        flex-direction: column;
        width: 300px;
        margin: 0 auto;
        }

        .submit{
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
    </style>
</head>
<body>
    <h1>Forgot Password</h1>

    <?php if (!empty($error_message)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>

    <!-- Form to enter username to request password reset -->
    <form action="forgot_password.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required><br><br>

        <div class="submit" > <input type="submit" value="Request Password Reset"></div>
    </form>

    <br><br>
    <p>Go back to <a href="reset_login.php">Login</a> page.</p>
</body>
</html>
