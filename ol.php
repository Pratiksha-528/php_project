<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login Form</title>
    <script type="text/javascript">
        // Prevent the user from using the back button by pushing a state in history
        window.history.forward();

        // To prevent the user from going back in the browser's history stack
        window.onload = function() {
            window.history.pushState(null, null, window.location.href);
            window.onpopstate = function() {
                window.history.pushState(null, null, window.location.href);
            }
        }
    </script>

    
</head>
<body>
    <h1>Login</h1>
    <form action="login2.php" method="POST">
        <label for="username">Username give</label>
        <input type="text" name="username" id="username" required><br><br>

        <label for="password">Password</label>
        <input type="password" name="password" id="password" required><br><br>

        <input type="submit" name="submit" value="Login">
    </form>

    
    </script>



    
</body>
</html>





<?php
    session_start();  // Start session to store user information

    // Database connection
    $conn = new mysqli("localhost", "root", "", "employee");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if login form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT * FROM info WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verify credentials   
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($password === $row['password']) {  // Direct password comparison
                // Store username in session
                $_SESSION['username'] = $username;
                echo "Login successful! <br><br><Br>
                <a href='view_data.php'>View Employee Education Data</a><br><br>
                 <a href ='view_empdata.php'>View employee details </a><br><Br>
                 <a href ='view_bank.php'>View employee bank details</a><br><br>
                 <a href ='view_family.php'>Family details</a><br><br>
                 <a href ='view_last.php'>Last employment details</a><br><br><br> 
                 <a href='reset_login.php'>Logout</a>";
                
                 
            } else {
                echo "Invalid username or password.";
            }
        } else {
            echo "No such user found. <br /><br />
            Go back to<a href ='reset_login.php'>Login</a>";
        }
       
            

        $stmt->close();

        
        
    }

    $conn->close();
    ?>
</body>
</html>







