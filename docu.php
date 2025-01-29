<?php
session_start(); // Start the session to check login status

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    echo "You need to <a href='reset_login.php'>login</a> first.";
    exit();
}

echo "Welcome, " . $_SESSION['username'] . "!<br><br>";

// Server connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "employee";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = "uploads/";
        $filename = basename($_FILES['file']['name']);
        $filepath = $uploadDir . $filename;

        // Ensure the uploads directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Move the uploaded file to the uploads directory
        if (move_uploaded_file($_FILES['file']['tmp_name'], $filepath)) {
            // Insert the file data into the database
            $stmt = $conn->prepare("INSERT INTO documents (filename, filepath) VALUES (?, ?)");
            $stmt->bind_param("ss", $filename, $filepath);  // Bind parameters correctly
            $stmt->execute();
            $stmt->close();
            echo "File uploaded successfully. <a href='view_data.php'>Go back</a>";
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "No file uploaded or an error occurred.";
    }
}

// Fetch and display uploaded documents
$sql = "SELECT * FROM documents ORDER BY uploaded_at DESC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload and View Documents</title>
    <style type="text/css">
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .container {
            margin: 20px;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        a {
            color: blue;
            text-decoration: none;
        }
        .upload-form {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- File upload form -->
        <h2>Upload Document</h2>
        <form class="upload-form" action="index.php" method="POST" enctype="multipart/form-data">
            <label for="file">Select Document:</label>
            <input type="file" name="file" id="file" required><br><br>
            <button type="submit">Upload</button>
        </form>

        <!-- Display uploaded documents -->
        <h2>Uploaded Documents</h2>
        <?php
        if ($result->num_rows > 0) {
            echo "<table><tr><th>Filename</th><th>Uploaded At</th><th>Action</th><th>Preview</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row['filename'] . "</td>
                        <td>" . $row['uploaded_at'] . "</td>
                        <td><a href='download.php?file=" . urlencode($row['filepath']) . "'>Download</a></td>
                        <td>";

                // Preview images or PDF files
                $fileExtension = strtolower(pathinfo($row['filename'], PATHINFO_EXTENSION));
                if ($fileExtension === "pdf") {
                    echo "<a href='" . $row['filepath'] . "' target='_blank'>View PDF</a>";
                } elseif (in_array($fileExtension, ["jpg", "jpeg", "png", "gif"])) {
                    echo "<img src='" . $row['filepath'] . "' alt='Image' style='width: 100px; height: auto;'>";
                } else {
                    echo "No preview available.";
                }

                echo "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "No documents uploaded yet.";
        }

        $conn->close();
        ?>
    </div>
    <a href="view_data.php">View database</a>
</body>
</html>
