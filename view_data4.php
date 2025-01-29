<?php






session_start();  // Start the session to check login status

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    echo "You need to <a href='reset_login.php'>login</a> first.";
    exit();
}

echo "Welcome, " . $_SESSION['username'] . "!<br><br>";

// Include PhpSpreadsheet files manually
/*echo "Current working directory: " . getcwd() . "<br>";
require_once 'C:\xamppt\htdocs\myproject\reset\Education.csv';

// Continue with the rest of your code*/


// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "employee";  // Ensure your database name is correct

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the download button was clicked
if (isset($_POST['download_excel'])) {
    // Get selected filter values
    $selectedYear = isset($_POST['year']) ? $_POST['year'] : '';
    $selectedHigh = isset($_POST['high']) ? $_POST['high'] : '';

    // Prepare the SQL query for filtered results
    $sql = "SELECT * FROM `education` WHERE 1=1";
    
    if ($selectedYear != '') {
        $sql .= " AND `Year of completion` = ?";
    }

    if ($selectedHigh != '') {
        $sql .= " AND `Highest level of education` = ?";
    }

    $stmt = $conn->prepare($sql);

    if ($selectedYear != '' && $selectedHigh != '') {
        $stmt->bind_param("ss", $selectedYear, $selectedHigh);
    } elseif ($selectedYear != '') {
        $stmt->bind_param("s", $selectedYear);
    } elseif ($selectedHigh != '') {
        $stmt->bind_param("s", $selectedHigh);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    // Create a new spreadsheet
    $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set the headers for the spreadsheet
    $sheet->setCellValue('A1', 'Employee ID');
    $sheet->setCellValue('B1', 'Employee Name');
    $sheet->setCellValue('C1', 'Highest Level of Education');
    $sheet->setCellValue('D1', 'Year of Completion');
    $sheet->setCellValue('E1', 'Other Course/Certification');
    $sheet->setCellValue('F1', 'Institute Name');
    $sheet->setCellValue('G1', 'Course Completion');
    $sheet->setCellValue('H1', 'Upload Document');

    // Write data to the Excel sheet
    $rowNum = 2;
    while ($row = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $rowNum, $row['Employee ID']);
        $sheet->setCellValue('B' . $rowNum, $row['Employee Name']);
        $sheet->setCellValue('C' . $rowNum, $row['Highest level of education']);
        $sheet->setCellValue('D' . $rowNum, $row['Year of completion']);
        $sheet->setCellValue('E' . $rowNum, $row['Other course/certification']);
        $sheet->setCellValue('F' . $rowNum, $row['Institute name']);
        $sheet->setCellValue('G' . $rowNum, $row['Course completion']);
        $sheet->setCellValue('H' . $rowNum, $row['Upload Document']);
        $rowNum++;
    }

    // Set headers to download the file as Excel
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="filtered_data.xlsx"');
    header('Cache-Control: max-age=0');

    // Write the file to the output
    $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save('php://output');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Education Details</title>
    <style>
        /* Add your CSS styling here */
    </style>
</head>
<body>

<div class="add"><input type="button" value="Add Education Details" onclick="window.location.href='reset.php';"></div>

<div class="sidebar">
    <a href="home.php">Home</a>
    <a href="reset_login.php">Logout</a>
</div>

<form action="view_data.php" method="POST">
    <label for="year">Year of Graduation</label>
    <select name="year" id="year">
        <option value="">Select Year</option>
        <?php
        $stmt = $conn->prepare("SELECT DISTINCT `Year of completion` FROM `education` ORDER BY `Year of completion` DESC");
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $selected = ($row["Year of completion"] == $selectedYear) ? 'selected' : '';
                echo "<option value='" . $row["Year of completion"] . "' $selected>" . $row["Year of completion"] . "</option>";
            }
        } else {
            echo "<option value=''>No data available</option>";
        }
        ?>
    </select>
    <br><br>

    <label for="high">Highest Level of Education</label>
    <select name="high" id="high">
        <option value="">Select Level of Education</option>
        <?php
        $stmt = $conn->prepare("SELECT DISTINCT `Highest level of education` FROM `education` ORDER BY `Highest level of education`");
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $selected = ($row["Highest level of education"] == $selectedHigh) ? 'selected' : '';
                echo "<option value='" . $row["Highest level of education"] . "' $selected>" . $row["Highest level of education"] . "</option>";
            }
        } else {
            echo "<option value=''>No data available</option>";
        }
        ?>
    </select>
    <br><br>

    <div class="filter_container">
        <input type="submit" value="Apply Filter">
        <input type="button" value="Reset" onclick="window.location.href='view_data.php';">
    </div>
</form>

<!-- Export Excel Button -->
<form action="view_data.php" method="POST">
    <input type="hidden" name="year" value="<?php echo $selectedYear; ?>">
    <input type="hidden" name="high" value="<?php echo $selectedHigh; ?>">
    <input type="submit" name="download_excel" value="Download Excel">
</form>

</body>
</html>
