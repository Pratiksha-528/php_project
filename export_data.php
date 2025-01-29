<?php
// Check if the user is logged in (as per your session handling)
session_start();
if (!isset($_SESSION['username'])) {
    echo "You need to <a href='reset_login.php'>login</a> first.";
    exit();
}

// Server connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "employee";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get filter values from the URL
$selectedemployee_name = isset($_GET['employee_name']) ? $_GET['employee_name'] :'';
$selectedState = isset($_GET['State']) ? $_GET['State'] : '';
$selecteddate_joined_start = isset($_GET['date_joined_start']) ? $_GET['date_joined_start'] : '';
$selecteddate_joined_end = isset($_GET['date_joined_end']) ? $_GET['date_joined_end'] : '';
$selectedprobation_extended = isset($_GET['probation_extended']) ? $_GET['probation_extended'] : '';
$selectednature_of_employment = isset($_GET['nature_of_employment']) ? $_GET['nature_of_employment'] : '';
$selectedjoining_ctc_min = isset($_GET['joining_ctc_min']) ? $_GET['joining_ctc_min'] : '';
$selectedjoining_ctc_max = isset($_GET['joining_ctc_max']) ? $_GET['joining_ctc_max'] : '';
$selectedperiod_from_employment_start = isset($_GET['period_from_employment_start']) ? $_GET['period_from_employment_start'] : '';
$selectedperiod_from_employment_end = isset($_GET['period_from_employment_end']) ? $_GET['period_from_employment_end'] : '';


// SQL query based on selected filters

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

if ($selectedjoining_ctc_min != '') {
    $sql .= " AND `Probation period CTC` <= ?";
    $params[] = $selectedjoining_ctc_min;
    $types .= 'd';  // 'd' stands for decimal
}

if ($selectedjoining_ctc_max != '') {
    $sql .= " AND `Probation period CTC` >= ?";
    $params[] = $selectedjoining_ctc_max;
    $types .= 'd';
}

if ($selectedperiod_from_employment_start != '') {
    $sql .= " AND `Period from employment date` >= ?";
    $params[] = $selectedperiod_from_employment_start;
    $types .= 'i';  // 'i' stands for integer
}

if ($selectedperiod_from_employment_end != '') {
    $sql .= " AND `Period from employment date` <= ?";
    $params[] = $selectedperiod_from_employment_end;
    $types .= 'i';
}

// Prepare and execute query
$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Start output buffering to avoid issues with headers
ob_start();

// Set the Content-Type to CSV
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="employee_data.csv"');

// Open output stream
$output = fopen('php://output', 'w');

// Get column names dynamically and write the header row
$columns = $result->fetch_fields();
$headers = [];
foreach ($columns as $column) {
    $headers[] = $column->name;
}
fputcsv($output, $headers);  // Write the headers to the CSV file

// Write data to CSV file
while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}

// Close the output stream
fclose($output);

// Clean up
$stmt->close();
$conn->close();
exit(); 
