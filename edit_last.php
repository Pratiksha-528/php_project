<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Edit Education Details</title>
    <style type ="text/css">
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
        $Employer_name = $_POST['Employer_name'];
        $Immediate_superior_name=$_POST['Immediate_superior_name']; 
        $Email_id = $_POST['Email_id'];
        $Date_of_joining = $_POST['Date_of_joining'];
        $Date_of_termination = $_POST['Date_of_termination'];
        $employeeId= $_POST['Employee_ID'];


        // SQL query to update the education details
        $update_sql = "UPDATE   `last employment` 
                       SET `Employer name` = ?, `Immediate_superior_name`=?, `Email id`=?, `Date of joining`=?, `Date of termination`=?
                       WHERE `Employee ID` = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("sssssi", $Employer_name, $Immediate_superior_name, $Email_id, $Date_of_joining, $Date_of_termination,$employeeId);

        if ($update_stmt->execute()) {
            echo "Record updated successfully!";
            // Redirect to the view_data.php page after update
            echo "<meta http-equiv='refresh' content='0;url=http://localhost/myproject/reset/view_last.php'>";
        } else {
            echo "Error updating record: " . $conn->error;
        }

        // Close the prepared statement
        $update_stmt->close();
    }

    // Prepare the SQ   L query to fetch the current employee details
    $sql = "SELECT * FROM `last_
    employment` WHERE `Employee ID` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $id,$documentPath);  // Bind the ID parameter as an integer
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Form for editing
        echo "<h2>Edit Education Details</h2>";
        echo "<form action='edit_last.php?id=" . $id . "' method='POST'>
                <div class='input_field'>
                    <label for='Employer_Name'>Employer Name:</label>
                    <input type='text' name='Employer_name' value='" . htmlspecialchars($row['Employer name']) . "' required><br><br>

                    <label for='Immediate_superior_name'>ImmediateSuperior Name:</label>
        <input type='text' name='Immediate_superior_name' value=' ".htmlspecialchars($row["Immediate_superior_name"]) ." ' required><br><br>


         <label for='Email_id'>Email ID:</label>
         <input type='text' name='Email_id' value='".htmlspecialchars($row["Email id"]) ."' required><br><br>

         <label for='Date_of_joining'>Date of Joining:</label>
         <input type='date' name='Date of joining' value='".htmlspecialchars($row["Date of joining"]) ."' required><br><br>

         <label for='Date_of_termination'>Date of Termination:</label>
         <input type='date' name='Date of termination' value='".htmlspecialchars($row["Date of termination"]) ."' required><br><br>

         <input type='hidden' name='Employee_ID' value='". htmlspecialchars($row['Employee ID']) . "'>
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
      <div class="back"><input type=button value='Back to view data 'onclick="window.location.href='view_last.php';"></div>
</body>
</html>
