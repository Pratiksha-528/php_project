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

// Get filter values from the POST request
$selectedState = isset($_POST['State']) ? $_POST['State'] : '';
$selectedDateJoinedStart = isset($_POST['date_joined_start']) ? $_POST['date_joined_start'] : '';
$selectedDateJoinedEnd = isset($_POST['date_joined_end']) ? $_POST['date_joined_end'] : '';
$selectedProbationExtended = isset($_POST['probation_extended']) ? $_POST['probation_extended'] : '';
$selectedNatureOfEmployment = isset($_POST['nature_of_employment']) ? $_POST['nature_of_employment'] : '';
$selectedJoiningCTCMin = isset($_POST['joining_ctc_min']) ? $_POST['joining_ctc_min'] : '';
$selectedJoiningCTCMax = isset($_POST['joining_ctc_max']) ? $_POST['joining_ctc_max'] : '';
$selectedPeriodFromEmploymentStart = isset($_POST['period_from_employment_start']) ? $_POST['period_from_employment_start'] : '';
$selectedPeriodFromEmploymentEnd = isset($_POST['period_from_employment_end']) ? $_POST['period_from_employment_end'] : '';

// Initialize SQL query
$sql = "SELECT * FROM empdata1 WHERE 1=1";

// Dynamically add conditions to the SQL query based on the filter values
if ($selectedState != '') {
    $sql .= " AND State = '$selectedState'";
}

if ($selectedDateJoinedStart != '') {
    $sql .= " AND `Date of joining` >= '$selectedDateJoinedStart'";
}

if ($selectedDateJoinedEnd != '') {
    $sql .= " AND `Date of joining` <= '$selectedDateJoinedEnd'";
}

if ($selectedProbationExtended != '') {
    $sql .= " AND `Probation Extended Y/N` = '$selectedProbationExtended'";
}

if ($selectedNatureOfEmployment != '') {
    $sql .= " AND `Nature of Employment` = '$selectedNatureOfEmployment'";
}

if ($selectedJoiningCTCMin != '') {
    $sql .= " AND `Joining CTC` >= $selectedJoiningCTCMin";
}

if ($selectedJoiningCTCMax != '') {
    $sql .= " AND `Joining CTC` <= $selectedJoiningCTCMax";
}

if ($selectedPeriodFromEmploymentStart != '') {
    $sql .= " AND `Period from Employment date` >= $selectedPeriodFromEmploymentStart";
}

if ($selectedPeriodFromEmploymentEnd != '') {
    $sql .= " AND `Period from Employment date` <= $selectedPeriodFromEmploymentEnd";
}

// Execute the query
$result = $conn->query($sql);

// Check if results were found
if ($result->num_rows > 0) {
    // Set headers to download the file as CSV
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename="filtered_emp_data.csv"');

    // Open the output stream to write CSV
    $output = fopen('php://output', 'w');

    // Get column names dynamically
    $columns = $result->fetch_fields();
    $headers = [];

    // Fetch and write column headers to the CSV file
    foreach ($columns as $column) {
        $headers[] = $column->name;
    }
    fputcsv($output, $headers);

    // Fetch and write rows to the CSV file
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }

    // Close the output stream
    fclose($output);
    exit();
} else {
    echo "No results found for the selected filters.";
}

// Close the connection
$conn->close();
?>
