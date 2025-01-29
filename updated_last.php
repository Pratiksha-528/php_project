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
<form action="filters_last.php" method="POST">
    <label for="Immediate_superior_name">Immediate Superior Name</label>
    <select name="Immediate_superior_name" id="Immediate_superior_name">
        <option value="tcs">TCS</option>
        <option value="infosys">Infosys</option>
        <option value="tata">Tata</option>
        <option value="abc">ABC</option>
    </select>
    <br><br>

    <!-- Uncomment and fix the "Date of joining" input if necessary -->
    <!-- <label for="Date_of_joining">Date of Joining</label>
    <input type="date" name="Date_of_joining" id="Date_of_joining">
    <br><br> -->

    <input type="submit" value="Apply Filter"><br><br>
    <a href="reset_login.php">Logout</a>
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
    $selected_Immediate_superior_name = $_POST['Immediate_superior_name'];
    
    // Prepare SQL query using prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT `Employee ID`, `Employer name`, `Immediate_superior_name`, `Email id`, `Date of joining`, `Date of termination` 
                            FROM `last employment`
                            WHERE `Immediate_superior_name` = ? 
                            ORDER BY `Date of joining` DESC");
    $stmt->bind_param("s", $selected_Immediate_superior_name); // "s" for string

    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are results
    if ($result->num_rows > 0) {
        // Start the table and define column headers
        echo "<table border='1'>
                <tr>
                    <th>Employee ID</th>
                    <th>Employer Name</th>    
                    <th>Immediate Superior Name</th>
                    <th>Email ID</th>
                    <th>Date of Joining</th>
                    <th>Date of Termination</th>
                    <th>Actions</th>
                </tr>";
        
        // Loop through the result and display each employee's details
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                     <td>" . htmlspecialchars($row["Employee ID"]) . "</td>
                     <td>" . htmlspecialchars($row["Employer name"]) . "</td>
                     <td>" . htmlspecialchars($row["Immediate_superior_name"]) . "</td>
                     <td>" . htmlspecialchars($row["Email id"]) . "</td>
                     <td>" . htmlspecialchars($row["Date of joining"]) . "</td>
                     <td>" . htmlspecialchars($row["Date of termination"]) . "</td>
                     <td>
                        <div class='edit'><a href='edit_last.php?id=" . htmlspecialchars($row['Employee ID']) . "'>Update</a></div>
                        <div class='delete'><a href='delete_last.php?id=" . htmlspecialchars($row['Employee ID']) . "' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a></div>
                    </td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "No results found for the selected search terms.";
    }

    // Close the prepared statement
    $stmt->close();
} else {
    // If the form is not submitted, show all data
    $sql = "SELECT `Employee ID`, `Employer name`, `Immediate_superior_name`, 
            `Email id`, `Date of joining`, `Date of termination`
            FROM `last employment`";

    $result = $conn->query($sql);

    // Check if there are any results
    if ($result->num_rows > 0) {
        // Start the table and define column headers
        echo "<table border='1'>
                <tr>
                    <th>Employee ID</th>
                    <th>Employer Name</th>    
                    <th>Immediate Superior Name</th>
                    <th>Email ID</th>
                    <th>Date of Joining</th>
                    <th>Date of Termination</th>
                    <th>Actions</th>
                </tr>";
        
        // Loop through the result and display each employee's details
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                     <td>" . htmlspecialchars($row["Employee ID"]) . "</td>
                     <td>" . htmlspecialchars($row["Employer name"]) . "</td>
                     <td>" . htmlspecialchars($row["Immediate_superior_name"]) . "</td>
                     <td>" . htmlspecialchars($row["Email id"]) . "</td>
                     <td>" . htmlspecialchars($row["Date of joining"]) . "</td>
                     <td>" . htmlspecialchars($row["Date of termination"]) . "</td>
                     <td>
                        <div class='edit'><a href='edit_last.php?id=" . htmlspecialchars($row['Employee ID']) . "'>Update</a></div>
                        <div class='delete'><a href='delete_last.php?id=" . htmlspecialchars($row['Employee ID']) . "' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a></div>
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
