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
        /* Styling for the 'Add education details' */
        a {
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
        
        /*.ADD{
            background-color: purple    ;
        }


        /*.edit{
            background-color: green;
        }

        .delete{
            background-color: red;
            color: white;
        }*/
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


</style>
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
</head>
<body>
    <div class="ADD"><a href="reset.php">Add Education Details</a><br></div>
    <div class="home"><a href="home.php">Home</a></div>
    <div class="logout"><a href="reset_login.php">Logout</a><br></div>
    
    <br>
    

    <?php
    // Server connection details
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "employee";  // Ensure your database name is correct

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query to get education details from the database
    $sql = "SELECT `Employee ID` ,`Employee Name`, `Highest level of education`, `Year of completion`, `Employee ID` FROM education";
    $result = $conn->query($sql);

    // Check if any records are found
    if ($result->num_rows > 0) {
        // Display the results in a table
        echo "<table>
                <tr>
                <TH> Employee Id</TH>
                    <th>Employee Name</th>
                    <th>Highest Level of Education</th>
                    <th>Year of Completion</th>
                    <th>Operations</th>
                </tr>";

        // Loop through and display each row
        while ($row = $result->fetch_assoc()) {
            // Escaping HTML special characters to prevent XSS
            $employeeName = htmlspecialchars($row["Employee Name"]);
            $educationLevel = htmlspecialchars($row["Highest level of education"]);
            $completionYear = htmlspecialchars($row["Year of completion"]);
            $employeeId = htmlspecialchars($row["Employee ID"]);

            echo "<tr>
            <td>$employeeId</td>  <!-- Display the employee ID -->
                    <td>$employeeName</td>
                    <td>$educationLevel</td>
                    <td>$completionYear</td>
                    <td>
                      <div class=edit>  <a href='edit_education.php?id=$employeeId'>Update</a> </div>
                        <div class=delete><a href='delete_education.php?id=$employeeId' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a></div>
                    </td>
                  </tr>";
        }

        echo "</table>";
    } else {
        echo "No data found.";
    }

    // Close the database connection
    $conn->close();
    ?>
</body>
</html>
