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
    <title>Add Bank Details</title>
</head>
<body>
<h2>Add Bank Details</h2>
    <form method="POST" action="bank.php">
        

        <label for="Employee_Name">Employee Name:</label>
        <input type="text" name="Employee_Name" id="Employee_Name" required><br><br>

        <label for="Bank_Account_Holder_Name">Bank Account Holder Name:</label>
        <input type="text" name="Bank_Account_Holder_Name" id="Bank_Account_Holder_Name" required><br><br>

        <label for="Bank_Account_Number">Bank Account Number:</label>
        <input type="text" name="Bank_Account_Number" id="Bank_Account_Number" required><br><br>

        <label for="Bank_Name">Bank Name:</label>
        <input type="text" name="Bank_Name" id="Bank_Name" required><br><br>

        <label for="IFSC_Code">IFSC Code:</label>
        <input type="text" name="IFSC_Code" id="IFSC_Code" required><br><br>

        <label for="Branch_address">Branch Address:</label>
        <input type="text" name="Branch_address" id="Branch_address" required><br><br>

        <input type="reset" name="reset" value="Reset" />
        <input type="submit" name="submit" value="Submit" />
    </form>
    <a href='view_bank.php'>See bank details</a>

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
        
        $Employee_Name = $_POST['Employee_Name'];
        $Bank_Account_Name = $_POST['Bank_Account_Holder_Name'];
        $Bank_Account_Number = $_POST['Bank_Account_Number'];
        $Bank_Name = $_POST['Bank_Name'];
        $IFSC_Code = $_POST['IFSC_Code'];
        $Branch_address = $_POST['Branch_address'];

        // Basic validation
        if ( empty($Employee_Name) || empty($Bank_Account_Name) || empty($Bank_Account_Number) || empty($Bank_Name) || empty($IFSC_Code) || empty($Branch_address)) {
            echo "All fields are required.<br>";
        } else {
            // Prepare SQL query with prepared statements to prevent SQL injection
            $stmt = $conn->prepare("INSERT INTO bank ( `Employee Name`, `Bank Account Holder Name`, `Bank Account Number`, `Bank Name`, `IFSC Code`, `Branch address`) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $Employee_Name,  $Bank_Account_Name, $Bank_Account_Number, $Bank_Name, $IFSC_Code, $Branch_address);

            // Execute the query and check if it was successful
            if ($stmt->execute()) {
                echo "New bank details added successfully .<a href='updated_bank.php'>updated_bank.php</a>";
            } else {
                echo "Error: " . $stmt->error . "<br>";
            }

            // Redirect after successful insert
            header("Location: view_bank.php");
             // Ensure no further code is executed
            // Close the prepared statement
            $stmt->close();
        }
    }

    // Close connection
    $conn->close();
    ?>
</body>
</html>
