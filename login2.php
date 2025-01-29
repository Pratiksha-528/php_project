<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: reset_login.php"); // Redirect to login page if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Home</title>
    <style type="text/css">
    body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    flex-direction: column;
}

h1 {
    color: #333;
}

a {
    display: block;
    margin: 10px 0;
    padding: 10px;
    text-decoration: none;
    color: white;
    background-color: #007BFF;
    border-radius: 5px;
    text-align: center;
    width: 200px;
}

a:hover {
    background-color:rgb(2, 55, 111);
}

#logout{
    background-color:rgb(11, 83, 131);
    color: white;
}
</style>
<!-- Prevent the browser from caching the page -->
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

   
       
<script type="text/javascript">
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
    </script>
</head>
<body>

    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
   
        <a href="view_data.php">View Employee Education Data</a><br><br>
        <a href="emp.php">View Employee Details</a><br><br>
        <a href="view_bank.php">View Employee Bank Details</a><br><br>
        <a href="view_family.php">View  Family Details</a><br><br>
        <a href="view_last.php">View Last Employment Details</a><br><br>
        
    <div id="logout">
    <a href="reset_login.php">Logout</a>
</div>

   
</body>
</html> 






<style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; flex-direction: column; }
        .container { width: 300px; padding: 20px; background-color: white; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; }
        button { width: 100%; padding: 10px; background-color: green; color: white; border: none; border-radius: 5px; }
        button:hover { background-color: #007B3A; }
    </style>
