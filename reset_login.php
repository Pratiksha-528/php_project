<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "employee");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize an error message
$error_message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
   
    // Prepare and execute the query to fetch user info based on username
    $stmt = $conn->prepare("SELECT * FROM info WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Check if the user is blocked (0 = blocked, 1 = active)
        if ($row['status'] == 0) {
            $error_message = "This account has been blocked by the manager.";
        } else {
            // Verify password (use password_verify if passwords are hashed)
            if ($password === $row['password']) {
                $_SESSION['username'] = $username; 
                $_SESSION['role'] = $row['role']; // Store the role from the database
                header("Location: login2.php"); // Redirect to home page
                exit();
            } else {
                $error_message = "Invalid username or password.";
            }
        }
    } else {
        $error_message = "Invalid username or password.";
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
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
    <style type="text/css">
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            padding: 50px;
            margin: 0;
        }

        /* Navigation Bar Style */
        .navbar {
            background-color: #333;
            overflow: hidden;
            padding: 10px;
        }

        .navbar a {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
            font-size: 17px;
        }

        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }

        /* Main Content */
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        form {
            padding: 20px;
            width: 300px;
            display: inline-block;
            text-align: left;
        }

        label {
            font-size: 14px;
            margin: 8px 0 5px;
            display: block;
        }

        input[type="text"], input[type="password"], select {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            border: none;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        p {
            font-size: 14px;
            margin-top: 10px;
        }

        .forgot {
            color: black;
            text-decoration: none;
            font-size: 14px;
            margin-top: 10px;
        }

        .forgot:hover {
            text-decoration: underline;
        }

        .register {
            color: white;
            background-color: black;
            padding: 10px;
            text-decoration: none;
            font-size: 14px;
            border-radius: 4px;
        }

        .register:hover {
            background-color: #444;
        }

        /* Button Styles */
        .button {
            display: inline-block;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
        }

        .button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
        
    <!-- Sidebar Section -->
<div style="position: fixed; top: 0; left: 0; width: 200px; height: 100%; background-color: #333; padding-top: 20px; text-align: center;">
    
    <a href="login.php" class="sidebar" style="margin: 10px 0; display: block; margin-top:30px;">Login as Manager</a>
    <a href="manager_dashboard.php" class="sidebar" style="margin: 10px 0; display: block;margin-top:50px;">Go to Manager Dashboard</a>
    
</div>


    <!-- Main Content -->
    <h1>Login</h1>
    <!-- Display error message if any -->
    <?php if (!empty($error_message)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>

    <!-- Login Form -->
    <form action="reset_login.php" method="POST">
        <div class="role">
            <label for="role">Role:</label>
            <select name="role" id="role" required>
                <option value="">Select Role</option>
                <option value="admin">Admin</option>
                <option value="edit_user">Edit user</option>
                <option value="delete_user">Delete user </option>
                <option value="view">View</option> 
            </select>
        </div>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <div class="login">
            <input type="submit" value="Login">
        </div>
    </form>

    <br><br><br><p>Forgot your password?<br><a href="forgot_password.php" class="forgot">Forgot Password</a></p>   

    <br><br><br><br><br><br><br><br><p> New user?<br><br><a href="reset_register.php" class="register">Register</a></p>

</body>
</html>
