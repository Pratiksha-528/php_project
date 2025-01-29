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

        .filter_container{
            
            display:block;
            
            justify-content: center;
            margin-bottom: 20px;
            margin-top: 20px;
            
        }

        .sidebar {
            width: 200px;
            background-color: #333;
            color: white;
            
           display:block;
           
        }
        </style>
</head>
<body>
<div class="ADD"><a href="family.php">Add Family Details</a><br></div>
    <br><br>
    <div class="sidebar">
    <a href="home.php">Home</a>
    <a href="reset_login.php">Logout</a>

    </div>
    
   
    
    <br><br>



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
    if (!empty($selected_Employee_ID)) {
        $sql = "SELECT * FROM `family` WHERE `Employee ID` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $selected_Employee_ID);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            echo "<h2>Family Details for Employee ID: " . htmlspecialchars($selected_Employee_ID) . "</h2>";
            echo "<table border='1'>
                <tr>
                    <th>Employee ID</th>
                   <th>Sr_no</th>
                    <th>Family member name</th>
                    <th>Family relation</th>
                    <th>mobile number</th>
                    <th>Actions</th>
                </tr>";

        
        // Loop through the result and display each employee's details
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
    } 
}else {

    if (!empty($selected_Employee_ID)) { 
$sql = "SELECT `Employee ID`, `Sr_no`  , `Family member name` , `Family relation`, `mobile number`  FROM family";
$stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $selected_Employee_ID);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
                // Start the table and define column headers
                echo "<table border='1'>
                <tr>
                <th>Employee ID</th>
                <th>Sr_no</th>           
                <th>Family member name</th>
                <th>Family relation</th>
                <th>mobile number</th>
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
                echo "No data found.";
            }
        }
    }
        // Fetch and display data based on selected filters




                   
                

$conn->close();
?>
    
</body>
</html>





