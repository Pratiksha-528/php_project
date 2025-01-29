<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Prevent the browser from caching the page -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

   
       
<!-- <script type="text/javascript">
        // Prevent the user from going back to the previous page
        window.history.forward();
        window.onload = function() {
            setTimeout(function() {
                window.history.forward();
            }, 0);
        };
    
</script>





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
    
    </script> -->
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
    $role=trim($_POST['role']);

    // Prepare and execute the query
    $stmt = $conn->prepare("SELECT * FROM info WHERE username = ? and role = ?");
    $stmt->bind_param("ss", $username, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verify password (use password_verify if passwords are hashed)
        if ($password === $row['password']) {
            $_SESSION['username'] = $username; 
            // Store session
            // After successful login, store the role in the session
           $_SESSION['role'] = $role;  // "editor" or "deleter"

            header("Location: login2.php"); // Redirect to home page
            exit();
        } else {
            $error_message = "Invalid username,role or password.";
        }
    } else {
        $error_message = "Invalid username,role or password.";
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
        .register {
            background-color: black;
            color: white;
            padding: 10px;
            text-decoration: none;
            display: inline-block;
            align-items: center;
            justify-content: center;
            text-align: center;
            height: 15px;
             width: 70px;
        }

        .login input{
            width: 100px;
            padding: 5px;
            margin-bottom: 10px;
            border: 3px  solid black;
        }

        .role{
            padding-bottom:20px;
            padding-top: 10px;
            align-items: center;
            justify-content: center;
            text-align: center;

            
        }

        .forgot{
            background-color: black;
            color: white;
            padding: 10px;
            text-decoration: none;
            display: inline-block;
            font-size: small;
            width:150px;
            height:10px;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

       
        
    </style>

    

</head>

<body>
    <h1>Login</h1>
    <!-- Display error message if any -->
    <?php if (!empty($error_message)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>

    <!-- Login Form -->
    <form action="reset_login.php" method="POST">
    <div class="role"><label for="role">Role:</label>
    <input type="hidden" name="role" value=""> 
    <select name="role" id="role"required>
        <option value="">Select Role</option>
        <option value="admin">Admin</option>
        <option value="edit_user">Edit user</option>
        <option value="delete_user">Delete user </option>

        <!-- <option value="guest">Guest</option> -->

    </select>
</div>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <div class="login"><input type="submit" value="Login">
        </div>
    </form>

<br><br><br><p>Forgot your password?<br><a href="reset_forgot_password.php" class="forgot">Forgot Password</a></p>   
    
<!-- forget password reset form 
<form action="password_reset_request.php" method="POST">
    <label for="email">Enter your email:</label>
    <input type="text" id="email" name="email" required>
    <input type="submit" value="Request Password Reset">
</form> -->

     <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><p> New user?<br><a href="reset_register.php" class="register">Register</a></p>
     <!-- <br><br><br><br><br><br><br><p>Forgot your password?><a href="reset_forgot_password.php" class="register">Forgot Password</a></p>   -->
</body>
</html>

