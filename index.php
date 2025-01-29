<?php
session_start();

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Connect to the database
    $conn = new mysqli("localhost", "root", "", "employee");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get the data from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if a manager already exists in the database
    $result = $conn->query("SELECT * FROM info WHERE role = 'manager'");

    if ($result->num_rows == 0) {
        // Proceed with registration if no manager exists
        $stmt = $conn->prepare("SELECT * FROM info WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Username already exists. Please choose a different username.";
        } else {
            // Insert new user as manager
            $stmt = $conn->prepare("INSERT INTO info (username, password, role) VALUES (?, ?, ?)");
            $role = 'manager'; // Always set the role to 'manager'
            $stmt->bind_param("sss", $username, $password, $role);

            if ($stmt->execute()) {
                echo "Manager registered successfully! <br><br><a href='login.php'>Login here</a>";
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    } else {
        // If a manager already exists, prevent further registration
        echo "A manager already exists. Only one manager is allowed.";
    }
    

    
          
    
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Manager</title>
    <style>
        /* Basic Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Body Styling */
body {
    font-family: Arial, Helvetica, sans-serif;
    background-color: #f4f4f4;
    color: #333;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    flex-direction: column;
    padding: 20px;
}

/* Title Styling */
h1 {
    font-size: 32px;
    color: #333;
    margin-bottom: 30px;
}

/* Form Container */
form {
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 30px;
    width: 100%;
    max-width: 400px;
    text-align: left;
}

/* Form Label */
label {
    font-size: 16px;
    margin-bottom: 5px;
    display: inline-block;
}

/* Form Input Fields */
input[type="text"],
input[type="password"] {
    width: 100%;
    padding: 12px;
    margin-bottom: 15px;
    border: 2px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
    outline: none;
    transition: border 0.3s ease-in-out;
}

/* Input Focus Effect */
input[type="text"]:focus,
input[type="password"]:focus {
    border-color: #4CAF50;
}

/* Submit Button */
input[type="submit"] {
    width: 100%;
    padding: 12px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 18px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

/* Button Hover Effect */
input[type="submit"]:hover {
    background-color: #45a049;
}

/* Link Styling */
a {
    color: #4CAF50;
    text-decoration: none;
    font-size: 16px;
}

a:hover {
    text-decoration: underline;
}

/* Error Message */
.error-message {
    color: red;
    font-size: 14px;
    margin-top: 10px;
}

/* Responsive Styling for Small Screens */
@media (max-width: 600px) {
    h1 {
        font-size: 28px;
    }
    
    form {
        width: 90%;
        padding: 20px;
    }

    input[type="submit"] {
        font-size: 16px;
    }
}

    </style>
</head>
<body>
    <h1>Register as Manager</h1>
    <form action="index.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br><br>

        <input type="submit" value="Register as Manager">
    </form>
    <br><Br>    
<a href='login.php'>Login here as Manager</a>
</body>
</html>
    