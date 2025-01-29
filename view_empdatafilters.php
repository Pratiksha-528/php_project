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
        /* General body styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* Sidebar styling */
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

        /* Form and filter container styling */
        form {
            margin-bottom: 20px;
        }

        .filter_container {
            margin-bottom: 20px;
        }

        input[type="submit"], input[type="button"] {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            font-size: 14px;
            cursor: pointer;
            border: none;
            margin-right: 10px;
        }

        input[type="submit"]:hover, input[type="button"]:hover {
            background-color: #555;
        }

        /* Table styling */
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
            color: black;
            background-color: lightslategray;
        }

        td {
            background-color: lightgray;
        }

        a {
            background-color: #333;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 3px;
            display: inline-block;
            margin-right: 10px;
        }

        a:hover {
            background-color: #555;
        }

        /* Responsive Design */
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

<br>

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

// Get selected filter values
$selectedState = isset($_POST['State']) ? $_POST['State'] : '';
$selecteddate_joined_start = isset($_POST['date_joined_start']) ? $_POST['date_joined_start'] : '';
$selecteddate_joined_end = isset($_POST['date_joined_end']) ? $_POST['date_joined_end'] : '';
$selectedprobation_extended = isset($_POST['probation_extended']) ? $_POST['probation_extended'] : '';
$selectednature_of_employment = isset($_POST['nature_of_employment']) ? $_POST['nature_of_employment'] : '';
$selectedjoining_ctc_min = isset($_POST['joining_ctc_min']) ? $_POST['joining_ctc_min'] : '';
$selectedjoining_ctc_max = isset($_POST['joining_ctc_max']) ? $_POST['joining_ctc_max'] : '';
$selectedperiod_from_employment_start = isset($_POST['period_from_employment_start']) ? $_POST['period_from_employment_start'] : '';
$selectedperiod_from_employment_end = isset($_POST['period_from_employment_end']) ? $_POST['period_from_employment_end'] : '';

// Get available states for the dropdown
$sql = "SELECT DISTINCT `State` FROM `empdata1` ORDER BY `State` DESC";
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
    </select><br><br>

    <label for="probation_extended">Probation Extended:</label>
    <select name="probation_extended" id="probation_extended">
        <option value="">Select</option>
        <option value="Y" <?= isset($_POST['probation_extended']) && $_POST['probation_extended'] == 'Y' ? 'selected' : '' ?>>Yes</option>
        <option value="N" <?= isset($_POST['probation_extended']) && $_POST['probation_extended'] == 'N' ? 'selected' : '' ?>>No</option>
    </select><br><br>

    <label for="date_joined_start">Date of Joining (Start):</label>
    <input type="date" name="date_joined_start" id="date_joined_start" value="<?= $selecteddate_joined_start ?>"><br><br>

    <label for="date_joined_end">Date of Joining (End):</label>
    <input type="date" name="date_joined_end" id="date_joined_end" value="<?= $selecteddate_joined_end ?>"><br><br>
    

    <label for="nature_of_employment">Nature of Employment:</label>
    <select name="nature_of_employment" id="nature_of_employment">
        <option value="">Select</option>
        <option value="Intern" <?= $selectednature_of_employment == 'Intern' ? 'selected' : '' ?>>Intern</option>
        <option value="Job" <?= $selectednature_of_employment == 'Job' ? 'selected' : '' ?>>Job</option>
    </select><br><br>

    <label for="joining_ctc_min">Joining CTC (Min):</label>
    <input type="number" name="joining_ctc_min" id="joining_ctc_min" value="<?= $selectedjoining_ctc_min ?>"><br><br>

    <label for="joining_ctc_max">Joining CTC (Max):</label>
    <input type="number" name="joining_ctc_max" id="joining_ctc_max" value="<?= $selectedjoining_ctc_max ?>"><br><br>

    <label for="period_from_employment_start">Period from Employment Date (days) (Start):</label>
    <input type="number" name="period_from_employment_start" id="period_from_employment_start" value="<?= $selectedperiod_from_employment_start ?>"><br><br>

    <label for="period_from_employment_end">Period from Employment Date (End):</label>
    <input type="number" name="period_from_employment_end" id="period_from_employment_end" value="<?= $selectedperiod_from_employment_end ?>"><br><br>

    <input type="submit" value="Apply Filter">
    <input type="button" value="Reset" onclick="window.location.href='view_empdatafilters.php';">
</form>

<br><br>

<div class="export"><a href="export_data.php">Download Excel</a><br><br></div>

<?php
// SQL query based on selected state or all records if no state is selected
$sql = "SELECT * FROM empdata1 WHERE 1=1";

// Array to hold the parameters for binding
$params = [];
$types = '';

// Add filter conditions and collect the parameters for binding
if ($selectedState != '') {
    $sql .= " AND `State` = ?";
    $params[] = $selectedState;
    $types .= 's';  // 's' stands for string
}

if ($selecteddate_joined_start != '') {
    $sql .= " AND `Date of joining` >= ?";
    $params[] = $selecteddate_joined_start;
    $types .= 's';
}

if ($selecteddate_joined_end != '') {
    $sql .= " AND `Date of joining` <= ?";
    $params[] = $selecteddate_joined_end;
    $types .= 's';
}

if ($selectedprobation_extended != '') {
    $sql .= " AND `Probation Extended  Y/N` = ?";
    $params[] = $selectedprobation_extended;
    $types .= 's';
}

if ($selectednature_of_employment != '') {
    $sql .= " AND `Nature of Employment` = ?";
    $params[] = $selectednature_of_employment;
    $types .= 's';
}

if ($selectedjoining_ctc_min != '') {
    $sql .= " AND `Joining CTC` >= ?";
    $params[] = $selectedjoining_ctc_min;
    $types .= 'd';  // 'd' stands for decimal
}

if ($selectedjoining_ctc_max != '') {
    $sql .= " AND `Joining CTC` <= ?";
    $params[] = $selectedjoining_ctc_max;
    $types .= 'd';
}

if ($selectedperiod_from_employment_start != '') {
    $sql .= " AND `Period from Employment Start` >= ?";
    $params[] = $selectedperiod_from_employment_start;
    $types .= 'i';  // 'i' stands for integer
}

if ($selectedperiod_from_employment_end != '') {
    $sql .= " AND `Period from Employment End` <= ?";
    $params[] = $selectedperiod_from_employment_end;
    $types .= 'i';
}

// Prepare and execute query
$stmt = $conn->prepare($sql);

// Dynamically bind parameters
if ($params) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

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