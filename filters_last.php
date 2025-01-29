<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Last Employment Details</title>
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

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the selected value from the form
    $selected_Immediate_superior_name = $conn->real_escape_string($_POST['Immediate_superior_name']);
        
    // SQL query to select data based on user input
    $sql = "SELECT `Employee ID`, `Employer name`, `Immediate_superior_name`, `Email id`, `Date of joining`, `Date of termination`
            FROM `last employment`
            WHERE `Immediate_superior_name` = '$selected_Immediate_superior_name'
            ORDER BY `Immediate_superior_name` DESC";

    $result = $conn->query($sql);

    // Check if there are results
    if ($result->num_rows > 0) {
        // Start the table and define column headers
        echo "<table border='1'>
                <tr>
                    <th>Employee ID</th>
                    <th>Employer Name</th>    
                    <th>Immediate Superior Name</th>
                    <th>Email ID</th>
                    <th>Date of Joining</th>
                    <th>Date of Termination</th>
                    
                </tr>";
        
        // Loop through the result and display each employee's details
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row["Employee ID"]) . "</td>
                    <td>" . htmlspecialchars($row["Employer name"]) . "</td>
                    <td>" . htmlspecialchars($row["Immediate_superior_name"]) . "</td>
                    <td>" . htmlspecialchars($row["Email id"]) . "</td>
                    <td>" . htmlspecialchars($row["Date of joining"]) . "</td>
                    <td>" . htmlspecialchars($row["Date of termination"]) . "</td>
                   
                </tr>";
        }
        echo "</table>";
    } else {
        echo "No results found for the selected Immediate Superior Name.";
    }
} else {
    // If the form is not submitted, show all data
    $sql = "SELECT `Employee ID`, `Employer name`, `Immediate_superior_name`, 
            `Email id`, `Date of joining`, `Date of termination`
            FROM `last employment`";
    $result = $conn->query($sql);

    // Check if there are any results
    if ($result->num_rows > 0) {
        // Start the table and define column headers
        echo "<table border='1'>
                <tr>
                    <th>Employee ID</th>
                    <th>Employer Name</th>    
                    <th>Immediate Superior Name</th>
                    <th>Email ID</th>
                    <th>Date of Joining</th>
                    <th>Date of Termination</th>
                    
                </tr>";
        
        // Loop through the result and display each employee's details
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row["Employee ID"]) . "</td>
                    <td>" . htmlspecialchars($row["Employer name"]) . "</td>
                    <td>" . htmlspecialchars($row["Immediate_superior_name"]) . "</td>
                    <td>" . htmlspecialchars($row["Email id"]) . "</td>
                    <td>" . htmlspecialchars($row["Date of joining"]) . "</td>
                    <td>" . htmlspecialchars($row["Date of termination"]) . "</td>
                    
                </tr>";
        }
        echo "</table>";
    } else {
        echo "No results found.";
    }
}

// Close the database connection
$conn->close();
?>

<br>
<a href ='home.php'>Home</a><br>
<a href ='reset_login.php'>Logout</a>
</body>
</html>