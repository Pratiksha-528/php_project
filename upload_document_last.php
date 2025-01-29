<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        /* General Body Styles */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

/* Header Styles */
h2 {
    color: #333;
    text-align: center;
    margin: 20px 0;
}

/* Form Styles */
form {
    background-color: #fff;
    padding: 20px;
    margin: 20px auto;
    width: 50%;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

/*form label {
    font-size: 16px;
    margin-bottom: 10px;
    display: block;
}*/

form input[type="file"] {
    font-size: 18px;
    padding: 5px;
    width: 100%;
    margin-bottom: 11px;
}

form input[type="submit"] {
    background-color:rgb(5, 30, 138);
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

form input[type="submit"]:hover {
    background-color:rgb(46, 75, 202);
}

/* Document List Styles */
ul#document-list {
    list-style-type: none;
    padding: 0;
    margin: 0;
    max-width: 70%;
    margin: 20px auto;
    
   
}

ul#document-list li {
    background-color: #fff;
    margin: 10px 0;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    
display: flex;
    justify-content: space-between;
    align-items: center;
}

ul#document-list li a {
    text-decoration: none;
    color:rgb(76, 79, 175);
    font-weight: bold;
    margin-right: 10px;
}

ul#document-list li a:hover {
    text-decoration: underline;
}

ul#document-list li button {
    background-color:rgb(235, 138, 10);
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 4px;
    cursor: pointer;
    margin-right: 10px;
}

ul#document-list li button:hover {
    background-color: #FF3333;
}

/* Back Button Styles */
div.back {
    text-align: center;
    margin-top: 20px;
}

div.back input[type="button"] {
    background-color: #2196F3;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

div.back input[type="button"]:hover {
    background-color: #0b7dda;
}

    </style>
</head>
<body>
    
</body>
</html>
<?php
// Start the session for authentication
session_start();

// Ensure the employee ID is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "No employee ID provided.";
    exit();
}

$employee_id = $_GET['id'];

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "employee";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle multiple file uploads
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['documents'])) {
    $files = $_FILES['documents']; // For multiple files

    // Loop through each file and handle the upload process
    for ($i = 0; $i < count($files['name']); $i++) {
        $file = [
            'name' => $files['name'][$i],
            'type' => $files['type'][$i],
            'tmp_name' => $files['tmp_name'][$i],
            'error' => $files['error'][$i],
            'size' => $files['size'][$i]
        ];

        // Validate file upload
        if ($file['error'] == UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/';  // Directory to store uploaded files
            
            // Check if the uploads directory exists, if not, create it
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            // Validate file extension (allow only specific types, e.g., PDF, DOCX, DOC)
            $allowed_extensions = ['pdf', 'docx', 'doc', 'jpg', 'jpeg', 'png'];
            $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);

            if (!in_array(strtolower($file_extension), $allowed_extensions)) {
                echo "Invalid file type for file '$file[name]'. Only PDF, DOCX, DOC, JPG, JPEG, and PNG files are allowed.<br>";
                continue;
            }

            // Validate file size (e.g., 5MB limit)
            if ($file['size'] > 5000000) {  // 5MB size limit
                echo "File size for '$file[name]' exceeds the limit of 5MB.<br>";
                continue;
            }

            // Create a unique filename to prevent conflicts (e.g., by appending timestamp)
            $filename = time() . "_" . basename($file['name']);
            $filepath = $upload_dir . $filename;

            // Move the uploaded file to the upload directory
            if (move_uploaded_file($file['tmp_name'], $filepath)) {
                // Insert the document record into the database
                $sql = "INSERT INTO  `last_documents` (`Employee ID`, `filename`, `filepath`, `uploaded_at`) VALUES (?, ?, ?, NOW())";
                $stmt = $conn->prepare($sql);
                $uploaded_at = date("Y-m-d H:i:s");  // Get the current date and time
                $stmt->bind_param("iss", $employee_id, $filename, $filepath);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    echo "Document '$filename' uploaded successfully!<br>";
                } else {
                    echo "Error inserting document record for '$filename'.<br>";
                }
            } else {
                echo "Error uploading file '$filename'.<br>";
            }
        } else {
            echo "Error with file upload for '$file[name]'.<br>";
        }
    }
}

// Handle document deletion
if (isset($_GET['delete'])) {
    $file_path = $_GET['delete'];

    // Delete the file from the server
    if (file_exists($file_path)) {
        unlink($file_path);
    }

    // Delete the file record from the database
    $sql = "DELETE FROM `last_documents`  WHERE filepath = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $file_path);
    $stmt->execute();

    echo "Document deleted successfully.";
}

?>

<!-- HTML for uploading documents -->
<h2>Upload Documents for Employee ID: <?php echo htmlspecialchars($employee_id); ?></h2>
<form action="upload_document_last.php?id=<?php echo $employee_id; ?>" method="POST" enctype="multipart/form-data">
    <label for="documents">Select Documents (you can select multiple):</label>
    <input type="file" name="documents[]" id="documents" multiple required>
    <br><br>
    <input type="submit" value="Upload">
</form>

<!-- Display uploaded documents -->
<h2>Uploaded Documents</h2>
<ul id="document-list">
    <?php
    // Fetch documents for the employee
    $sql = "SELECT * FROM `last_documents` WHERE `Employee ID` = ?"; #('employment_history_id')
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<li>";
            echo "Document name: " . htmlspecialchars($row['filename']) . " - ";'<br>';
           
            echo  "Uploaded at: ". htmlspecialchars($row['uploaded_at']);

            echo "<a href='" . htmlspecialchars($row['filepath']) . "' download>Download</a>";'<br>';
            echo " <button onclick='printDocument(\"" . htmlspecialchars($row['filepath']) . "\")'>Print Document</button>";'<br>';
            echo " <button onclick='deleteDocument(\"" . htmlspecialchars($row['filepath']) . "\")'>Delete</button>";
            echo "</li>";
        }
    } else {
        echo "<li>No documents found for this employee.</li>";
    }
    ?>
</ul>

<!-- JavaScript for deleting and printing documents -->
<script>
    function printDocument(filepath) {
        var printWindow = window.open(filepath, '_blank');
        printWindow.onload = function() {
            printWindow.print();
        };
    }

    function deleteDocument(filePath) {
        if (confirm("Are you sure you want to delete this document?")) {
            window.location.href = "upload_document_last.php?id=<?php echo $employee_id; ?>&delete=" + encodeURIComponent(filePath);
        }
    }
</script>

<!-- Back button -->
<br><br>
<div class="back">
    <input type="button" value="Back to Employee Profile" onclick="window.location.href='view_last.php?id=<?php echo $employee_id; ?>';">
</div>
