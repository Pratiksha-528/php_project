<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="">
    <title>Document</title>
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
    // Get the selected values from the form
    $selected_Bank_Name = $_POST['Bank_Name'];
    $selected_Branch_Address = $_POST['Branch_Address'];
    
    // SQL query to select data based on user input
    $sql = "SELECT `Employee ID`, `Employee Name`, `Branch address`, `Bank Name`, `Bank Account Holder Name`, `Bank Account Number`, `IFSC Code`
            FROM bank
            WHERE `Bank Name` = '$selected_Bank_Name' 
            AND `Branch address` = '$selected_Branch_Address'
            ORDER BY `Branch address` DESC";  // Sorting by Branch address

    // Execute the query
    $result = $conn->query($sql);

    // Check if there are results
    if ($result->num_rows > 0) {
        // Output the data in a table
        echo "<table border='1'><tr><th>Employee ID</th><th>Employee Name</th><th>Branch_Address</th><th>Bank_Name</th><th>Bank Account Name</th><th>Bank Account Holder Number</th><th>IFSC Code</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row["Employee ID"] . "</td><td>" . $row["Employee Name"] . "</td><td>" . $row["Branch address"] . "</td><td>" . $row["Bank Name"] . "</td><td>" . $row["Bank Account Holder Name"] . "</td><td>" . $row["Bank Account Number"] . "</td><td>" . $row["IFSC Code"] . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "No results found for the selected bank and branch address.";
    }
} else {
    // If the form is not submitted, show all data
    $sql = "SELECT `Employee ID`, `Employee Name`, `Branch address`, `Bank Name`, `Bank Account Holder Name`, `Bank Account Number`, `IFSC Code` FROM bank";
    $result = $conn->query($sql);

    // Check if there are any results
    if ($result->num_rows > 0) {
        // Output the data in a table
        echo "<table border='1'><tr><th>Employee ID</th><th>Employee Name</th><th>Branch Address</th><th>Bank Name</th><th>Bank Account Holder Name</th><th>Bank Account Number</th><th>IFSC Code</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row["Employee ID"] . "</td><td>" . $row["Employee Name"] . "</td><td>" . $row["Branch address"] . "</td><td>" . $row["Bank Name"] . "</td><td>" . $row["Bank Account Name"] . "</td><td>" . $row["Bank Account Number"] . "</td><td>" . $row["IFSC Code"] . "</td></tr>";
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
