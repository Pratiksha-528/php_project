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
    <title>Employee Bank Details</title>
    <style type="text/css">
    /* Styling for the 'Add bank details' */
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        a {
            text-decoration: none;
            color: white;
            background-color: black;
            display: inline-block;
            padding: 4px 12px;
        }
        a:hover {
            color: red;
        }
    </style>
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
    <a href="bank.php">Add Bank details</a>
    <br>
    <Br>
    <a href ="home.php">Home</a>
    
    <a href="reset_login.php" >Logout</a>
    <br>
    <br>
    <?php
    // Server connection details
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "employee";  // Make sure your database is correct

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to fetch employee details
    $sql = "SELECT `Employee ID`, `Employee Name`, `Bank Account Holder Name`, 
    `Bank Account Number`, `Bank Name`, `IFSC Code`, `Branch address`
FROM bank";



    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // Start the table and define column headers
        echo "<table border='1'>
                <tr>
                    <th>Employee ID</th>
                    <th>Employee Name</th>
                    <th>Bank Account Holder Name</th>
                    <th>Bank Account Number</th>
                    <th>Bank Name</th>
                    <th>IFSC Code</th>
                    <th>Branch address</th>
                    <th>Operation</th>
                </tr>";
        
        // Loop through the result and display each employee's details
        while ($row = $result->fetch_assoc()) {
            $employeeId=htmlspecialchars($row["Employee ID"]);
            $Employee_Name = htmlspecialchars($row["Employee Name"]);
            $Bank_Account_Name=htmlspecialchars($row["Bank Account Holder Name"]);
            $Bank_Account_Number=htmlspecialchars($row["Bank Account Number"]);
            $Bank_Name=htmlspecialchars($row["Bank Name"]);
            $IFSC_Code=htmlspecialchars($row["IFSC Code"]);
            $Branch_address=htmlspecialchars($row["Branch address"]);
            
             echo "<tr>
             <td>$employeeId</td>
                    <td>$Employee_Name</td>
                    <td>$Bank_Account_Name</td>
                    <td>$Bank_Account_Number</td>
                    <td>$Bank_Name</td>
                    <td>$IFSC_Code</td>
                    <td>$Branch_address</td>
                    <td>
                    <div class=edit>  <a href='edit_bank.php?id=$employeeId'>Update</a> </div>
                        <div class=delete><a href='delete_bank.php?id=$employeeId' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a></div>
                    </td>
                    </tr>";
        }
        
        // End the table
        echo "</table>";
    } else {
        echo "No data found.";
    }

    // Close the database connection
    $conn->close();
    ?>

</body>
</html>
