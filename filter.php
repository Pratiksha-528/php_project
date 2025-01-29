<?php
require('reset.php'); // Include your database connection file

// Retrieve filter values from the POST request
$selectedState = isset($_POST['State']) ? $_POST['State'] : '';
$selectedDateJoinedStart = isset($_POST['date_joined_start']) ? $_POST['date_joined_start'] : '';
$selectedDateJoinedEnd = isset($_POST['date_joined_end']) ? $_POST['date_joined_end'] : '';
$selectedProbationExtended = isset($_POST['probation_extended']) ? $_POST['probation_extended'] : '';
$selectedNatureOfEmployment = isset($_POST['nature_of_employment']) ? $_POST['nature_of_employment'] : '';
$selectedJoiningCTCMin = isset($_POST['joining_ctc_min']) ? $_POST['joining_ctc_min'] : '';
$selectedJoiningCTCMax = isset($_POST['joining_ctc_max']) ? $_POST['joining_ctc_max'] : '';
$selectedPeriodFromEmploymentStart = isset($_POST['period_from_employment_start']) ? $_POST['period_from_employment_start'] : '';
$selectedPeriodFromEmploymentEnd = isset($_POST['period_from_employment_end']) ? $_POST['period_from_employment_end'] : '';

// Initialize the SQL query with a base condition
$sql = "SELECT * FROM empdata1 WHERE 1=1";

// Prepare the array to hold the values for binding
$types = '';
$params = [];

// Add conditions based on the filter values
if ($selectedState != '') {
    $sql .= " AND State= ?";
    $types .= "s";  // String type
    $params[] = &$selectedState;
}

if ($selectedDateJoinedStart != '') {
    $sql .= " AND Date of joining >= ?";
    $types .= "s";  // String type for date
    $params[] = &$selectedDateJoinedStart;
}

if ($selectedDateJoinedEnd != '') {
    $sql .= " AND Date of joining <= ?";
    $types .= "s";  // String type for date
    $params[] = &$selectedDateJoinedEnd;
}

if ($selectedProbationExtended != '') {
    $sql .= " AND Probation Extended  Y/N = ?";
    $types .= "s";  // String type
    $params[] = &$selectedProbationExtended;
}

if ($selectedNatureOfEmployment != '') {
    $sql .= " AND Nature of Employment = ?";
    $types .= "s";  // String type
    $params[] = &$selectedNatureOfEmployment;
}

if ($selectedJoiningCTCMin != '') {
    $sql .= " AND Joining CTC >= ?";
    $types .= "i";  // Integer type
    $params[] = &$selectedJoiningCTCMin;
}

if ($selectedJoiningCTCMax != '') {
    $sql .= " AND Joining CTC <= ?";
    $types .= "i";  // Integer type
    $params[] = &$selectedJoiningCTCMax;
}

if ($selectedPeriodFromEmploymentStart != '') {
    $sql .= " AND Period from Employment date >= ?";
    $types .= "i";  // Integer type
    $params[] = &$selectedPeriodFromEmploymentStart;
}

if ($selectedPeriodFromEmploymentEnd != '') {
    $sql .= " AND Period from Employment date <= ?";
    $types .= "i";  // Integer type
    $params[] = &$selectedPeriodFromEmploymentEnd;
}

// Prepare the statement
$stmt = $conn->prepare($sql);

// Bind the parameters dynamically if needed
if ($types) {
    $stmt->bind_param($types, ...$params);
}

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Check if there are results
if ($result->num_rows > 0) {
    // Set headers to download the file as Excel (CSV format)
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename="filtered_employee_data.csv"');

    // Open the output stream
    $output = fopen('php://output', 'w');

    // Fetch and write the column headers
    $columns = $result->fetch_fields();
    $headers = [];
    foreach ($columns as $column) {
        $headers[] = $column->name;
    }
    fputcsv($output, $headers);

    // Fetch and write the rows
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }

    // Close the output stream
    fclose($output);
    exit();
} else {
    echo "No results found.";
}

// Close the statement and connection
$stmt->close();
$conn->close();
?><?php
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

/* Main content area */


/* Button to add employee details */
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

/* Add additional styling for small screens (Responsive Design) */
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

        /* CSS styles here (no changes) */
    </style>
</head>
<body>

<div class="add">
    <input type=button value='Add Employee details' onclick="window.location.href='empdata.php';">
</div>
<div class="sidebar">
    <a href="home.php">Home</a>
    <a href="reset_login.php">Logout</a>
</div>

<br>

<div class="export"><a href="export_data.php">Export to CSV</a><br><br></div>  

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


// Prepare the SQL query based on the filter values 


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
    </select>
    <br><br>


   

    <label for="date_joined_start">Date of Joining (Start):</label>
    <input type="date" name="date_joined_start" id="date_joined_start" value="<?= isset($_POST['date_joined_start']) ? $_POST['date_joined_start'] : '' ?>"><br><br>

    <div class="date"><label for="date_joined_end">Date of Joining (End):</label>
    <input type="date" name="date_joined_end" id="date_joined_end" value="<?= isset($_POST['date_joined_end']) ? $_POST['date_joined_end'] : '' ?>"><br><br></div>

    <div class= "probation">
        
    </div><label for="probation_extended">Probation Extended:</label>
    <select name="probation_extended" id="probation_extended">
        <option value="">Select</option>
        <option value="Y" <?= isset($_POST['probation_extended']) && $_POST['probation_extended'] == 'Y' ? 'selected' : '' ?>>Yes</option>
        <option value="N" <?= isset($_POST['probation_extended']) && $_POST['probation_extended'] == 'N' ? 'selected' : '' ?>>No</option>
    </select><br><br>

    <label for="nature_of_employment">Nature of Employment:</label>
    <select name="nature_of_employment" id="nature_of_employment">
        <option value="">Select</option>
        <option value="Intern" <?= isset($_POST['nature_of_employment']) && $_POST['nature_of_employment'] == 'Intern' ? 'selected' : '' ?>>Intern</option>
        <option value="Job" <?= isset($_POST['nature_of_employment']) && $_POST['nature_of_employment'] == 'Job' ? 'selected' : '' ?>>Job</option>
       
        
    </select><br><br>

    <label for="joining_ctc_min">Joining CTC (Min):</label>
    <input type="number" name="joining_ctc_min" id="joining_ctc_min" value="<?= isset($_POST['joining_ctc_min']) ? $_POST['joining_ctc_min'] : '' ?>"><br><br>

    <label for="joining_ctc_max">Joining CTC (Max):</label>
    <input type="number" name="joining_ctc_max" id="joining_ctc_max" value="<?= isset($_POST['joining_ctc_max']) ? $_POST['joining_ctc_max'] : '' ?>"><br><br>

    <label for="period_from_employment_start">Period from Employment Date (days) (Start):</label>
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

// SQL query based on selected state or all records if no state is selected
$sql = "SELECT * FROM empdata1 where 1=1 ";

if ($selectedState != '') {
    $sql .= "AND `State`= ?";
}

if($selecteddate_joined_start != '') {
    $sql .= "AND `Date of joining` >= ?";
}

if($selecteddate_joined_end != '') {
    $sql .= "AND `Date of joining` <= ?";
}

if($selectedprobation_extended != '') {
    $sql .= "AND `Probation Extended Y/N`=?";
}

if($selectednature_of_employment != '') {
    $sql .= "AND `Nature of Employment`=?";
}

if($selectedjoining_ctc_min!= '') {
    $sql .= "AND `Joining CTC` >=?";
}

if($selectedjoining_ctc_max!= '') {
    $sql .= "AND `Joining CTC` <=?";
}

if($selectedperiod_from_employment_start!= '') {
    $sql .= "AND `Period from Employment date` >=?";
}

if($selectedperiod_from_employment_end!= '') {
    $sql .= "AND `Period from Employment date` <=?";
}

// Add sorting based on selected column and order



// Prepared statement to prevent SQL injection

$stmt = $conn->prepare($sql);

if ($selectedState !='' && $selecteddate_joined_start !='' && $selecteddate_joined_end !='' && $selectedprobation_extended !='' && $selectednature_of_employment !='' && $selectedjoining_ctc_min !='' && $selectedjoining_ctc_max !='' && $selectedperiod_from_employment_start !='' && $selectedperiod_from_employment_end !='') {
    $stmt->bind_param("ssssssiiii", $selectedState, $selecteddate_joined_start, $selecteddate_joined_end, $selectedprobation_extended, $selectednature_of_employment, $selectedjoining_ctc_min, $selectedjoining_ctc_max, $selectedperiod_from_employment_start, $selectedperiod_from_employment_end);  // Bind the selected state as a string
}

else if ($selectedState !='' && $selecteddate_joined_start !='' && $selecteddate_joined_end !='' && $selectedprobation_extended !='' && $selectednature_of_employment !='' && $selectedjoining_ctc_min !='' && $selectedjoining_ctc_max !='' && $selectedperiod_from_employment_start !='') {
    $stmt->bind_param("ssssssiii", $selectedState, $selecteddate_joined_start, $selecteddate_joined_end, $selectedprobation_extended, $selectednature_of_employment, $selectedjoining_ctc_min, $selectedjoining_ctc_max, $selectedperiod_from_employment_start);  // Bind the selected state as a string
}

else if ($selectedState !='' && $selecteddate_joined_start !='' && $selecteddate_joined_end !='' && $selectedprobation_extended !='' && $selectednature_of_employment !='' && $selectedjoining_ctc_min !='' && $selectedjoining_ctc_max !='') {
    $stmt->bind_param("ssssssii", $selectedState, $selecteddate_joined_start, $selecteddate_joined_end, $selectedprobation_extended, $selectednature_of_employment, $selectedjoining_ctc_min, $selectedjoining_ctc_max);  // Bind the selected state as a string
}

else if ($selectedState !='' && $selecteddate_joined_start !='' && $selecteddate_joined_end !='' && $selectedprobation_extended !='' && $selectednature_of_employment !='' && $selectedjoining_ctc_min !='') {
    $stmt->bind_param("ssssssi", $selectedState, $selecteddate_joined_start, $selecteddate_joined_end, $selectedprobation_extended, $selectednature_of_employment, $selectedjoining_ctc_min);  // Bind the selected state as a string
}

else if ($selectedState !='' && $selecteddate_joined_start !='' && $selecteddate_joined_end !='' && $selectedprobation_extended !='' && $selectednature_of_employment !='') {
    $stmt->bind_param("sssssi", $selectedState, $selecteddate_joined_start, $selecteddate_joined_end, $selectedprobation_extended, $selectednature_of_employment);  // Bind the selected state as a string
}

else if ($selectedState !='' && $selecteddate_joined_start !='' && $selecteddate_joined_end !='' && $selectedprobation_extended !='') {
    $stmt->bind_param("ssssi", $selectedState, $selecteddate_joined_start, $selecteddate_joined_end, $selectedprobation_extended);  // Bind the selected state as a string
}

else if ($selectedState !='' && $selecteddate_joined_start !='' && $selecteddate_joined_end !='') {
    $stmt->bind_param("sssi", $selectedState, $selecteddate_joined_start, $selecteddate_joined_end);  // Bind the selected state as a string
}

else if ($selectedState !='' && $selecteddate_joined_start !='') {
    $stmt->bind_param("ssi", $selectedState, $selecteddate_joined_start);  // Bind the selected state as a string
}

else if ($selectedState !='') {
    $stmt->bind_param("s", $selectedState);  // Bind the selected state as a string
}

else if ($selecteddate_joined_start !='') {
    $stmt->bind_param("s", $selecteddate_joined_start);  // Bind the selected state as a string
}

else if ($selecteddate_joined_end !='') {
    $stmt->bind_param("s", $selecteddate_joined_end);  // Bind the selected state as a string
}

else if ($selectedprobation_extended !='') {
    $stmt->bind_param("s", $selectedprobation_extended);  // Bind the selected state as a string
}

else if ($selectednature_of_employment !='') {
    $stmt->bind_param("s", $selectednature_of_employment);  // Bind the selected state as a string
}

else if ($selectedjoining_ctc_min !='') {
    $stmt->bind_param("i", $selectedjoining_ctc_min);  // Bind the selected state as a string
}

else if ($selectedjoining_ctc_max !='') {
    $stmt->bind_param("i", $selectedjoining_ctc_max);  // Bind the selected state as a string
}

else if ($selectedperiod_from_employment_start !='') {
    $stmt->bind_param("i", $selectedperiod_from_employment_start);  // Bind the selected state as a string
}

else if ($selectedperiod_from_employment_end
    !='') {
        $stmt->bind_param("i", $selectedperiod_from_employment_end);  // Bind the selected state as a string
    }
    $stmt->execute();  // Execute the prepared statement
    $result = $stmt->get_result();  // Get the result from the query
    
    // Fetch and display the records
    if ($result->num_rows > 0) {
        // Get column names dynamically
        $columns = $result->fetch_fields();
        echo "<table>";
        echo "<tr>";
        foreach ($columns as $column) {
            echo "<th>". $column->name. "</th>";
        }
        echo "</tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach ($columns as $column) {
                echo "<td>". $row[$column->name]. "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No results found.";
    }

    


$stmt->close();  // Close the prepared statement
$conn->close();  // Close the database connection
?>



</body>
</html>

    
