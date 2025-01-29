<?php
session_start();  // Start the session to check login status

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    echo "You need to <a href='
    
    
    reset_login.php'>login</a> first.";
    exit();
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Employee Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .sidebar {
            width: 200px;
            background-color: #333;
            color: white;
            padding-top: 20px;
            position: fixed;
        }
        .sidebar a {
            display: block;
            padding: 10px;
            color: white;
            text-decoration: none;
            margin: 5px 0;
        }
        .sidebar a:hover {
            background-color: #555;
        }
        .add input {
            background-color: #333;
            color: wheat;
            font-size: 16px;
            padding: 12px 25px;
            cursor: pointer;
            border: none;
            margin-bottom: 20px;
        }
        .add input:hover {
            background-color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1);
        }
        th, td {
            border: 1px solid black;
            padding: 12px 15px;
            text-align: left;
        }
        th {
            background-color: lightslategray;
            color: black;
        }
        td {
            background-color: lightgray;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: static;
            }
            .main-content {
                margin-left: 0;
            }
            .add input {
                width: 100%;
                text-align: center;
            }
            table, th, td {
                width: 100%;
                display: block;
                text-align: center;
            }
            th, td {
                border: 1px solid #ddd;
                padding: 10px;
                font-size: 14px;
            }
            .sidebar a {
                text-align: center;
            }
        }
    </style>
</head>
<body>
<div class="add">
    <input type="button" value="Add Employee details" onclick="window.location.href='empdata.php';">
</div>
<div class="sidebar">
    <a href="home.php">Home</a>
    <a href="reset_login.php">Logout</a>
</div>
<br><br>

<div>


<?php

// Database connection details
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

// Get selected filter values
$selectedState = isset($_POST['State']) ? $_POST['State'] : '';
$selectedDateJoinedStart = isset($_POST['date_joined_start']) ? $_POST['date_joined_start'] : '';
$selectedDateJoinedEnd = isset($_POST['date_joined_end']) ? $_POST['date_joined_end'] : '';
$selectedProbationExtended = isset($_POST['probation_extended']) ? $_POST['probation_extended'] : '';
$selectedNatureOfEmployment = isset($_POST['nature_of_employment']) ? $_POST['nature_of_employment'] : '';
$selectedJoiningCTCMin = isset($_POST['joining_ctc_min']) ? $_POST['joining_ctc_min'] : '';
$selectedJoiningCTCMax = isset($_POST['joining_ctc_max']) ? $_POST['joining_ctc_max'] : '';
$selectedPeriodFromEmploymentStart = isset($_POST['period_from_employment_start']) ? $_POST['period_from_employment_start'] : '';
$selectedPeriodFromEmploymentEnd = isset($_POST['period_from_employment_end']) ? $_POST['period_from_employment_end'] : '';


$sql = "SELECT DISTINCT State FROM empdata1 ORDER BY State DESC";
$result = $conn->query($sql);

// Display the filter form
?>
<form action="view_empdatafilters.php" method="POST">
    <label for="State">State:</label>
    <select name="State" id="State">
        <option value="">Select State</option>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $selected = ($row["State"] == $selectedState) ? 'selected' : '';
                echo "<option value='" . $row["State"] . "' $selected>" . $row["State"] . "</option>";
            }
        } else {
            echo "<option value=''>No data available</option>";
        }
        ?>
    </select>
    <br><br>


    <!-- New Filters -->
    <label for="date_joined_start">Date of Joining (Start):</label>
    <input type="date" name="date_joined_start" id="date_joined_start" value="<?= isset($_POST['date_joined_start']) ? $_POST['date_joined_start'] : '' ?>"><br><br>

    <label for="date_joined_end">Date of Joining (End):</label>
    <input type="date" name="date_joined_end" id="date_joined_end" value="<?= isset($_POST['date_joined_end']) ? $_POST['date_joined_end'] : '' ?>"><br><br>

    <label for="probation_extended">Probation Extended:</label>
    <select name="probation_extended" id="probation_extended">
        <option value="">Select</option>
        <option value="Y" <?= isset($_POST['probation_extended']) && $_POST['probation_extended'] == 'Y' ? 'selected' : '' ?>>Yes</option>
        <option value="N" <?= isset($_POST['probation_extended']) && $_POST['probation_extended'] == 'N' ? 'selected' : '' ?>>No</option>
    </select><br><br>

    <label for="nature_of_employment">Nature of Employment:</label>
    <select name="nature_of_employment" id="nature_of_employment">
        <option value="">Select</option>
        <option value="Intern" <?= isset($_POST['nature_of_employment']) && $_POST['nature_of_employment'] == 'Full-Time' ? 'selected' : '' ?>>Intern</option>
        <option value="Job" <?= isset($_POST['nature_of_employment']) && $_POST['nature_of_employment'] == 'Full-Time' ? 'selected' : '' ?>>Full-Time</option>
       
        <option value="Contract" <?= isset($_POST['nature_of_employment']) && $_POST['nature_of_employment'] == 'Contract' ? 'selected' : '' ?>>Contract</option>
    </select><br><br>

    <label for="joining_ctc_min">Joining CTC (Min):</label>
    <input type="number" name="joining_ctc_min" id="joining_ctc_min" value="<?= isset($_POST['joining_ctc_min']) ? $_POST['joining_ctc_min'] : '' ?>"><br><br>

    <label for="joining_ctc_max">Joining CTC (Max):</label>
    <input type="number" name="joining_ctc_max" id="joining_ctc_max" value="<?= isset($_POST['joining_ctc_max']) ? $_POST['joining_ctc_max'] : '' ?>"><br><br>

    <label for="period_from_employment_start">Period from Employment Date (Start):</label>
    <input type="number" name="period_from_employment_start" id="period_from_employment_start" value="<?= isset($_POST['period_from_employment_start']) ? $_POST['period_from_employment_start'] : '' ?>"><br><br>

    <label for="period_from_employment_end">Period from Employment Date (End):</label>
    <input type="number" name="period_from_employment_end" id="period_from_employment_end" value="<?= isset($_POST['period_from_employment_end']) ? $_POST['period_from_employment_end'] : '' ?>"><br><br>

    <div class="filter_container">
        <input type="submit" value="Apply Filter">
        <input type="button" value="Reset" onclick="window.location.href='view_empdatafilters.php';">
    </div>
</form>
<br><br>
<?php 
// Prepare the SQL query based on the filter values
$sql = "SELECT * FROM empdata1 WHERE 1=1 ";

if ($selectedState != '') {
    $sql .= "AND State= ? ";
}
if ($selectedDateJoinedStart != '') {
    $sql .= "AND Date of joining >= ? ";
}
if ($selectedDateJoinedEnd != '') {
    $sql .= "AND Date of joining <= ? ";
}
if ($selectedProbationExtended != '') {
    $sql .= "AND Probation Extended Y/N = ? ";
}
if ($selectedNatureOfEmployment != '') {
    $sql .= "AND Nature of Employment = ? ";
}
if ($selectedJoiningCTCMin != '') {
    $sql .= "AND Joining CTC >= ? ";
}
if ($selectedJoiningCTCMax != '') {
    $sql .= "AND Joining CTC <= ? ";
}
if ($selectedPeriodFromEmploymentStart != '') {
    $sql .= "AND Period from Employment date >= ? ";
}
if ($selectedPeriodFromEmploymentEnd != '') {
    $sql .= "AND Period from Employment date <= ? ";
}

$stmt = $conn->prepare($sql);

// Bind parameters dynamically
$types = "";
$params = [];
if ($selectedState != '') {
    $types .= "s";
    $params[] = &$selectedState;
}
if ($selectedDateJoinedStart != '') {
    $types .= "s";
    $params[] = &$selectedDateJoinedStart;
}
if ($selectedDateJoinedEnd != '') {
    $types .= "s";
    $params[] = &$selectedDateJoinedEnd;
}
if ($selectedProbationExtended != '') {
    $types .= "s";
    $params[] = &$selectedProbationExtended;
}
if ($selectedNatureOfEmployment != '') {
    $types .= "s";
    $params[] = &$selectedNatureOfEmployment;
}
if ($selectedJoiningCTCMin != '') {
    $types .= "i";
    $params[] = &$selectedJoiningCTCMin;
}
if ($selectedJoiningCTCMax != '') {
    $types .= "i";
    $params[] = &$selectedJoiningCTCMax;
}
if ($selectedPeriodFromEmploymentStart != '') {
    $types .= "i";
    $params[] = &$selectedPeriodFromEmploymentStart;
}
if ($selectedPeriodFromEmploymentEnd != '') {
    $types .= "i";
    $params[] = &$selectedPeriodFromEmploymentEnd;
}

if ($types) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
  // Get the result from the query

if ($result->num_rows > 0) {
    // Get column names dynamically
    $columns = $result->fetch_fields();
    
    // Start the table
    echo "<table border='1'>
            <tr>";
    
    // Create table headers dynamically
    foreach ($columns as $column) {
        echo "<th>" . htmlspecialchars($column->name) . "</th>"; // Column names as table headers
    }
    echo "<th>Actions</th></tr>";  // Add actions column
    
    // Loop through all rows and display the data
    while ($row = $result->fetch_assoc()) {
        $employeeId = $row["Employee ID"];  // Store Employee ID for use in URLs
        echo "<tr>";
        
        // Loop through all columns and display the data dynamically
        foreach ($columns as $column) {
            $columnName = $column->name;
            echo "<td>" . htmlspecialchars($row[$columnName]) . "</td>";
        }
        
        // Actions column with Update and Delete options
        echo "<td>
                <a href='edit_empdata.php?id=$employeeId'>Update</a> | 
                <a href='delete_empdata.php?id=$employeeId' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a>
              </td>";
        
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No results found.";
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>

</body>
</html>