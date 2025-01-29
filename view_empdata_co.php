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
    <title>Employee Details</title>
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

    <a href="empdata.php">Add employee details</a><br><br>
    <a href="home.php">Home</a>
    <a href="reset_login.php">Logout</a>
    <br><br>

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

    // SQL query to fetch employee details
    $sql = "SELECT `Employee ID`, `Employee Name`, `Present Address`, `Present Pincode`, 
    `Permanent Address`, `Permanent Area`, `Permanent Pincode`, `State`
    FROM empdata1";

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // Start the table and define column headers
        echo "<table>
                <tr>
                    <th>Employee ID</th>
                    <th>Employee Name</th>
                    <th>Present Address</th>
                    <th>Present Pincode</th>
                    <th>Permanent Address</th>
                    <th>Permanent Area</th>
                    <th>Permanent Pincode</th>
                    <th>State</th>
                    <th>Operations</th>
                </tr>";
        
        // Loop through the result and display each employee's details
        while ($row = $result->fetch_assoc()) {
            $employeeName = htmlspecialchars($row["Employee Name"]);
            $presentAddress = htmlspecialchars($row["Present Address"]);
            $presentPincode = htmlspecialchars($row["Present Pincode"]);
            $permanentAddress = htmlspecialchars($row["Permanent Address"]);
            $permanentArea = htmlspecialchars($row["Permanent Area"]);
            $permanentPincode = htmlspecialchars($row["Permanent Pincode"]);
            $state = htmlspecialchars($row["State"]);
            $employeeId = htmlspecialchars($row["Employee ID"]);
            
            echo "<tr>
                <td>$employeeId</td>
                <td>$employeeName</td>
                <td>$presentAddress</td>
                <td>$presentPincode</td>
                <td>$permanentAddress</td>
                <td>$permanentArea</td>
                <td>$permanentPincode</td>
                <td>$state</td>
                <td>
                    <a href='edit_empdata.php?id=$employeeId'>Update</a> | 
                    <a href='delete_empdata.php?id=$employeeId' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a>
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
