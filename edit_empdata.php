<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Edit Employee Details</title>
    <style type="text/css">
         .sidebar {
            width: 200px;
            background-color:BLACK;
            color: white;
            justify-content: CENTER;
            align-items: CENTER;
            display: inline-block;
           
        }
        
        .sidebar input{
            color: white;
        }
        .input_field {
            margin-bottom: 10px;
            color: black;
        }

        .back input{
            margin-top: 10px;
            color: white;
            background-color: black;
        }

        .back{
            margin-top: 150px;
        }
        </style>
</head>
<body>
    <?php
    // Ensure the 'id' parameter is set in the URL
    if (!isset($_GET['id'])) {
        echo "No employee ID provided.";
        exit();  // Exit script if 'id' is not passed
    }

    // Get the id of the record to edit
    $id = $_GET['id'];  // Retrieve the ID from the query string

    // Connect to the database
    $conn = new mysqli("localhost", "root", "", "employee");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the form was submitted to update the record
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get updated values from the form
        $employeeName = htmlspecialchars($_POST["Employee_Name"]);
        $presentAddress = htmlspecialchars($_POST["Present_Address"]);
        $presentPincode = htmlspecialchars($_POST["Present_Pincode"]);
        $permanentAddress = htmlspecialchars($_POST["Permanent_Address"]);
        $permanentArea = htmlspecialchars($_POST["Permanent_Area"]);
        $permanentPincode = htmlspecialchars($_POST["Permanent_Pincode"]);
        $state = htmlspecialchars($_POST["State"]);

        // Check if all the fields are filled out
        if (empty($employeeName) || empty($presentAddress) || empty($presentPincode) || empty($permanentAddress) || empty($permanentArea) || empty($permanentPincode) || empty($state)) {
            echo "<p style='color: red;'>All fields are required. Please fill them out.</p>";
        } else {
            // SQL query to update the employee details
            $update_sql = "UPDATE empdata1 
                           SET `Employee Name` = ?, `Present Address` = ?, `Present Pincode` = ?, 
                               `Permanent Address` = ?, `Permanent Area` = ?, `Permanent Pincode` = ?, `State` = ? 
                           WHERE `Employee ID` = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("sssssssi", $employeeName, $presentAddress, $presentPincode, 
                                     $permanentAddress, $permanentArea, $permanentPincode, $state, $id);

            if ($update_stmt->execute()) {
                echo "<p style='color: green;'>Record updated successfully!</p>";
                // Redirect to the view_empdata.php page after update
                echo "<meta http-equiv='refresh' content='0;url=emp.php'>";  // Redirect after 2 seconds
            } else {
                echo "<p style='color: red;'>Error updating record: " . $conn->error . "</p>";
            }

            // Close the prepared statement
            $update_stmt->close();
        }
    }

    // Prepare the SQL query to fetch the current employee details
    $sql = "SELECT * FROM empdata1 WHERE `Employee ID` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);  // Bind the ID parameter as an integer
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Form for editing
        echo "<h2>Edit Employee Details</h2>";
        echo "<form action='edit_empdata.php?id=" . $id . "' method='POST'>
                <div class='input_field'>
                    <label for='Employee_Name'>Employee Name:</label>
                    <input type='text' name='Employee_Name' id='Employee_Name' value='". $row['Employee Name']. "' required><br><br>
                    
                    <label for='Present_Address'>Present Address:</label>
                    <input type='text' name='Present_Address' id='Present_Address' value='". $row['Present address']. "' required><br><br>
                    
                    <label for='Present_Pincode'>Present Pincode:</label>
                    <input type='text' name='Present_Pincode' id='Present_Pincode' value='". $row['Present Pincode']. "' required pattern='\d{6}' title='Pincode should be a 6 digit number'><br><br>

                    <label for='Permanent_Address'>Permanent Address:</label>
                    <input type='text' name='Permanent_Address' id='Permanent_Address' value='". $row['Permanent address']. "' required><br><br>
                    
                    <label for='Permanent_Area'>Permanent Area:</label>
                    <input type='text' name='Permanent_Area' id='Permanent_Area' value='". $row['Permanent Area']. "' required><br><br>
                    
                    <label for='Permanent_Pincode'>Permanent Pincode:</label>
                    <input type='text' name='Permanent_Pincode' id='Permanent_Pincode' value='". $row['Permanent Pincode']. "' required pattern='\d{6}' title='Pincode should be a 6 digit number'><br><br>
                    
                    <label for='State'>State:</label>
                    <input type='text' name='State' id='State' value='" . $row['State']. "' required><br><br>

                    <input type='submit' value='Update'>
                </div>
              </form>";
    } else {
        echo "Record not found.";
    }

    // Close the prepared statement and database connection
    $stmt->close();
    $conn->close();
    ?>
<br><br><br><br><br><br> 
<!-- <div class="sidebar">
    <a href="home.php">Home</a>
    <a href="reset_login.php">Logout</a>  -->
    <div class="back"><input type=button value='Back to view data 'onclick="window.location.href='view_empdata.php';"></div>
    </div>
    
</body>
</html>
