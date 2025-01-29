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

    <!-- Prevent the browser from caching the page -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

   
 

    <link rel="stylesheet" href="style.css">
    <title>Document</title>
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
    <h2>DATABASES</h2>
    <a href="view_data.php">View Employee Education Data</a><br><br>
    <a href="emp.php">View Employee Details</a><br><br>
    <a href="view_bank.php">View Employee Bank Details</a><br><br>
    <a href="view_family.php">View Family details</a><br><br>
    
    <a href="view_last.php">Last Employment Details</a><br><br><br> 
        
    <div id="logout">
    <a href="reset_login.php">Logout</a>
</div>


    <!-- Prevent the user from going back to the previous page (again) -->
    <script type="text/javascript">
        window.history.forward();
    </script>




    
</body>
</html>
