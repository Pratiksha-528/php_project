<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Bank Details Filter</title>    
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
<?php
session_start();  // Start the session to check login status

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    echo "You need to <a href='reset_login.php'>login</a> first.";
    exit();
}

echo "Welcome, " . $_SESSION['username'] . "!<br><br>";
?>

<h2>Apply Filters</h2>
<form action="filters_bank.php" method="POST">
    <label for="Bank Name">Bank Name</label>
    <select name="Bank Name" id="Bank Name">
        <option value="ICICI">ICICI</option>
        <option value="HDFC Bank">HDFC Bank</option>
        <option value="Axis Bank">Axis Bank</option>
        <option value="Yes Bank">Yes Bank</option>
    </select>
    <br><br>

    <label for="Branch Address">Branch Address</label>
    <select name="Branch Address" id="Branch Address">
        <option value="Mumbai">Mumbai</option>
        <option value="Gujarat">Gujarat</option>
        <option value="Delhi">Delhi</option>
        <option value="Bhopal">Bhopal</option>
    </select>
    <br><br>

    <input type="submit" value="Apply Filter"><br><br><br>
    
    <a href="Home.php" >Home</a>
    <a href="reset_login.php" >Logout</a>
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
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the selected values from the form
        $selected_Bank_Name = $_POST['Bank Name'];
        $selected_Branch_Address = $_POST['Branch Address'];
        
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
                   
                        
                        while ($row = $result->fetch_assoc()) {
                            $employeeId=htmlspecialchars($row["Employee ID"]);
                            $Employee_Name = htmlspecialchars($row["Employee Name"]);
                            $Bank_Account_Name=htmlspecialchars($row["Bank Account Holder Name"]);
                            $Bank_Account_Number=htmlspecialchars($row["Bank Account Number"]);
                            $Bank_Name=htmlspecialchars($row["Bank Name"]);
                            $IFSC_Code=htmlspecialchars($row["IFSC Code"]);
                            $Branch_address=htmlspecialchars($row["Branch address"]);// Correct variable
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
