<?php
// Prevent caching of the page to ensure that the page cannot be accessed via the back button
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="style.css"> -->
    <title>Prevent Back Button</title>
    
    <style>
        body{
            background-color:lightcyan;
            color: black;
            
        }

        
    </style>
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

if ($_SERVER["REQUEST_METHOD"] == "POST" ) {
    $selected_State = $conn->real_escape_string($_POST['State']);  // Prevent SQL injection

    // Prepared statement to prevent SQL injection
    $sql = "SELECT `Employee ID` ,`Employee Name`, `Present Address`, `Present Pincode`, 
                   `Permanent Address`, `Permanent Area`, `Permanent Pincode`, `State`
            FROM empdata1
            WHERE `State` = '$selected_State'
            ORDER BY `Employee ID` ";

    /*if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $selected_State);  // "s" means string parameter
        $stmt->execute();
        $result = $stmt->get_result();*/
        
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table border='1'>
                    <tr>
                    <th>Employee ID</th>
                        <th>Employee Name</th>
                        <th>Present Address</th>
                        <th>Present Pincode</th>
                        <th>Permanent Address</th>
                        <th>Permanent Area</th>
                        <th>Permanent Pincode</th>
                        <th>State</th>
                    </tr>";

            // Loop through the result and display each employee's detail
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                     <td>". htmlspecialchars($row["Employee ID"]). "</td>
                        <td>" . htmlspecialchars($row["Employee Name"]) . "</td>
                        <td>" . htmlspecialchars($row["Present Address"]) . "</td>
                        <td>" . htmlspecialchars($row["Present Pincode"]) . "</td>
                        <td>" . htmlspecialchars($row["Permanent Address"]) . "</td>
                        <td>" . htmlspecialchars($row["Permanent Area"]) . "</td>
                        <td>" . htmlspecialchars($row["Permanent Pincode"]) . "</td>
                        <td>" . htmlspecialchars($row["State"]) . "</td>
                      </tr>";
            }
        
            echo "</table>";
        } else {
            echo "No results found for the selected state.";
        }
        
} else {
    // Display all employees if no filter is applied
    $sql = "SELECT `Employee ID`, `Employee Name`, `Present Address`, `Present Pincode`, 
            `Permanent Address`, `Permanent Area`, `Permanent Pincode`, `State`
            FROM empdata1
            ORDER BY `Employee ID`";  // Sorted by Employee Name for readability

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table border='1'>
                <tr>
                    <th>Employee ID</th>
                    <th>Employee Name</th>
                    <th>Present Address</th>
                    <th>Present Pincode</th>
                    <th>Permanent Address</th>
                    <th>Permanent Area</th>
                    <th>Permanent Pincode</th>
                    <th>State</th>
                </tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row["Employee ID"]) . "</td>
                    <td>" . htmlspecialchars($row["Employee Name"]) . "</td>
                    <td>" . htmlspecialchars($row["Present Address"]) . "</td>
                    <td>" . htmlspecialchars($row["Present Pincode"]) . "</td>
                    <td>" . htmlspecialchars($row["Permanent Address"]) . "</td>
                    <td>" . htmlspecialchars($row["Permanent Area"]) . "</td>
                    <td>" . htmlspecialchars($row["Permanent Pincode"]) . "</td>
                    <td>" . htmlspecialchars($row["State"]) . "</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "No results found.";
    }

}

$conn->close();
?>
<Br>
<br><br><br><br>
<div id="home"><a href ='home.php'>Home</a><br><br>    
<a href ='reset_login.php'>Logout</a>
</div>


</body>
</html>
