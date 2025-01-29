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
    <title>Education Details</title>

    <!-- Prevent the browser from caching the page -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <script type="text/javascript">
        // Prevent the user from using the back button by pushing a state in history
        window.history.pushState(null, null, window.location.href);
        window.onpopstate = function() {
            window.history.pushState(null, null, window.location.href);
        }
    </script>

    <style>
        /* Styling for the 'Add education details' */
        .add input {
            background-color: black;
            color: wheat;
            padding: 10px;
            text-decoration: none;
            display: inline-block;
        }

        a {
            background-color: black;
            color: white;
            padding: 10px;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        a:hover {
            color: brown;
        }

        .filter_container input {
            width: 100px;
            height: 30px;
            padding: 5px;
            margin-right: 10px;
        }

        .edit input {
            background-color: black;
            color: white;
            padding: 10px;
            text-decoration: none;
            display: inline-block;
        }

        a:hover {
            background-color: gray;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .filter_container {
            display: block;
            justify-content: center;
            margin-bottom: 20px;
            margin-top: 20px;
        }

        .sidebar {
            width: 200px;
            background-color: #333;
            color: white;
            display: block;
        }

        .edit, .delete {
            background-color: gray;
            padding: 5px;
            margin: 5px;
        }

        .edit a, .delete a {
            color: white;
            text-decoration: none;
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

<div class="add"><input type="button" value="Add Education Details" onclick="window.location.href='reset.php';"></div>


<div class="sidebar">
    <a href="home.php">Home</a>
    <a href="reset_login.php">Logout</a>
</div>


<?php
// Server connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "employee";  // Ensure your database name is correct

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get selected filter values
$selectedYear = isset($_POST['year']) ? $_POST['year'] : '';
$selectedHigh = isset($_POST['high']) ? $_POST['high'] : '';

// Fetch filter options for year and education level
echo "<h2>Apply Filters</h2>";
?>

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

<?php
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

if ($result->num_rows > 0) {
    echo "<table>
        <caption>Education Details</caption>
        <tr>
            <th>Employee ID</th>
            <th>Employee Name</th>
            <th>Highest level of education</th>
            <th>Year of completion</th>
            <th>Other course/certification</th>
            <th>Institute name</th>
            <th>Course completion</th>
            <th>Upload Document</th>
            <th>Actions</th>
        </tr>";
    while ($row = $result->fetch_assoc()) {
        $employeeId = $row["Employee ID"];
        $documentPath = $row["Upload Document"];  // Assuming it's a relative path
        echo "<tr>
            <td>" . $row["Employee ID"] . "</td>
            <td>" . $row["Employee Name"] . "</td>
            <td>" . $row["Highest level of education"] . "</td>
            <td>" . $row["Year of completion"] . "</td>
            <td>" . $row["Other course/certification"] . "</td>
            <td>" . $row["Institute name"] . "</td>
            <td>" . $row["Course completion"] . "</td>
            <td>
               
                <a href='upload_document.php?id=$employeeId'>Get   Document</a>

           
<td>    
                <div class='edit'><a href='edit_education.php?id=$employeeId'>Update</a></div>
                <div class='delete'><a href='delete_education.php?id=$employeeId' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a></div>
            </td>
        </tr>";
    }
    echo "</table>";
} else {
    echo "No results found.";
}

$stmt->close();
$conn->close();
?>





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
 


