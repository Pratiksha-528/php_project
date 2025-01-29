<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Education Form</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
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
        a {view_data.php
            text-decoration: none;
            color: white;
            background-color: black;
            display: inline-block;
            padding: 4px 12px;
        }
        a:hover {
            color: red;
        }
    </style>


<script type="text/javascript">
        // Prevent the user from going back to the previous page
        window.history.forward();
        window.onload = function() {
            setTimeout(function() {
                window.history.forward();
            }, 0);
        };
    </script>
    
</head>
<body>

<?php
session_start();  // Start the session to check login status

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    echo "You need to <a href='reset_login.php'>login</a> first.";
    exit();
}

echo "Welcome, " . $_SESSION['username'] . "!<br><br>";
?>

<h2>Apply Filters</h2>
<form action="filters.php" method="POST">
    <label for="year">Year of Graduation</label>
    <select name="year" id="year">
        <option value="2023">2023</option>
        <option value="2022">2022</option>
        <option value="2021">2021</option>
        <option value="2020">2020</option>
    </select>
    <br><br>

    <label for="high">Highest Level of Education</label>
    <select name="high" id="high">
        <option value="Masters">Masters</option>
        <option value="Bachelors">Bachelors</option>
    </select>
    <br><br>

    <input type="submit" value="Apply Filter">
    <br><br>
    <a href="home.php">Home</a><br><br>
    <a href="reset_login.php">Logout</a>
    
</form>

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

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the selected values from the form
    $selected_year = $_POST['year'];
    $selected_high = $_POST['high'];

    // SQL query to select data based on user input
    $sql = "SELECT `Employee ID`, `Employee Name`, `Highest level of education`, `Year of completion` 
            FROM education 
            WHERE `Year of completion` = '$selected_year' 
            AND `Highest level of education` = '$selected_high' 
            ORDER BY `Year of completion` DESC";  // Sorting by Year of completion (Descending)

    // Execute the query
    $result = $conn->query($sql);

    // Check if there are results
    if ($result->num_rows > 0) {
        // Output the data in a table
        echo "<table border='1'>
                <tr>
                    <th>Employee ID</th>
                    <th>Employee Name</th>
                    <th>Highest level of education</th>
                    <th>Year of completion</th>
                    <th>Actions</th> <!-- Added column for actions -->
                </tr>";
        while ($row = $result->fetch_assoc()) {
            $employeeId = $row["Employee ID"];  // Assign employee ID to variable
            echo "<tr>
                    <td>" . $row["Employee ID"] . "</td>
                    <td>" . $row["Employee Name"] . "</td>
                    <td>" . $row["Highest level of education"] . "</td>
                    <td>" . $row["Year of completion"] . "</td>
                    <td>
                        <div class='edit'><a href='edit_education.php?id=$employeeId'>Update</a></div>
                        <div class='delete'><a href='delete_education.php?id=$employeeId' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a></div>
                    </td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "No results found for the selected year and education level.";
    }
} else {
    // If the form is not submitted, show all data
    $sql = "SELECT `Employee ID`, `Employee Name`, `Highest level of education`, `Year of completion` FROM education";
    $result = $conn->query($sql);

    // Check if there are any results
    if ($result->num_rows > 0) {
        // Output the data in a table
        echo "<table border='1'>
                <tr>
                    <th>Employee ID</th>
                    <th>Employee Name</th>
                    <th>Highest level of education</th>
                    <th>Year of completion</th>
                    <th>Actions</th> <!-- Added column for actions -->
                </tr>";
        while ($row = $result->fetch_assoc()) {
            $employeeId = $row["Employee ID"];  // Assign employee ID to variable
            echo "<tr>
                    <td>" . $row["Employee ID"] . "</td>
                    <td>" . $row["Employee Name"] . "</td>
                    <td>" . $row["Highest level of education"] . "</td>
                    <td>" . $row["Year of completion"] . "</td>
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
}

// Close the database connection
$conn->close();
?>
</body>
</html>
