<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Edit Family Details</title>
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
        $Family_member_name = htmlspecialchars($_POST['Family_member_name']);
        $Family_relation = htmlspecialchars($_POST['Family_relation']);
        $mobile_number = htmlspecialchars($_POST['mobile_number']);
        $Employee_ID = htmlspecialchars($_POST['Employee_ID']);  // Keep this as Employee_ID, not 'employee_id'

        // SQL query to update the family details
        $update_sql = "UPDATE family
                       SET `Family  member name`=?, `Family relation`=?, `mobile number`=?, `Employee ID`=?
                       WHERE `Employee ID` = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ssssi", $Family_member_name, $Family_relation, $mobile_number, $Employee_ID, $id);

        if ($update_stmt->execute()) {
            echo "Record updated successfully!";
            // Redirect to the view page after update
            echo "<meta http-equiv='refresh' content='0;url=http://localhost/myproject/reset/view_family.php'>";
        } else {
            echo "Error updating record: " . $conn->error;
        }

        // Close the prepared statement
        $update_stmt->close();
    }

    // Prepare the SQL query to fetch the current family details
    $sql = "SELECT * FROM family WHERE `Employee ID` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);  // Bind the ID parameter as an integer
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Form for editing
        echo "<h2>Edit Family Details</h2>";
        echo "<form action='edit_family.php?id=" . $id . "' method='POST'>
                <div class='input_field'>

                <label for='Employee ID'>Employee ID:</label>
                <input type='text' name='Employee_ID' value='" . htmlspecialchars($row['Employee ID']) . "' readonly><br><br>

                <label for='Family_member_name'>Family Member Name:</label>
                <input type='text' name='Family_member_name' value='" . htmlspecialchars($row['Family  member name']) . "' required><br><br>

                <label for='Family_relation'>Family Relation:</label>
                <input type='text' name='Family_relation' value='" . htmlspecialchars($row['Family relation']) . "' required><br><br>

                <label for='mobile_number'>Mobile Number:</label>
                <input type='text' name='mobile_number' value='" . htmlspecialchars($row['mobile number']) . "' required><br><br>

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
     <div class="back"><input type=button value='Back to view data 'onclick="window.location.href='view_family.php';"></div>
    
</body>
</html>
