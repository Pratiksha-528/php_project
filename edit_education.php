<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Edit Education Details</title>
    <style type="text/css">
        .input_field {
            margin-bottom: 10px;
            color: black;
        }
        .back input {
            margin-top: 10px;
            color: white;
            background-color: black;
        }
        .back {
            margin-top: 150px;
        }
        #update input {
            margin-top: 10px;
            color: black;
            border: 3px solid black;
            height: 30px;
            width: 80px;
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
        $employee_name = $_POST['employee_name'];
        $education = $_POST['education'];
        $year = $_POST['year'];
        $other_course = $_POST['other_course'];
        $institute_name = $_POST['institute_name'];
        $course_completion = $_POST['course_completion'];
        $employee_id = $_POST['Employee_ID'];

        // Validate required fields
        if (empty($employee_name) || empty($education) || empty($year) || empty($institute_name) || empty($course_completion)) {
            echo "Please fill in all required fields.";
            exit();
        }

        // SQL query to update the education details (no changes here)
        $update_sql = "UPDATE education 
                       SET `Employee Name` = ?, `Highest level of education` = ?, `Year of completion` = ?, `Other course/certification` = ?, 
                           `Institute name` = ?, `Course completion` = ? 
                       WHERE `Employee ID` = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ssssssi", $employee_name, $education, $year, $other_course, $institute_name, $course_completion, $employee_id);

        if ($update_stmt->execute()) {
            echo "Record updated successfully!<br>";

            // Handle file uploads for multiple files
            if (isset($_FILES['files']) && count($_FILES['files']['name']) > 0) {
                $upload_dir = 'uploads/';  // Directory to store uploaded files
                
                // Ensure upload directory exists
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                // Process each uploaded file
                foreach ($_FILES['files']['name'] as $index => $filename) {
                    if ($_FILES['files']['error'][$index] == UPLOAD_ERR_OK) {
                        // File type validation (allow only PDFs, DOCX, DOC files)
                        $allowed_extensions = ['pdf', 'docx', 'doc','jpg', 'jpeg','png'];
                        $file_extension = pathinfo($filename, PATHINFO_EXTENSION);
                        if (!in_array(strtolower($file_extension), $allowed_extensions)) {
                            echo "Invalid file type: $filename.<br>";
                            continue;
                        }

                        // File size validation (limit to 5MB)
                        if ($_FILES['files']['size'][$index] > 5000000) {
                            echo "File size exceeds the limit of 5MB: $filename.<br>";
                            continue;
                        }

                        $file_tmp_name = $_FILES['files']['tmp_name'][$index];
                        $file_name = time() . "_" . preg_replace("/[^a-zA-Z0-9\-_\.]/", "", basename($filename));
                        $filepath = $upload_dir . $file_name;

                        if (move_uploaded_file($file_tmp_name, $filepath)) {
                            // Store file path in the uploads table
                            $insert_sql = "INSERT INTO uploads (employee_id, file_path) VALUES (?, ?)";
                            $insert_stmt = $conn->prepare($insert_sql);
                            $insert_stmt->bind_param("is", $employee_id, $filepath);

                            if ($insert_stmt->execute()) {
                                echo "File uploaded successfully: $filename<br>";
                            } else {
                                echo "Error uploading file: $filename<br>";
                            }
                            $insert_stmt->close();
                        } else {
                            echo "Error moving file: $filename<br>";
                        }
                    }
                }
            }

            // Redirect to the view_data.php page after update
            header("Location: view_data.php?id=" . $id);
            exit();
        } else {
            echo "Error updating record: " . $conn->error;
        }

        // Close the prepared statement
        $update_stmt->close();
    }

    // Prepare the SQL query to fetch the current employee details
    $sql = "SELECT * FROM education WHERE `Employee ID` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);  // Bind the ID parameter as an integer
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Form for editing
        echo "<h2>Edit Education Details</h2>";
        echo "<form action='edit_education.php?id=" . $id . "' method='POST' enctype='multipart/form-data'>
                <div class='input_field'>
                    <label for='employee_name'>Employee Name:</label>
                    <input type='text' name='employee_name' value='" . htmlspecialchars($row['Employee Name']) . "' required><br><br>

                    <label for='education'>Highest Level of Education:</label>
                    <input type='text' name='education' value='" . htmlspecialchars($row['Highest level of education']) . "' required><br><br>

                    <label for='year'>Year of Completion:</label>
                    <input type='text' name='year' value='" . htmlspecialchars($row['Year of completion']) . "' required><br><br>

                    <label for='other_course'>Other course/certification:</label>
                    <input type='text' name='other_course' value='" . htmlspecialchars($row['Other course/certification']) . "'><br><br>

                    <label for='institute_name'>Institute name:</label>
                    <input type='text' name='institute_name' value='" . htmlspecialchars($row['Institute name']) . "' required><br><br>

                    <label for='course_completion'>Course Completion Date:</label>
                    <input type='date' pattern='\d{2}-\d{2}-\d{4}' name='course_completion' value='" . htmlspecialchars($row['Course completion']) . "' required><br><br>

                    
                    
<div id='update'>
                    <input type='hidden' name='Employee_ID' value='" . htmlspecialchars($row['Employee ID']) . "'>
                    <input type='submit' value='Update'>
                    </div>
                </div>
              </form>";
       

        
    } else {
        echo "Record not found.";
    }

    // Close the prepared statement and database connection
    $stmt->close();
    $conn->close();
    ?>

    <div class="back"><input type="button" value="Back to view data" onclick="window.location.href='view_data.php?id=<?php echo $id; ?>';"></div>
</body>
</html>
