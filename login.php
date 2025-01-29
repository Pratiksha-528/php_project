<?php
session_start();

// Check for form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = new mysqli("localhost", "root", "", "employee");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the user exists and fetch their role and blocked status
    $stmt = $conn->prepare("SELECT password, role, is_blocked FROM info WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($db_password, $role, $is_blocked);
        $stmt->fetch();

        // Directly compare entered password with stored password
        if ($password === $db_password) {
            // Check if the user is blocked
            if ($is_blocked) {
                echo "Your account has been blocked. Please contact the administrator.";
                exit;
            } else {
                // Set session for the user
                $_SESSION['username'] = $username;
                $_SESSION['user_role'] = $role;

                // Check if the user is a manager or not
                if ($role === 'manager') {
                    header("Location: reset_register.php");  // Redirect to registration page
                    exit;
                } else {
                    echo "You are not a manager and cannot access this page.";
                }
            }}else {
                echo "Invalid username or password.";
            }
        } else {
            echo "Invalid username or password.";
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
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }
        .container {
            width: 300px;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .login {
            width: 100%;
            padding: 2px;
            color: white;
            border: none;
            border-radius: 5px;
        }
        .login input {
            background-color: rgb(15, 207, 60);
        }
        .login input:hover {
            background-color: rgb(25, 124, 30);
        }
        .register {
            text-align: center;
            padding: 10px;
            color: rgb(15, 207, 60);
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>Login as Manager</h1>

    <div class="container">
        <form action="login.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>

            <div class="login"><input type="submit" value="Login"></div>
        </form>
    </div>

    <div class="register">
        <input type="button" value="Register as Manager first" onclick="window.location.href='register_manager.php';">
    </div>
</body>
</html>
