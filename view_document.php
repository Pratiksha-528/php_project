<?php
// Start the session if needed (for authentication or validation)
session_start();

// Ensure the employee ID is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "No employee ID provided.";
    exit();
}

$employee_id = $_GET['id'];

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "employee";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the documents for the given employee ID
$sql = "SELECT * FROM documents WHERE `Employee ID` = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employee_id);  // Bind the employee ID as an integer
$stmt->execute();
$result = $stmt->get_result();

// Check if there are documents
if ($result->num_rows > 0) {
    echo "<h2>Documents for Employee ID: " . htmlspecialchars($employee_id) . "</h2>";
    echo "<ul>";

    // Output each document
    while ($row = $result->fetch_assoc()) {
        echo "<li>";
        echo "Document: " . htmlspecialchars($row['filename']) . " - ";
        echo "<a href='" . htmlspecialchars($row['filepath']) . "' download>Download</a>";
        echo "</li>";
    }

    echo "</ul>";
} else {
    echo "No documents found for this employee.";
}

// Close the database connection
$stmt->close();
$conn->close();
?>

<!-- Back button -->
<div class="back">
    <input type="button" value="Back to Employee Profile" onclick="window.location.href='view_data.php?id=<?php echo $employee_id; ?>';">
</div>
