<?php
session_start();  // Start the session to check login status

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    echo "You need to <a href='reset_login.php'>login</a> first.";
    exit();
}

echo "Welcome, " . $_SESSION['username'] . "!<br><br>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Add Last Employment Details</title>
</head>
<body>
<h2>Add Last Employment Details</h2>
    <form method="POST" action="last.php">
        <label for="Employer_Name">Employer Name:</label>
        <input type="text" name="Employer_Name" id="Employer_Name" required><br><br>

        <label for="Immediate_superior_name">Immediate Superior Name:</label>
        <input type="text" name="Immediate_superior_name" id="Immediate_superior_name" required><br><br>

        <label for="Email_id">Email ID:</label>
        <input type="text" name="Email_id" id="Email_id" required><br><br>

        <label for="Date_of_joining">Date of Joining:</label>
        <input type="date" name="Date_of_joining" id="Date_of_joining" required><br><br>

        <label for="Date_of_termination">Date of Termination:</label>
        <input type="date" name="Date_of_termination" id="Date_of_termination" required><br><br>

       

        <input type="reset" name="reset" value="Reset" />
        <input type="submit" name="submit" value="Submit" />
    </form>
    <a href='view_last.php'>See last employment details </a>
    <?php
    // Server connection details
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "employee";  // Ensure your database is correct

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Sanitize user inputs to prevent SQL Injection
        $Employer_name = $conn->real_escape_string($_POST['Employer_Name']);
        $Immediate_superior_name = $conn->real_escape_string($_POST['Immediate_superior_name']);
        $Email_id = $conn->real_escape_string($_POST['Email_id']);
        $Date_of_joining = $conn->real_escape_string($_POST['Date_of_joining']);
        $Date_of_termination = $conn->real_escape_string($_POST['Date_of_termination']);
    

        // Basic validation
        if (empty($Employer_name) || empty($Immediate_superior_name) || empty($Email_id) || empty($Date_of_joining) || empty($Date_of_termination) ) {
            echo "All fields are required.<br>";
        } else {
            // Prepare SQL query with prepared statements to prevent SQL injection
            $stmt = $conn->prepare("INSERT INTO `last_employment` (`Employer name`, `Immediate_superior_name`, `Email id`, `Date of joining`, `Date of termination`)
                                    VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $Employer_name, $Immediate_superior_name, $Email_id, $Date_of_joining, $Date_of_termination);

            // Execute the query and check if it was successful
            if ($stmt->execute()) {
                echo "New details added successfully. <a href='view_last.php'>See last employment details (updated database)</a><br>";
            } else {
                echo "Error: " . $stmt->error . "<br>";
            }
            header("Location:view_last.php");

            // Close the prepared statement
            $stmt->close();
        }
    }

    // Close connection
    $conn->close();
    ?>
</body>
</html>
