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
            display: block;
        }

        .sidebar {
            display: block;
            padding: 10px;
            color: white;
            text-decoration: none;
            margin: 5px 0;
        }

        .sidebar:hover {
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
            background-color:green;
            color: white
            
            
            ;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 3px;
            display: inline-block;
            margin-right: 10px;
        }

        a:hover {
            background-color: darkgreen;
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
    <input type="button" value="Home" onclick="window.location.href='home.php';">
    <input type="button" value="Logout" onclick="window.location.href='reset_login.php';">
    
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

// The filter data used for the query
$selectedemployee_name = isset($_POST['employee_name']) ? $_POST['employee_name'] : '';
$selectedState = isset($_POST['State']) ? $_POST['State'] : '';
$selecteddate_joined_start = isset($_POST['date_joined_start']) ? $_POST['date_joined_start'] : '';
$selecteddate_joined_end = isset($_POST['date_joined_end']) ? $_POST['date_joined_end'] : '';
$selectedprobation_extended = isset($_POST['probation_extended']) ? $_POST['probation_extended'] : '';
$selectednature_of_employment = isset($_POST['nature_of_employment']) ? $_POST['nature_of_employment'] : '';
$selectedjoining_ctc_min = isset($_POST['joining_ctc_min']) ? $_POST['joining_ctc_min'] : '';
$selectedjoining_ctc_max = isset($_POST['joining_ctc_max']) ? $_POST['joining_ctc_max'] : '';
$selectedperiod_from_employment_start = isset($_POST['period_from_employment_start']) ? $_POST['period_from_employment_start'] : '';
$selectedperiod_from_employment_end = isset($_POST['period_from_employment_end']) ? $_POST['period_from_employment_end'] : '';
$selectedprobation_period_ctc_min = isset($_POST['probation_period_ctc_min']) ? $_POST['probation_period_ctc_min'] : '';
$selectedprobation_period_ctc_max = isset($_POST['probation_period_ctc_max']) ? $_POST['probation_period_ctc_max'] : '';

// Fetch Employee Names for the dropdown list
// After displaying the filtered data, provide an export link
/*echo "<div class='export'>
        <a href='export_data.php?State=$selectedState&date_joined_start=$selecteddate_joined_start&date_joined_end=$selecteddate_joined_end&probation_extended=$selectedprobation_extended&nature_of_employment=$selectednature_of_employment&joining_ctc_min=$selectedjoining_ctc_min&joining_ctc_max=$selectedjoining_ctc_max&period_from_employment_start=$selectedperiod_from_employment_start&period_from_employment_end=$selectedperiod_from_employment_end'>Download Filtered Data (CSV)</a>
      </div>";
?>
<br><br>*/


// Fetch Employee Names for the dropdown list
$sql = "SELECT DISTINCT `Employee Name` FROM `empdata1` ORDER BY `Employee Name` DESC";
$result = $conn->query($sql);

// Capture selected employee name if the form has been submitted
$selectedemployee_name = isset($_POST['employee_name']) ? $_POST['employee_name'] : '';
?>

<form action="emp.php" method="post">
    <label for="employee_name">Employee Name:</label>
    <select name="employee_name" id="employee_name">
        <option value="">Select Employee Name</option>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $selected = ($row["Employee Name"] == $selectedemployee_name) ? 'selected' : '';
                echo "<option value='" . $row["Employee Name"] . "' $selected>" . $row["Employee Name"] . "</option>";
            }
        } else {
            echo "<option value=''>No data available</option>";
        }
        ?>
    </select><br><br>

   


    <?php
// Prepare the query based on the filter data
// Get available states for the dropdown
$sql = "SELECT DISTINCT `State` FROM `empdata1` ORDER BY `State` DESC";
$result = $conn->query($sql);

// Display the filter form
?>
<form action="emp.php" method="POST">
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

    

    <label for="date_joined_start">Date of Joining (Start):</label>
    <input type="date" name="date_joined_start" id="date_joined_start" value="<?= $selecteddate_joined_start ?>"><br><br>

    <label for="date_joined_end">Date of Joining (End):</label>
    <input type="date" name="date_joined_end" id="date_joined_end" value="<?= $selecteddate_joined_end ?>"><br><br>
    
    <label for="nature_of_employment">Nature of Employment:</label>
    <select name="nature_of_employment" id="nature_of_employment" onchange="toggleSalaryFields()">
        <option value="">Select</option>
        <option value="Intern" <?= $selectednature_of_employment == 'Intern' ? 'selected' : '' ?>>Intern</option>
        <option value="Employee" <?= $selectednature_of_employment == 'Employee' ? 'selected' : '' ?>>Employee</option>
    </select><br><br>

    <div id="intern_salary_fields" style="display: <?= $selectednature_of_employment == 'Intern' ? 'block' : 'none' ?>;">
        <label for="probation_period_ctc_min">Current salary range(Min):</label>
        <input type="number" name="probation_period_ctc_min" id="probation_period_ctc_min" value="<?= $selectedprobation_period_ctc_min ?>"><br><br>

        <label for="probation_period_ctc_max">Current salary range(Max):</label>
        <input type="number" name="probation_period_ctc_max" id="probation_period_ctc_max" value="<?= $selectedprobation_period_ctc_max ?>"><br><br>
    </div>

    <div id="employee_salary_fields" style="display: <?= $selectednature_of_employment == 'Employee' ? 'block' : 'none' ?>;">
        <label for="joining_ctc_min">Current salary range (Min):</label>
        <input type="number" name="joining_ctc_min" id="joining_ctc_min" value="<?= $selectedjoining_ctc_min ?>"><br><br>

        <label for="joining_ctc_max">Current salary range (Max):</label>
        <input type="number" name="joining_ctc_max" id="joining_ctc_max" value="<?= $selectedjoining_ctc_max ?>"><br><br>
    </div>

    <label for="probation_extended">Probation Extended:</label>
    <select name="probation_extended" id="probation_extended">
        <option value="">Select</option>
        <option value="Yes" <?= $selectedprobation_extended == 'Yes' ? 'selected' : '' ?>>Yes</option>
        <option value="No" <?= $selectedprobation_extended == 'No' ? 'selected' : '' ?>>No</option>
    </select><br><br>

    
    <input type="submit" value="Apply Filter">
    <input type="button" value="Reset" onclick="window.location.href='emp.php';">
</form>

<br><br>

<script>
    // Toggle fields based on the selected nature of employment
    function toggleSalaryFields() {
        var natureOfEmployment = document.getElementById("nature_of_employment").value;
        if (natureOfEmployment == "Intern") {
            document.getElementById("intern_salary_fields").style.display = "block";
            document.getElementById("employee_salary_fields").style.display = "none";
        } else if (natureOfEmployment == "Employee") {
            document.getElementById("intern_salary_fields").style.display = "none";
            document.getElementById("employee_salary_fields").style.display = "block";
        } else {
            document.getElementById("intern_salary_fields").style.display = "none";
            document.getElementById("employee_salary_fields").style.display = "none";
        }
    }

    // Call the function on load to set the initial visibility
    window.onload = toggleSalaryFields;
</script>



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
$selectedemployee_name = isset($_POST['employee_name']) ? $_POST['employee_name'] : '';
$selectedState = isset($_POST['State']) ? $_POST['State'] : '';
$selecteddate_joined_start = isset($_POST['date_joined_start']) ? $_POST['date_joined_start'] : '';
$selecteddate_joined_end = isset($_POST['date_joined_end']) ? $_POST['date_joined_end'] : '';
$selectedprobation_extended = isset($_POST['probation_extended']) ? $_POST['probation_extended'] : '';
$selectednature_of_employment = isset($_POST['nature_of_employment']) ? $_POST['nature_of_employment'] : '';
$selectedjoining_ctc_min = isset($_POST['joining_ctc_min']) ? $_POST['joining_ctc_min'] : '';
$selectedjoining_ctc_max = isset($_POST['joining_ctc_max']) ? $_POST['joining_ctc_max'] : '';
$selectedperiod_from_employment_start = isset($_POST['period_from_employment_start']) ? $_POST['period_from_employment_start'] : '';
$selectedperiod_from_employment_end = isset($_POST['period_from_employment_end']) ? $_POST['period_from_employment_end'] : '';
$selectedprobation_period_ctc_min = isset($_POST['probation_period_ctc_min']) ? $_POST['probation_period_ctc_min'] : '';
$selectedprobation_period_ctc_max = isset($_POST['probation_period_ctc_max']) ? $_POST['probation_period_ctc_max'] : '';


// After displaying the filtered data, provide an export link
echo "<div class='export'>
<a href='export_data.php?State=$selectedState&date_joined_start=$selecteddate_joined_start&date_joined_end=$selecteddate_joined_end&probation_extended=$selectedprobation_extended&nature_of_employment=$selectednature_of_employment&joining_ctc_min=$selectedjoining_ctc_min&joining_ctc_max=$selectedjoining_ctc_max&period_from_employment_start=$selectedperiod_from_employment_start&period_from_employment_end=$selectedperiod_from_employment_end&probation_period_ctc_min=$selectedprobation_period_ctc_min&probation_period_ctc_max=$selectedprobation_period_ctc_max'>Download Data (CSV)</a>
</div>";

?>
<br><br>




<?php
// Prepare the SQL query for filtered results
$sql = "SELECT * FROM empdata1 WHERE 1=1";

// Array to hold the parameters for binding
$params = [];
$types = '';

// If employee name is selected, add it to the SQL query
if ($selectedemployee_name != '') {
    $sql .= " AND `Employee Name` = ?";
    $params[] = $selectedemployee_name;
    $types .= 's';  // 's' stands for string
}

// Add filter conditions and collect parameters for binding
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

// Add CTC filtering logic for Interns and Employees
// Add CTC filtering logic for Interns and Employees
if ($selectednature_of_employment == 'Intern') {
    if ($selectedprobation_period_ctc_min != '') {
        // Directly compare the Probation Period CTC for Interns
        $sql .= " AND `Probation period CTC` >= ?";
        $params[] = $selectedprobation_period_ctc_min;
        $types .= 'i';  // Use 'i' for integer values
    }

    if ($selectedprobation_period_ctc_max != '') {
        // Directly compare the Probation Period CTC for Interns
        $sql .= " AND `Probation period CTC` <= ?";
        $params[] = $selectedprobation_period_ctc_max;
        $types .= 'i';  // Use 'i' for integer values
    }
} elseif ($selectednature_of_employment == 'Employee') {
    if ($selectedjoining_ctc_min != '') {
        // Directly compare the Joining CTC for Employees
        $sql .= " AND `Joining CTC` >= ?";
        $params[] = $selectedjoining_ctc_min;
        $types .= 'i';  // Use 'i' for integer values
    }

    if ($selectedjoining_ctc_max != '') {
        // Directly compare the Joining CTC for Employees
        $sql .= " AND `Joining CTC` <= ?";
        $params[] = $selectedjoining_ctc_max;
        $types .= 'i';  // Use 'i' for integer values
    }
}



if ($selectedperiod_from_employment_start != '') {
    $sql .= " AND DATEDIFF(CURDATE(), `Date of joining`) >= ?";
    $params[] = $selectedperiod_from_employment_start;
    $types .= 'i';
}

if ($selectedperiod_from_employment_end != '') {
    $sql .= " AND DATEDIFF(CURDATE(), `Date of joining`) <= ?";
    $params[] = $selectedperiod_from_employment_end;
    $types .= 'i';
}

// Prepare and execute the statement
$stmt = $conn->prepare($sql);
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

    // Conditionally show the Actions column if the user is not 'view' role
    if ($role != 'view') {
        echo "<th>Actions</th>";
    }

    echo "</tr>"; 
    
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
        if ($role != 'view') {
            echo "<td>";

            // Display actions based on the role
            if ($role == 'admin') {
                echo "<div class='edit'><a href='edit_empdata.php?id=$employeeId'>Update</a></div>";
                echo "<div class='delete'><a href='delete_empdata.php?id=$employeeId' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a></div>";
            } elseif ($role == 'edit_user') {
                echo "<div class='edit'><a href='edit_bank.php?id=$employeeId'>Update</a></div>";
            } elseif ($role == 'delete_user') {
                echo "<div class='delete'><a href='delete_bank.php?id=$employeeId' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a></div>";
            }

            echo "</td>";
        }

        echo "</tr>";
    }
    echo '</table>';
} else {
    echo "No results found.";
}

// For debugging purposes (remove in production)
echo $sql;

// Close the statement and connection
$stmt->close();
$conn->close();
?>
