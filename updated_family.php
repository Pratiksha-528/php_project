<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Family Details Filter</title>  
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
            padding: 4px 8px;
        }
        a:hover {
            color: red;
        }
        </style   
</head>
<body>
<?php
session_start();  // Start the session to check login status

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    echo "You need to <a href='reset_login.php'>login</a> first.";
    exit();
}
echo "Welcome, " . $_SESSION['username'] . "!<br>";
?>

<h3>Apply Filters</h3>
<form action="filters_family.php" method="POST">
    <label for="Employee_ID">Employee ID</label>
    <input type="text" id="Employee_ID" name="Employee_ID" > 
    <br><br>
    <input type="submit" value="Apply Filter"><br><br>
    <div class="home"> <a href="Home.php" >Home</a>
   
    <a href="reset_login.php" >Logout</a></div>
</form>

<?php
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "employee";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Employee_ID'])) {
        // Sanitize and get the selected Employee_ID from POST request
        $selected_Employee_ID = $conn->real_escape_string($_POST['Employee_ID']);

        // SQL query to select data based on user input
        $sql = "SELECT `Employee ID`, `Sr_no`, `Family  member name`, `Family relation`, `mobile number`
                FROM family
                WHERE `Employee ID` = '$selected_Employee_ID'
                ORDER BY `Employee ID` DESC";  // Sorting by Employee ID in descending order

        // Execute the query
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
                        <th>Actions</th>
                    </tr>";
            
            // Loop through the result and display each employee's details
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>". htmlspecialchars($row["Employee ID"]) . "</td>
                        <td>" . htmlspecialchars($row["Sr_no"]) . "</td>
                        <td>" . htmlspecialchars($row["Family  member name"]) . "</td>
                        <td>" . htmlspecialchars($row["Family relation"]) . "</td>
                        <td>" . htmlspecialchars($row["mobile number"]) . "</td>
                         <td>
                            <a href='edit_family.php?id=$'Employee ID'>Update</a> | 
                            <a href='delete_family.php?id=$'Employee ID' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a>
                        </td>
                        </tr>";
            }
            echo "</table>";
        } else {
            echo "No results found for the selected Employee ID.";
        }
    } else {
        // If the form is not submitted, show all data
        $sql = "SELECT `Employee ID`, `Sr_no`, `Family  member name`, `Family relation`, `mobile number`
                FROM family";

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
                        <TH>Actions</th>
                    </tr>";

            // Loop through the result and display each employee's details
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>". htmlspecialchars($row["Employee ID"]) . "</td>
                        <td>" . htmlspecialchars($row["Sr_no"]) . "</td>
                        <td>" . htmlspecialchars($row["Family  member name"]) . "</td>
                        <td>" . htmlspecialchars($row["Family relation"]) . "</td>
                        <td>" . htmlspecialchars($row["mobile number"]) . "</td>
                        <td>
                            <a href='edit_family.php?id=$'Employee ID'>Update</a> | 
                            <a href='delete_family.php?id=$'Employee ID' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a>
                        </td>
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

</body>
</html>
