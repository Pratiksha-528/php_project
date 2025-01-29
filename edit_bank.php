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
        $employeeid = $_POST['Employee_ID'];  // Correct key to match the form input
        $Employee_Name = $_POST['Employee_Name'];  // Correct key to match the form input
        $Bank_Account_Name = $_POST['Bank_Account_Holder_Name'];  // Correct key to match the form input
        $Bank_Account_Number = $_POST['Bank_Account_Number'];  // Correct key to match the form input
        $Bank_Name = $_POST['Bank_Name'];  // Correct key to match the form input
        $IFSC_Code = $_POST['IFSC_Code'];  // Correct key to match the form input
        $Branch_address = $_POST['Branch_address'];  // Correct key to match the form input

        // SQL query to update the bank details
        $update_sql = "UPDATE bank 
                       SET `Employee Name` = ?, `Bank Account Holder Name` = ?, `Bank Account Number` = ?, `Bank Name` = ?, `IFSC Code` = ?, `Branch address` = ?
                       WHERE `Employee ID` = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ssssssi", $Employee_Name, $Bank_Account_Name, $Bank_Account_Number, $Bank_Name, $IFSC_Code, $Branch_address, $employeeid);

        if ($update_stmt->execute()) {
            echo "Record updated successfully!";
            // Redirect to the view_data.php page after update
            echo "<meta http-equiv='refresh' content='0;url=http://localhost/myproject/reset/view_bank.php'>";
        } else {
            echo "Error updating record: " . $conn->error;
        }

        // Close the prepared statement
        $update_stmt->close();
    }

    // Prepare the SQL query to fetch the current bank details
    $sql = "SELECT * FROM bank WHERE `Employee ID` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);  // Bind the ID parameter as an integer
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Form for editing
        echo "<h2>Edit Bank Details</h2>";
        echo "<form action='edit_bank.php?id=" . $id . "' method='POST'>
                <div class='input_field'>
                    <label for='employee_name'>Employee Name:</label>
                    <input type='text' name='Employee_Name' value='" . htmlspecialchars($row['Employee Name']) . "' required><br><br>

                    <label for='Bank_Account_Holder_Name'>Bank Account Holder Name:</label>
                    <input type='text' name='Bank_Account_Holder_Name' value='" . htmlspecialchars($row['Bank Account Holder Name']) . "' required><br><br>

                    <label for='Bank_Account_Number'>Bank Account Number:</label>
                    <input type='text' name='Bank_Account_Number' value='" . htmlspecialchars($row['Bank account number']) . "' required><br><br>

                    <label for='Bank_Name'>Bank Name:</label>
                    <input type='text' name='Bank_Name' value='" . htmlspecialchars($row['Bank Name']) . "' required><br><br>

                    <label for='IFSC_Code'>IFSC Code:</label>
                    <input type='text' name='IFSC_Code' value='" . htmlspecialchars($row['IFSC Code']) . "' required><br><br>

                    <label for='Branch_address'>Branch Address:</label>
                    <input type='text' name='Branch_address' value='" . htmlspecialchars($row['Branch address']) . "' required><br><br>

                    <input type='hidden' name='Employee_ID' value='" . htmlspecialchars($row['Employee ID']) . "'>
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
     <div class="back"><input type=button value='Back to view data 'onclick="window.location.href='view_bank.php';"></div>
</body>
</html>
