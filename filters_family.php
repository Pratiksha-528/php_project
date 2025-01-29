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

// Check if the form is submitted (if Employee_ID is passed via POST)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Employee_ID'])) {
    // Get the selected Employee ID from POST request
    $selected_Employee_ID = $conn->real_escape_string($_POST['Employee_ID']);  // Prevent SQL injection

    // SQL query to select data based on Employee ID
    $sql = "SELECT `Employee ID`, `Sr_no`, `Family member name`, `Family relation`, `mobile number` 
            FROM family
            WHERE `Employee ID` = '$selected_Employee_ID'
            ORDER BY `Employee ID` DESC";  // Sorting by Employee ID

    $result = $conn->query($sql);

    // Check if there are results
    if ($result->num_rows > 0) {
        // Start the table and define column headers
        echo "<table border='1'>
                <tr>
                    <th>Employee ID</th>
                    <th>Sr_no</th>    
                    <th>Family member name</th>
                    <th>Family relation</th>
                    <th>Mobile number</th>
                </tr>";

        // Loop through the result and display each employee's details
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row["Employee ID"]) . "</td>
                    <td>" . htmlspecialchars($row["Sr_no"]) . "</td>
                    <td>" . htmlspecialchars($row["Family member name"]) . "</td>
                    <td>" . htmlspecialchars($row["Family relation"]) . "</td>
                    <td>" . htmlspecialchars($row["mobile number"]) . "</td>
                </tr>";
        }
        echo "</table>";
    } else {
        echo "No results found for the selected Employee ID.";
    }
} else {
    // If the form is not submitted, show family details for a specific employee (using session or URL parameter)
    if (isset($_GET['Employee_ID'])) {
        $selected_Employee_ID = $conn->real_escape_string($_GET['Employee_ID']);  // Sanitize input

        // SQL query to display family data for the selected Employee ID
        $sql = "SELECT `Employee ID`, `Sr_no`, `Family member name`, `Family relation`, `mobile number` 
                FROM family
                WHERE `Employee ID` = '$selected_Employee_ID'
                ORDER BY `Employee ID` DESC";

        $result = $conn->query($sql);

        // Check if results exist
        if ($result->num_rows > 0) {
            // Start the table and define column headers
            echo "<table border='1'>
                    <tr>
                        <th>Employee ID</th>
                        <th>Sr_no</th>    
                        <th>Family member name</th>
                        <th>Family relation</th>
                        <th>Mobile number</th>
                    </tr>";

            // Loop through the result and display each employee's family details
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row["Employee ID"]) . "</td>
                        <td>" . htmlspecialchars($row["Sr_no"]) . "</td>
                        <td>" . htmlspecialchars($row["Family member name"]) . "</td>
                        <td>" . htmlspecialchars($row["Family relation"]) . "</td>
                        <td>" . htmlspecialchars($row["mobile number"]) . "</td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "No results found.";
        }
    }
}

// Close the database connection
$conn->close();
?>

<br>
<a href='home.php'>Home</a><br>
<a href='reset_login.php'>Logout</a>

</body>
</html>
