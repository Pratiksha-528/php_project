<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Employee Education Form</title>
</head>
<body>
    <h2>Employee Education Form</h2>
    <form action="resett.php" method="post" enctype="multipart/form-data">
        
        <label for="employee_name">Employee Name</label>
        <input type="text" id="employee_name" name="employee_name" required><br><br>

        <label for="high">Highest Level of Education</label>
        <input type="text" id="high" name="high" required><br><br>

        <label for="year">Year of Graduation</label>
        <input type="text" id="year" name="year" required><br><br>

        <label for="other_course">Other course/certification</label>
        <input type="text" id="other_course" name="other_course"><br><br>

        <label for="institute_name">Institute Name</label>
        <input type="text" id="institute_name" name="institute_name" required><br><br>

        <label for="course_completion">Course Completion Date:</label>
        <input type="date" id="course_completion" name="course_completion" required><br><br>

        <!-- File Upload -->
        <label for="file">Upload Document:</label>
        <input type="file" name="file" id="file" required><br><br>

        <input type="reset" name="reset" value="Reset" />
        <input type="submit" name="submit" value="Submit" />
    </form>
    <a href='view_data.php'>See education details </a>

    <?php
    // Server connection details
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "employee";  // Ensure the database is correct

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Sanitize user inputs to prevent SQL Injection
        $employee_name = $conn->real_escape_string(trim($_POST['employee_name']));
        $high = $conn->real_escape_string(trim($_POST['high']));
        $year = $conn->real_escape_string(trim($_POST['year']));
        $other_course = $conn->real_escape_string(trim($_POST['other_course']));
        $institute_name = $conn->real_escape_string(trim($_POST['institute_name']));
        $course_completion = $conn->real_escape_string(trim($_POST['course_completion']));
        $file = $conn->real_escape_string(trim($_FILES['file']['name']));
        
        // File upload handling
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["file"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate if file is uploaded correctly
        if ($_FILES["file"]["error"] != UPLOAD_ERR_OK) {
            echo "Error uploading file.<br>";
            $uploadOk = 0;
        }

        // Check file size (Limit to 5MB)
        if ($_FILES["file"]["size"] > 5000000) {
            echo "Sorry, your file is too large.<br>";
            $uploadOk = 0;
        }

        // Allow only specific file formats (pdf, docx, jpg, png)
        if (!in_array($imageFileType, ['pdf', 'docx', 'jpg', 'png'])) {
            echo "Sorry, only PDF, DOCX, JPG, and PNG files are allowed.<br>";
            $uploadOk = 0;
        }

        

        // Proceed if all validations are successful
        if ($uploadOk == 1) {
            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                // SQL query with prepared statements to prevent SQL injection
                $query = "INSERT INTO education (`Employee Name`, `Highest level of education`, `Year of completion`, `Other course/certification`, `Institute name`, `Course completion`, `Upload Document`) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("sssssss", $employee_name, $high, $year, $other_course, $institute_name, $course_completion, $target_file);

                // Execute the query
                if ($stmt->execute()) {
                    echo "New education details added successfully. <a href='view_data.php'>See last education details (updated database)</a><br>";
                } else {
                    echo "Error: " . $stmt->error . "<br>";
                }

                // Close the prepared statement
                $stmt->close();
            } else {
                echo "Sorry, there was an error uploading your file.<br>";
            }
        }

        // Close connection
        $conn->close();
    }
    ?>
</body>
</html>
