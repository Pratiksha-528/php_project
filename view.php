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
            margin-top: 20px;
        }

        .add input {
            background-color: black;
            color: white;
            height: 30px;
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
            display: block;
            padding: 4px 12px;
        }

        a:hover {
            color: red;
        }

        .filter_container {
            display: block;
            justify-content: center;
        }

        .filter_container input {
            color: black;
            height: 30px;
            margin: 6px;
        }

        .sidebar {
            width: 200px;
            background-color: #333;
            color: white;
            display: block;
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

<div class="sidebar">
    <a href="home.php">Home</a>
    <a href="reset_login.php">Logout</a>
</div>

<div class="add">
    <input type="button" value="Add Last Employment Details" onclick="window.location.href='last.php';">
</div>

<h2>Apply Filters</h2>
<form action="view_last.php" method="POST">
    <label for="Employee_ID">Employee ID</label>
    <select name="Employee_ID" id="Employee_ID">
        <option value="">Select Employee ID</option>
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

        // Get selected filter values
        $selected_Employee_ID = isset($_POST['Employee_ID']) ? $_POST['Employee_ID'] : '';
        $selected_Employer_name = isset($_POST['Employer_name']) ? $_POST['Employer_name'] : '';

        // Fetch Employee IDs
        $stmt = $conn->prepare("SELECT DISTINCT `Employee ID` FROM `last employment` ORDER BY `Employee ID`");
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $selected = ($row["Employee ID"] == $selected_Employee_ID) ? 'selected' : '';
                echo "<option value='" . $row["Employee ID"] . "' $selected>" . $row["Employee ID"] . "</option>";
            }
        } else {
            echo "<option value=''>No data available</option>";
        }
        ?>
    </select>
    <br><br>

    <label for="Employer_name">Employer Name</label>
    <select name="Employer_name" id="Employer_name">
        <option value="">Select Employer Name</option>
        <?php
        // Fetch Employer Names
        $stmt = $conn->prepare("SELECT DISTINCT `Employer name` FROM `last employment` ORDER BY `Employer name`");
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $selected = ($row["Employer name"] == $selected_Employer_name) ? 'selected' : '';
                echo "<option value='" . $row["Employer name"] . "' $selected>" . $row["Employer name"] . "</option>";
            }
        } else {
            echo "<option value=''>No data available</option>";
        }
        ?>
    </select>
    <br><br>

    <div class="filter_container">
        <input type="submit" value="Apply Filter">
        <input type="button" value="Reset" onclick="window.location.href='view_last.php';">
    </div>
</form>

<?php
// Fetch and display filtered data
$sql = "SELECT * FROM `last employment` WHERE 1=1";

if ($selected_Employee_ID != '') {
    $sql .= " AND `Employee ID` = ?";
}

if ($selected_Employer_name != '') {
    $sql .= " AND `Employer name` = ?";
}

// Prepare the statement
$stmt = $conn->prepare($sql);

if ($selected_Employee_ID != '' && $selected_Employer_name != '') {
    $stmt->bind_param("ss", $selected_Employee_ID, $selected_Employer_name);
} elseif ($selected_Employee_ID != '') {
    $stmt->bind_param("s", $selected_Employee_ID);
} elseif ($selected_Employer_name != '') {
    $stmt->bind_param("s", $selected_Employer_name);
}

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

echo "<h3>Total records: " . $result->num_rows . "</h3>";

if ($result->num_rows > 0) {
    echo "<table>
            <tr>
                <th>Employee ID</th>
                <th>Sr_no</th>
                <th>Employer Name</th>
                <th>Immediate Superior Name</th>
                <th>Email ID</th>
                <th>Date of Joining</th>
                <th>Date of Termination</th>
                <th>Actions</th>
            </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["Employee ID"] . "</td>
                <td>" . $row["Sr_no"] . "</td>
                <td>" . $row["Employer name"] . "</td>
                <td>" . $row["Immediate_superior_name"] . "</td>
                <td>" . $row["Email id"] . "</td>
                <td>" . $row["Date of joining"] . "</td>
                <td>" . $row["Date of termination"] . "</td>
                <td>
                    <a href='edit_last.php?id=" . htmlspecialchars($row['Employee ID']) . "'>Update</a>
                    <a href='delete_last.php?id=" . htmlspecialchars($row['Employee ID']) . "' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a>
                </td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "<p>No records found.</p>";
}

$conn->close();
?>
</body>
</html>

