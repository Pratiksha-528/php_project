<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "employee");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_message = "";
$success_message = "";

// Step 1: Check if the user is authenticated (username stored in session)
if (!isset($_SESSION['username'])) {
    header("Location: forgot_password.php");  // If not, redirect to request password reset
    exit();
}

// Step 2: Handle form submission to reset password
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reset_password'])) {
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($new_password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        // No password hashing, storing the plain password
        $username = $_SESSION['username'];
        $stmt = $conn->prepare("UPDATE info SET password = ? WHERE username = ?");
        $stmt->bind_param("ss", $new_password, $username);

        if ($stmt->execute()) {
            $success_message = "Password has been successfully reset!";
            unset($_SESSION['username']);  // Clear the session after resetting the password
        } else {
            $error_message = "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
        }
        form {
            max-width: 300px;
            margin: 0 auto;
        }
        label {
            display: block;
            margin-bottom: 10px;
        }
        input[type="password"], input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            border: none;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px;
        }
        a {
            color: #333;
            text-decoration: none;
        }
        a:hover {
            color: #4CAF50;
        }

        .login input
        {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 20px;
            size: large;
        }
    </style>
</head>
<body>
    <h1>Reset Your Password</h1>
   <br><br><br>
    <?php if (!empty($error_message)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>

    <?php if (!empty($success_message)): ?>
        <p style="color: green;"><?php echo htmlspecialchars($success_message); ?></p><br><Br>
        <div class="login"><a href="reset_login.php">Login with your new password</a></class></div>
    <?php else: ?>

    <!-- Form to enter new password -->
    <form action="reset_password.php" method="POST">
        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" id="new_password" required><br><br>

        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" name="confirm_password" id="confirm_password" required><br><br>

        <div class="submit"><input type="submit" name="reset_password" value="Reset Password"></div>
    </form>

    <?php endif; ?>

    <br><br><br><br><br><br><br>
    <p><a href="reset_login.php">Back to Login</a></p>
</body>
</html>
