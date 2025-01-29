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
    <style type="text/css">
       
        .container {
            text-align: center;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        a {
            background-color: black;
            color: white;
            padding: 10px;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
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
            display: flex;
            justify-content: center;
            margin-top: 20px;
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
    <div class="container">
        <div class="ADD"><a href="reset.php">Add Education Details</a><br></div>
        <h2>Apply Filters</h2>
        <form action="view_data.php" method="POST">
            <label for="year">Year of Graduation</label>
            <select name="year" id="year">
                <option value="">Select Year</option>
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
                $selectedLevel = isset($_POST['level']) ? $_POST['level'] : '';

                $sql = "SELECT DISTINCT `Year of completion` FROM `education` ORDER BY `Year of completion` DESC";
                $result = $conn->query($sql);

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
            <label for="level">Level of Education</label>
            <select name="level" id="level">
                <option value="">Select Level of education</option>
                <?php
                $sql = "SELECT DISTINCT `Highest level of education` FROM `education` ORDER BY `Highest level of education`";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $selected = ($row["Highest level of education"] == $selectedLevel) ? 'selected' : '';
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
<input type=button value=Reset onclick="window.location.href='view_data.php';">
</div>


    <br>
    <a href="home.php">Home</a>
    <a href="reset_login.php">Logout</a>
        </form>

        

        <?php
        // Fetch and display data based on selected filters
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $year = $_POST['year'];
            $level = $_POST['level'];

            $sql = "SELECT * FROM `education` WHERE 1=1";
            if ($year != '') {
                $sql .= " AND `Year of completion` = '$year'";
            }
            if ($level != '') {
                $sql .= " AND `Highest level of education` = '$level'";
            }

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
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
                echo "No data found for the selected filters.";
            }
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
