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
    <!-- Prevent the browser from caching the page -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

   
       
<script type="text/javascript">
        // Prevent the user from going back to the previous page
        window.history.forward();
        window.onload = function() {
            setTimeout(function() {
                window.history.forward();
            }, 0);
        };
    
</script>


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
    
    <link rel="stylesheet" href="style.css">

    <title>Document</title>

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
            color: brown;
        }

   .filter_container input {
    width: 100px;
    height: 30PX;
    padding: 5px;
    margin-right: 10px;
   }

        .edit input{
            
            background-color: black;
            color: white;
            padding: 10px;
            text-decoration: none;
            display: inline-block;

        
        
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

       

        .filter_container{
            
            display:block;
            
            justify-content: center;
            margin-bottom: 20px;
            margin-top: 20px;
            
        }

        .sidebar {
            width: 200px;
            background-color: black;
            color: white;
            
           display:block;
           
        }
        
        </style>
</head>
<body>
<div class="ADD"><a href="family.php">Add Family Details</a><br></div>
    <br><br>
    <div class="sidebar">
    <a href="home.php">Home</a><br>
    <a href="reset_login.php">Logout</a>

    </div>
    
   
    
    



    <?php
    // Server connection details
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "employee";  // Make sure your database is correct

    // Create connection
   

/*session_start();  // Start the session to check login status

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    echo "You need to <a href='reset_login.php'>login</a> first.";
    exit();
}

echo "Welcome, " . $_SESSION['username'] . "!<br><br>";*/

// Database connection
$conn = new mysqli("localhost", "root", "", "employee");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$selected_Employee_ID = isset($_POST['Employee ID']) ? $_POST['Employee ID'] : '';

?>

<h2>Apply Filters</h2>
<form action="view_family.php" method="POST">
    <label for="Employee_ID">Employee ID</label>
    <select name="Employee_ID" id="Employee_ID">
        <option value="">Select Employee ID</option>
        <?php
        $sql = "SELECT DISTINCT `Employee ID` FROM `family` ORDER BY `Employee ID` ASC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $selected = ($row["Employee ID"] == $selected_Employee_ID) ? 'selected' : '';
                echo "<option value='" . $row["Employee ID"] . "' $selected>" . $row["Employee ID"] . "</option>";
            }
        } else {
            echo "<option value=''>No data available</option>";
        }
        ?>
    </select>
    <br><br>


       
       <div class="filter_container">
<input type="submit" value="Apply Filter">
    <input type=button value=Reset onclick="window.location.href='view_family.php';">
</div>

</form>
    <br>

<?php
    // Check if form is submitted
    //If an Employee ID is selected, fetch and display the family details
    // No need for real_escape_string, use prepared statement
        // Prepared statement to prevent SQL injection
        
        // Check if form is submitted and Employee ID is selected
        if (isset($_POST['Employee_ID'])) {
            $selected_Employee_ID = $_POST['Employee_ID'];
        
            // Prepared statement to prevent SQL injection
            $sql = "SELECT * FROM `family` WHERE `Employee ID` = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $selected_Employee_ID);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                // Dynamically generate the table headers using column names
                $columns = $result->fetch_fields(); // Fetch column metadata
        
                // Start the table
                echo "<table border='1'><tr>";
        
                // Loop through column names and create table headers
                foreach ($columns as $column) {
                    echo "<th>" . htmlspecialchars($column->name) . "</th>";
                }
        
                // Add Actions column if needed (conditionally based on role)
                if ($role != 'view') {
                    echo "<th>Actions</th>";
                }
        
                echo "</tr>";
        
                // Loop through the result and display each row's data
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    foreach ($columns as $column) {
                        $columnName = $column->name;
                        echo "<td>" . htmlspecialchars($row[$columnName]) . "</td>";
                    }
        
                    // Conditionally display action buttons based on user role
                    if ($role != 'view') {
                        echo "<td>";
        
                        if ($role == 'admin' || $role == 'edit_user') {
                            echo "<a href='edit_family.php?id=" . urlencode($row["Employee ID"]) . "'>Update</a> | ";
                        }
                        if ($role == 'admin' || $role == 'delete_user') {
                            echo "<a href='delete_family.php?id=" . urlencode($row["Employee ID"]) . "' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a>";
                        }
        
                        echo "</td>";
                    }
        
                    echo "</tr>";
                }
        
                echo "</table>";
            } else {
                echo "No results found.";
            }
        } else {
            // Fetch all family details if no Employee ID is selected
            $sql = "SELECT * FROM family"; // Fetch all columns for the family table
            $result = $conn->query($sql);
        
            if ($result->num_rows > 0) {
                // Dynamically generate the table headers using column names
                $columns = $result->fetch_fields(); // Fetch column metadata
        
                // Start the table
                echo "<table border='1'><tr>";
        
                // Loop through column names and create table headers
                foreach ($columns as $column) {
                    echo "<th>" . htmlspecialchars($column->name) . "</th>";
                }
        
                // Add Actions column if needed (conditionally based on role)
                if ($role != 'view') {
                    echo "<th>Actions</th>";
                }
        
                echo "</tr>";
        
                // Loop through the result and display each row's data
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    foreach ($columns as $column) {
                        $columnName = $column->name;
                        echo "<td>" . htmlspecialchars($row[$columnName]) . "</td>";
                    }
        
                    // Conditionally display action buttons based on user role
                    if ($role != 'view') {
                        echo "<td>";
        
                        if ($role == 'admin' || $role == 'edit_user') {
                            echo "<a href='edit_family.php?id=" . urlencode($row["Employee ID"]) . "'>Update</a> | ";
                        }
                        if ($role == 'admin' || $role == 'delete_user') {
                            echo "<a href='delete_family.php?id=" . urlencode($row["Employee ID"]) . "' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a>";
                        }
        
                        echo "</td>";
                    }
        
                    echo "</tr>";
                }
        
                echo "</table>";
            } else {
                echo "No data found.";
            }
        }
        
        $conn->close();
        ?>
        