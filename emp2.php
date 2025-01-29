<?php
session_start();

// Check if the user is logged in
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
            position: fixed;
            padding-top: 20px;
        }

        .sidebar a {
            padding: 10px;
            color: white;
            text-decoration: none;
            margin: 5px 0;
        }

        .sidebar a:hover {
            background-color: #555;
        }

        .form-container {
            margin-left: 220px;
            padding: 20px;
        }

        form {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid black;
        }

        th {
            background-color: lightslategray;
        }

        td {
            background-color: lightgray;
        }

        input[type="submit"], input[type="button"] {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            cursor: pointer;
            border: none;
        }

        input[type="submit"]:hover, input[type="button"]:hover {
            background-color: #555;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: static;
            }

            .form-container {
                margin-left: 0;
            }

            table {
                width: 100%;
                font-size: 14px;
            }
        }
    </style>

    <script>
        // JavaScript to toggle CTC fields based on "Nature of Employment"
        function toggleCTCFields() {
            var natureOfEmployment = document.getElementById("nature_of_employment").value;
            if (natureOfEmployment == "Intern") {
                document.getElementById("probation_ctc_fields").style.display = "block";
                document.getElementById("joining_ctc_fields").style.display = "none";
            } else if (natureOfEmployment == "Employee") {
                document.getElementById("probation_ctc_fields").style.display = "none";
                document.getElementById("joining_ctc_fields").style.display = "block";
            } else {
                document.getElementById("probation_ctc_fields").style.display = "none";
                document.getElementById("joining_ctc_fields").style.display = "none";
            }
        }
    </script>
</head>
<body>

<div class="sidebar">
    <a href="home.php">Home</a>
    <a href="reset_login.php">Logout</a>
</div>

<div class="form-container">
    <input type="button" value="Add Employee details" onclick="window.location.href='empdata.php';">

    <form action="emp.php" method="POST">
        <label for="employee_name">Employee Name:</label>
        <select name="employee_name" id="employee_name">
            <option value="">Select Employee Name</option>
            <?php
            $conn = new mysqli("localhost", "root", "", "employee");
            $sql = "SELECT DISTINCT `Employee Name` FROM `empdata1` ORDER BY `Employee Name` DESC";
            $result = $conn->query($sql);

            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row["Employee Name"] . "' " . ($_POST['employee_name'] == $row["Employee Name"] ? 'selected' : '') . ">" . $row["Employee Name"] . "</option>";
            }
            ?>
        </select><br><br>

        <label for="State">State:</label>
        <select name="State" id="State">
            <option value="">Select State</option>
            <?php
            $sql = "SELECT DISTINCT `State` FROM `empdata1` ORDER BY `State` DESC";
            $result = $conn->query($sql);

            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row["State"] . "' " . ($_POST['State'] == $row["State"] ? 'selected' : '') . ">" . $row["State"] . "</option>";
            }
            ?>
        </select><br><br>

        <label for="date_joined_start">Date of Joining (Start):</label>
        <input type="date" name="date_joined_start" id="date_joined_start" value="<?= $_POST['date_joined_start'] ?>"><br><br>

        <label for="date_joined_end">Date of Joining (End):</label>
        <input type="date" name="date_joined_end" id="date_joined_end" value="<?= $_POST['date_joined_end'] ?>"><br><br>
        <?php
// Check if the 'nature_of_employment' key is set in the $_POST array
$natureOfEmployment = isset($_POST['nature_of_employment']) ? $_POST['nature_of_employment'] : '';  // Default to an empty string if not set
?>

<form action="emp.php" method="POST">
    <label for="nature_of_employment">Nature of Employment:</label>
    <select name="nature_of_employment" id="nature_of_employment" onchange="fetchCTCFields()">
        <option value="">Select</option>
        <option value="Intern" <?= $natureOfEmployment == 'Intern' ? 'selected' : '' ?>>Intern</option>
        <option value="Employee" <?= $natureOfEmployment == 'Employee' ? 'selected' : '' ?>>Employee</option>
    </select><br><br>

    <!-- Dynamic CTC Fields (Populated via AJAX) -->
    <div id="ctc-fields"></div>

    <input type="submit" value="Apply Filter">
    <input type="button" value="Reset" onclick="window.location.href='emp.php';">
</form>


        <label for="probation_extended">Probation Extended:</label>
        <select name="probation_extended" id="probation_extended">
            <option value="">Select</option>
            <option value="Yes" <?= $_POST['probation_extended'] == 'Yes' ? 'selected' : '' ?>>Yes</option>
            <option value="No" <?= $_POST['probation_extended'] == 'No' ? 'selected' : '' ?>>No</option>
        </select><br><br>

        <!-- Probation CTC Fields (Hidden initially) -->
        <div id="probation_ctc_fields" style="display:none;">
            <label for="probation_ctc_min">Probation CTC (Min):</label>
            <input type="number" name="probation_ctc_min" id="probation_ctc_min" value="<?= $_POST['probation_ctc_min'] ?>"><br><br>

            <label for="probation_ctc_max">Probation CTC (Max):</label>
            <input type="number" name="probation_ctc_max" id="probation_ctc_max" value="<?= $_POST['probation_ctc_max'] ?>"><br><br>
        </div>

        <!-- Joining CTC Fields (Hidden initially) -->
        <div id="joining_ctc_fields" style="display:none;">
            <label for="joining_ctc_min">Joining CTC (Min):</label>
            <input type="number" name="joining_ctc_min" id="joining_ctc_min" value="<?= $_POST['joining_ctc_min'] ?>"><br><br>

            <label for="joining_ctc_max">Joining CTC (Max):</label>
            <input type="number" name="joining_ctc_max" id="joining_ctc_max" value="<?= $_POST['joining_ctc_max'] ?>"><br><br>
        </div>

        <label for="period_from_employment_start">Period from Employment Date (days) (Start):</label>
        <input type="number" name="period_from_employment_start" id="period_from_employment_start" value="<?= $_POST['period_from_employment_start'] ?>"><br><br>

        <label for="period_from_employment_end">Period from Employment Date (End):</label>
        <input type="number" name="period_from_employment_end" id="period_from_employment_end" value="<?= $_POST['period_from_employment_end'] ?>"><br><br>

        <input type="submit" value="Apply Filter">
        <input type="button" value="Reset" onclick="window.location.href='emp.php';">
    </form>

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

// The filter data used for the query
$selectedemployee_name = isset($_POST['employee_name']) ? $_POST['employee_name'] :'';
$selectedState = isset($_POST['State']) ? $_POST['State'] : '';
$selecteddate_joined_start = isset($_POST['date_joined_start']) ? $_POST['date_joined_start'] : '';
$selecteddate_joined_end = isset($_POST['date_joined_end']) ? $_POST['date_joined_end'] : '';
$selectedprobation_extended = isset($_POST['probation_extended']) ? $_POST['probation_extended'] : '';
$selectednature_of_employment = isset($_POST['nature_of_employment']) ? $_POST['nature_of_employment'] : '';
$selectedjoining_ctc_min = isset($_POST['joining_ctc_min']) ? $_POST['joining_ctc_min'] : '';
$selectedjoining_ctc_max = isset($_POST['joining_ctc_max']) ? $_POST['joining_ctc_max'] : '';
$selectedperiod_from_employment_start = isset($_POST['period_from_employment_start']) ? $_POST['period_from_employment_start'] : '';
$selectedperiod_from_employment_end = isset($_POST['period_from_employment_end']) ? $_POST['period_from_employment_end'] : '';


// After displaying the filtered data, provide an export link
echo "<div class='export'>
<a href='export_data.php?State=$selectedState&date_joined_start=$selecteddate_joined_start&date_joined_end=$selecteddate_joined_end&probation_extended=$selectedprobation_extended&nature_of_employment=$selectednature_of_employment&joining_ctc_min=$selectedjoining_ctc_min&joining_ctc_max=$selectedjoining_ctc_max&period_from_employment_start=$selectedperiod_from_employment_start&period_from_employment_end=$selectedperiod_from_employment_end'>Download Data (CSV)</a>
</div>";
?>
<br><br>
<?php
// Prepare the query based on the filter data
// Get available states for the dropdown
$sql = "SELECT DISTINCT `State` FROM `empdata1` ORDER BY `State` DESC";
$result = $conn->query($sql);

// Display the filter form
?>


    <?php
    // Construct SQL query based on filters
    $filters = [];
    $params = [];
    $sql = "SELECT * FROM `empdata1` WHERE 1=1";

    if ($_POST['employee_name']) {
        $sql .= " AND `Employee Name` = ?";
        $filters[] = 's';
        $params[] = $_POST['employee_name'];
    }
    if ($_POST['State']) {
        $sql .= " AND `State` = ?";
        $filters[] = 's';
        $params[] = $_POST['State'];
    }
    if ($_POST['date_joined_start']) {
        $sql .= " AND `Date of joining` >= ?";
        $filters[] = 's';
        $params[] = $_POST['date_joined_start'];
    }
    if ($_POST['date_joined_end']) {
        $sql .= " AND `Date of joining` <= ?";
        $filters[] = 's';
        $params[] = $_POST['date_joined_end'];
    }
    if ($_POST['nature_of_employment']) {
        $sql .= " AND `Nature of Employment` = ?";
        $filters[] = 's';
        $params[] = $_POST['nature_of_employment'];
    }
    if ($_POST['probation_extended']) {
        $sql .= " AND `Probation Extended  Y/N` = ?";
        $filters[] = 's';
        $params[] = $_POST['probation_extended'];
    }

    // Filter by Joining or Probation CTC based on employment type
    if ($_POST['nature_of_employment'] == 'Intern') {
        if ($_POST['probation_ctc_min']) {
            $sql .= " AND `Probation CTC` >= ?";
            $filters[] = 'd';
            $params[] = $_POST['probation_ctc_min'];
        }
        if ($_POST['probation_ctc_max']) {
            $sql .= " AND `Probation CTC` <= ?";
            $filters[] = 'd';
            $params[] = $_POST['probation_ctc_max'];
        }
    } else if ($_POST['nature_of_employment'] == 'Employee') {
        if ($_POST['joining_ctc_min']) {
            $sql .= " AND `Joining  CTC` >= ?";
            $filters[] = 'd';
            $params[] = $_POST['joining_ctc_min'];
        }
        if ($_POST['joining_ctc_max']) {
            $sql .= " AND `Joining  CTC` <= ?";
            $filters[] = 'd';
            $params[] = $_POST['joining_ctc_max'];
        }
    }

    // Period filters
    if ($_POST['period_from_employment_start']) {
        $sql .= " AND DATEDIFF(CURRENT_DATE, `Date of joining`) >= ?";
        $filters[] = 'i';
        $params[] = $_POST['period_from_employment_start'];
    }
    if ($_POST['period_from_employment_end']) {
        $sql .= " AND DATEDIFF(CURRENT_DATE, `Date of joining`) <= ?";
        $filters[] = 'i';
        $params[] = $_POST['period_from_employment_end'];
    }

    $stmt = $conn->prepare($sql);
    if (!empty($filters)) {
        $stmt->bind_param(implode('', $filters), ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>Employee Name</th><th>State</th><th>Date of Joining</th><th>Nature of Employment</th><th>Probation Extended</th><th>Joining CTC</th><th>Period (days)</th><th>Current Salary</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["Employee Name"] . "</td>";
            echo "<td>" . $row["State"] . "</td>";
            echo "<td>" . $row["Date of joining"] . "</td>";
            echo "<td>" . $row["Nature of Employment"] . "</td>";
            echo "<td>" . $row["Probation Extended  Y/N"] . "</td>";
            echo "<td>" . $row["Joining  CTC"] . "</td>";
            echo "<td>" . $row["Period from employment date (days)"] . "</td>";
            echo "<td>" . $row["Current Salary"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No results found!";
    }

    $stmt->close();
    $conn->close();
    ?>
</div>

</body>
</html>
