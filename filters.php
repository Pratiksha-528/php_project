<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="">
    <title>Document</title>

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
    

<?php
session_start();  // Start the session to check login status

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    echo "You need to <a href='reset_login.php'>login</a> first.";
    exit();
}

echo "Welcome, " . $_SESSION['username'] . "!<br><br>";
?>



<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "employee");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selected_year = $_POST['year'];
    $selected_high = $_POST['high'];

    // SQL query to filter data
    $sql = "SELECT `Employee Name`, `Highest level of education`, `Year of completion` 
            FROM education 
            WHERE `Year of completion` = '$selected_year' 
            AND `Highest level of education` = '$selected_high'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table border='1'><tr><th>Employee Name</th><th>Highest level of education</th><th>Year of completion</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row["Employee Name"] . "</td><td>" . $row["Highest level of education"] . "</td><td>" . $row["Year of completion"] . "</td></tr>";
        }

        
    
        echo "</table>";
    } else {
        echo "No results found for the selected criteria.";
    }
    
}

$conn->close();
?>
<br>
<a href ='home.php'>Home</a><br>
<a href ='reset_login.php'>Logout</a>
</body>
</html>