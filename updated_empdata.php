<?php
// Prevent caching of the page to ensure that the page cannot be accessed via the back button
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Prevent Back Button</title>
    <script type="text/javascript">
        // Prevent the user from using the back button by pushing a state in history
        window.history.forward();

        // To prevent the user from going back in the browser's history stack
        window.onload = function() {
            window.history.pushState(null, null, window.location.href);
            window.onpopstate = function() {
                window.history.pushState(null, null, window.location.href);
            }
        }
    </script>

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
        a {
            text-decoration: none;
            color: white;
            background-color: black;
            display: inline-block;
            padding: 4px 8px;
        }
        a:hover {
            color: red;
        }
    </style>
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
    <form action="filters_empdata.php" method="POST">
        <label for="State">State:</label>
        <select name="State" id="State">
            <option value="Maharashtra">Maharashtra</option>
            <option value="Kerala">Kerala</option>
            <option value="Madhya Pradesh">Madhya Pradesh</option>
            <option value="Gujarat">Gujarat</option>
            <option value="Uttar Pradesh">Uttar Pradesh</option>
            <option value="Andhra Pradesh">Andhra Pradesh</option>
        </select>
        <br><br>
        <input type="submit" value="Apply Filter">
    </form>

    <a href="Home.php">Home</a><br><br>
    <a href="reset_login.php">Logout</a><br><br>

    <?php
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "employee";  // Ensure the database name is correct

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $selected_State = $_POST['State'];  // No need for real_escape_string, use prepared statement
        // Prepared statement to prevent SQL injection
        $sql = "SELECT `Employee ID`, `Employee Name`, `Present Address`, `Present Pincode`, 
                `Permanent Address`, `Permanent Area`, `Permanent Pincode`, `State`
                FROM empdata1
                WHERE `State` = ? 
                ORDER BY `Employee ID`";  // Sorted by Employee ID for readability

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $selected_State);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<table>
                    <tr>
                        <th>Employee ID</th>
                        <th>Employee Name</th>
                        <th>Present Address</th>
                        <th>Present Pincode</th>
                        <th>Permanent Address</th>
                        <th>Permanent Area</th>
                        <th>Permanent Pincode</th>
                        <th>State</th>
                        <th>Actions</th>
                    </tr>";
            while ($row = $result->fetch_assoc()) {
                $employeeId = $row["Employee ID"];  // Correct variable
                echo "<tr>
                        <td>" . htmlspecialchars($row["Employee ID"]) . "</td>
                        <td>" . htmlspecialchars($row["Employee Name"]) . "</td>
                        <td>" . htmlspecialchars($row["Present Address"]) . "</td>
                        <td>" . htmlspecialchars($row["Present Pincode"]) . "</td>
                        <td>" . htmlspecialchars($row["Permanent Address"]) . "</td>
                        <td>" . htmlspecialchars($row["Permanent Area"]) . "</td>
                        <td>" . htmlspecialchars($row["Permanent Pincode"]) . "</td>
                        <td>" . htmlspecialchars($row["State"]) . "</td>
                        <td>
                            <a href='edit_empdata.php?id=$employeeId'>Update</a> | 
                            <a href='delete_empdata.php?id=$employeeId' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a>
                        </td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "No results found.";
        }

        $stmt->close();

    } else {
        // Display all employees if no filter is applied
        $sql = "SELECT `Employee ID`, `Employee Name`, `Present Address`, `Present Pincode`, 
                `Permanent Address`, `Permanent Area`, `Permanent Pincode`, `State`
                FROM empdata1
                ORDER BY `Employee ID`";  // Sorted by Employee ID for readability

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table>
                    <tr>
                        <th>Employee ID</th>
                        <th>Employee Name</th>
                        <th>Present Address</th>
                        <th>Present Pincode</th>
                        <th>Permanent Address</th>
                        <th>Permanent Area</th>
                        <th>Permanent Pincode</th>
                        <th>State</th>
                        <th>Actions</th>
                    </tr>";

            while ($row = $result->fetch_assoc()) {
                $employeeId = $row["Employee ID"];
                echo "<tr>
                        <td>" . $row["Employee ID"] . "</td>
                        <td>" . htmlspecialchars($row["Employee Name"]) . "</td>
                        <td>" . htmlspecialchars($row["Present Address"]) . "</td>
                        <td>" . htmlspecialchars($row["Present Pincode"]) . "</td>
                        <td>" . htmlspecialchars($row["Permanent Address"]) . "</td>
                        <td>" . htmlspecialchars($row["Permanent Area"]) . "</td>
                        <td>" . htmlspecialchars($row["Permanent Pincode"]) . "</td>
                        <td>" . htmlspecialchars($row["State"]) . "</td>
                        <td>
                            <a href='edit_empdata.php?id=$employeeId'>Update</a> | 
                            <a href='delete_empdata.php?id=$employeeId' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a>
                        </td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "No results found.";
        }
    }

    $conn->close();
    ?>
</body>
</html>
