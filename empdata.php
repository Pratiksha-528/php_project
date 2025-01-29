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
    <title>Add Employee Details</title>
</head>
<body>
<h2>Add Employee Details</h2>
    <form method="POST" action="empdata.php">
        <label for="Employee_Name">Employee Name:</label>
        <input type="text" name="Employee_Name" id="Employee_Name" required><br><br>

        <label for="Present_Address">Present Address:</label>
        <input type="text" name="Present_Address" id="Present_Address" required><br><br>

        <label for="Present_Pincode">Present Pincode:</label>
        <input type="text" name="Present_Pincode" id="Present_Pincode" required pattern="\d{6}" title="Pincode should be a 6 digit number"><br><br>

        <label for="Permanent_Address">Permanent Address:</label>
        <input type="text" name="Permanent_Address" id="Permanent_Address" required><br><br>

        <label for="Permanent_Area">Permanent Area:</label>
        <input type="text" name="Permanent_Area" id="Permanent_Area" required><br><br>

        <label for="Permanent_Pincode">Permanent Pincode:</label>
        <input type="text" name="Permanent_Pincode" id="Permanent_Pincode" required pattern="\d{6}" title="Pincode should be a 6 digit number"><br><br>

        <label for="State">State:</label>
        <input type="text" name="State" id="State" required><br><br>

        <input type="reset" name="reset" value="Reset" />
        <input type="submit" name="submit" value="Submit" />
    </form>
    <a href='emp
    
        
    
    
    
    
    
    .php'>See education details</a>

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
        $Employee_Name = $conn->real_escape_string($_POST['Employee_Name']);
        $Present_Address = $conn->real_escape_string($_POST['Present_Address']);
        $Present_Pincode = $conn->real_escape_string($_POST['Present_Pincode']);
        $Permanent_Address = $conn->real_escape_string($_POST['Permanent_Address']);
        $Permanent_Area = $conn->real_escape_string($_POST['Permanent_Area']);
        $Permanent_Pincode = $conn->real_escape_string($_POST['Permanent_Pincode']);
        $State = $conn->real_escape_string($_POST['State']);

        // Basic validation
        if (empty($Employee_Name) || empty($Present_Address) || empty($Present_Pincode) || empty($Permanent_Address) || empty($Permanent_Area) || empty($Permanent_Pincode) || empty($State)) {
            echo "All fields are required.<br>";
        } elseif (!is_numeric($Present_Pincode) || !is_numeric($Permanent_Pincode)) {
            echo "Pincode must be a numeric value.<br>";
        } elseif (strlen($Present_Pincode) != 6 || strlen($Permanent_Pincode) != 6) {
            echo "Pincode must be exactly 6 digits.<br>";
        } else {
            // Prepare SQL query with prepared statements to prevent SQL injection
            $stmt = $conn->prepare("INSERT INTO empdata1 (`Employee Name`, `Present Address`, `Present Pincode`, `Permanent Address`, `Permanent Area`, `Permanent Pincode`, `State`) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $Employee_Name, $Present_Address, $Present_Pincode, $Permanent_Address, $Permanent_Area, $Permanent_Pincode, $State);

            // Execute the query and check if it was successful
            if ($stmt->execute()) {
                echo "New employee added successfully.<br>";
                // Redirect after successful insertion to avoid resubmission on refresh
                header("Location: view_empdata.php");
                exit();  // Always call exit() after header redirection
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
