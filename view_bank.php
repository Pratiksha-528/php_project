<?php
session_start();  // Start the session to check login status

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    echo "You need to <a href='reset_login.php'>login</a> first.";
    exit();
}
$role = $_SESSION['role']; 
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
        a {
            background-color: black;
            color: white;
            padding: 10px;
            text-decoration: none;
            display: inline-block;
        }

        a:hover {
            color: brown;
        }

        .filter_container input {
            width: 100px;
            height: 30PX;
            padding: 5px;
            margin-right: 10px;
        }

        .edit input {
            background-color: black;
            color: white;
            padding: 10px;
            text-decoration: none;
            display: inline-block;
        }

        a:hover {
            background-color: gray;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid black;
            padding: 12px 15px;
            text-align: left;
        }

        th {
            color: black;
            background-color: lightslategray;
        }

        td {
            background-color: lightgray;
        }

        .filter_container {
            display: block;
            justify-content: center;
            margin-bottom: 20px;
            margin-top: 20px;
        }

        .sidebar {
            width: 200px;
            background-color: #333;
            color: white;
            margin-right: 10px;
        }

        .add input {
            height: 40px;
            width: 200px;
            background-color: black;
            color: wheat;
        }
    </style>
</head>

<body>
    <div class="add">
        <input type="button" value="Add Bank details" onclick="window.location.href='bank.php';">
    </div>
    <div class="sidebar">
        <a href="home.php">Home</a>
        <a href="reset_login.php">Logout</a>
    </div>

    <br>
    <?php


// Server connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "employee";  // Make sure this matches your actual database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get selected filter values from the form (if POST request)
$selected_Bank_Name = isset($_POST['bank_name']) ? $_POST['bank_name'] : '';
$selected_Branch_Address = isset($_POST['branch_address']) ? $_POST['branch_address'] : '';

// SQL query based on selected filters
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql = "SELECT `Employee ID`, `Employee Name`, `Bank Account Holder Name`, `Bank account number`, `Bank Name`, `IFSC Code`, `Branch address`
            FROM `bank` 
            WHERE 1=1";

    if ($selected_Bank_Name != '') {
        $sql .= " AND `Bank Name` = '$selected_Bank_Name'";
    }
    if ($selected_Branch_Address != '') {
        $sql .= " AND `Branch address` = '$selected_Branch_Address'";
    }

    $sql .= " ORDER BY `Branch address` DESC";  // Sorting by Branch address

    // Execute the query
    $result = $conn->query($sql);
} else {
    // If the form is not submitted, show all data
    $sql = "SELECT `Employee ID`, `Employee Name`, `Bank Account Holder Name`, `Bank account number`, `Bank Name`, `IFSC Code`, `Branch address` FROM bank";
    $result = $conn->query($sql);
}

// Check if there are any results
if ($result->num_rows > 0) {
    // Output the data in a table
    echo "<table border='1'><tr><th>Employee ID</th>
    <th>Employee Name</th>
    <th>Branch Address</th>
    <th>Bank Name</th>
    <th>Bank Account Holder Name</th>
    <th>Bank Account Number</th>
    <th>IFSC Code</th>";

    // Conditionally show the Actions column if the user is not 'view' role
    if ($role != 'view') {
        echo "<th>Actions</th>";
    }

    echo "</tr>";

    while ($row = $result->fetch_assoc()) {
        $employeeId = $row["Employee ID"];
        echo "<tr>
                <td>" . $row["Employee ID"] . "</td>
                <td>" . $row["Employee Name"] . "</td>
                <td>" . $row["Branch address"] . "</td>
                <td>" . $row["Bank Name"] . "</td>
                <td>" . $row["Bank Account Holder Name"] . "</td>
                <td>" . $row["Bank account number"] . "</td>
                <td>" . $row["IFSC Code"] . "</td>";

        // Conditionally show edit or delete based on user role
        if ($role != 'view') {
            echo "<td>";

            // Display actions based on the role
            if ($role == 'admin') {
                echo "<div class='edit'><a href='edit_bank.php?id=$employeeId'>Update</a></div>";
                echo "<div class='delete'><a href='delete_bank.php?id=$employeeId' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a></div>";
            } elseif ($role == 'edit_user') {
                echo "<div class='edit'><a href='edit_bank.php?id=$employeeId'>Update</a></div>";
            } elseif ($role == 'delete_user') {
                echo "<div class='delete'><a href='delete_bank.php?id=$employeeId' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a></div>";
            }

            echo "</td>";
        }

        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "No data found.";
}

$conn->close();
?>
