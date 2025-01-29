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
    <title>Add Family Details</title>
</head>
<body>
<h2>Add Family Details</h2>
    <form method="POST" action="family.php">
        <label for="Employee_ID">Employee ID:</label>
        <input type="text" name="Employee_ID" id="Employee_ID" required><br><br>

        <label for="Family_member_name">Family member name:</label>
        <input type="text" name="Family_member_name" id="Family_member_name" required><br><br>

        <label for="Family_relation">Family relation:</label>
        <input type="text" name="Family_relation" id="Family_relation" required><br><br>

        <label for="mobile_number">Mobile number:</label>
        <input type="text" name="mobile_number" id="mobile_number" required><br><br>

        <input type="reset" name="reset" value="Reset" />
        <input type="submit" name="submit" value="Submit" />
    </form>
    <a href='view_family.php'>See family details</a>

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
        $Employee_ID = $conn->real_escape_string($_POST['Employee_ID']);
        $Family_member_name = $conn->real_escape_string($_POST['Family_member_name']);
        $Family_relation = $conn->real_escape_string($_POST['Family_relation']);
        $mobile_number = $conn->real_escape_string($_POST['mobile_number']);

        // Basic validation
        if (empty($Employee_ID) || empty($Family_member_name) || empty($Family_relation) || empty($mobile_number)) {    
            echo "All fields are required.<br>";
        
        } else {
            // Prepare SQL query with prepared statements to prevent SQL injection
            $stmt = $conn->prepare("INSERT INTO family (`Employee ID`, `Family  member name`, `Family relation`, `mobile number`)
                                    VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $Employee_ID, $Family_member_name, $Family_relation, $mobile_number);

            // Execute the query and check if it was successful
            if ($stmt->execute()) {
                // Redirect after successful insertion (no output before header call)
                header("Location: view_family.php");
                exit();
            } else {
                echo "Error: " . $stmt->error . "<br>";
            }

            // Close the prepared statement
            $stmt->close();
        }
    }

    // Close connection
    $conn->close();
    ?>
</body>
</html>
