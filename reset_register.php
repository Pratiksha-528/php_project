 <?php
 session_start();   
// Check if user is logged in and is a manager
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'manager') {
    die("You must be logged in as a manager to register users.");
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Registration Form</title>
    <style type="text/css">
        
        /* Basic Reset */   
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Body Styling */
body {
    font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
    background-color: #f0f0f0;
    color: #333;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    flex-direction: column;
}

/* Container Styling*/
.container {
    
    padding: 40px;
    
    
    width: 400px;
    text-align: left;
}

/* Title Styling */
h1 {
    font-size: 28px;
    color: #333;
    margin-bottom: 20px;
}

/* Input fields styling */
input[type="text"],
input[type="password"],
select {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border: 2px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    color: #333;
    outline: none;
}

input[type="text"]:focus,
input[type="password"]:focus,
select:focus {
    border-color:rgb(16, 16, 16);
}

/* Button Styling */
.register input {
    width: 100%;
    padding: 12px;
    background-color:green;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
}

.register input:hover {
    background-color:rgb(17, 99, 40);
}

/* Links Styling */
.login, .forgot {
    color:rgb(52, 154, 55);
    text-decoration: none;
    font-size: 20px;
}

.login:hover, .forgot:hover {
    text-decoration: underline;
}

/* Error Message Styling */
p.error {
    color: red;
    font-size: 14px;
    margin-top: 10px;
}

/* Role dropdown styling */
.role {
    margin-bottom: 20px;
}

.select-role {
    width: 100%;
    padding: 12px;
    border: 2px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    outline: none;
}

.select-role:focus {
    border-color:rgb(16, 17, 16);
}

/* Small screen (Mobile) adjustments */
@media (max-width: 600px) {
    .container {
        width: 90%;
        padding: 20px;
    }   
    h1 {
        font-size: 24px;
    }
    .register input {
        padding: 10px;
    }
    .login, .forgot {
        font-size: 12px;
    }

}
</style>
    
</head>
<body>
    <h1>Register</h1>
<div class-="container">
    <form action="reset_register.php" method="POST">

    <div class="role"><label for="role">Role:</label>
    <select name="role" id="role">
        <option value="">Select Role</option>
        
        <option value="admin">Admin</option>
        <option value="edit_user">Edit user</option>
        <option value="delete_user">Delete user </option>
        <option value="view">View</option> 
    </select>
    </div>
       <div class="box"> <label for="username">Username:</label>
        <input type="text" name="username" id="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br><br>
        </div>

        
        <br>
        <div class="register">
        <input type="submit" name="submit" value="Register"><Br><br><br>
        
        </div>
    </form>
</div>
 <!-- Sidebar Section -->
 <div style="position: fixed; top: 0; left: 0; width: 200px; height: 100%; background-color: #333; padding-top: 20px; text-align: center;">
    
    <a href="login.php" class="sidebar" style="margin: 10px 0; display: block; margin-top:30px;">Login as Manager</a>
    <a href="manager_dashboard.php" class="sidebar" style="margin: 10px 0; display: block;margin-top:50px;">Go to Manager Dashboard</a>
    
</div>
    

    <br>
    <br>
    <br>
    <p>Already have an account? <br><br> <a href="reset_login.php" class="login">Login</a></p>

    <script type="text/javascript">
        window.history.forward();
        window.onload = function() {
            window.history.pushState(null, null, window.location.href);
            window.onpopstate = function() {
                window.history.pushState(null, null, window.location.href);
            }
        }
    </script>

    <?php
    
    $conn = new mysqli("localhost", "root", "", "employee");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];  


        $role=$_POST['role'];

        $stmt = $conn->prepare("SELECT * FROM info WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0){ ?> 
            <p style = "color:red;"> <?php echo "Username already exists. Please choose a different username.";?></p><br><br><br><br>
            <?php 
        } else {
            // Insert the new user
            $stmt = $conn->prepare("INSERT INTO info (username, password , role) VALUES (?, ?,?)");
            $stmt->bind_param("sss", $username, $password,$role);
    
            if ($stmt->execute()) {
                echo "Registration successful! <br><br><a href='reset_login.php'>Login here</a>";
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    
        $stmt->close();
    }

    $conn->close();
    ?>
</body>
</html>



