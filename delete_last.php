<?php
// Get the id of the record to delete
$id = $_GET['id'];  // Retrieve the EmployeeID from the query string

// Connect to the database
$conn = new mysqli("localhost", "root", "", "employee");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare the SQL query using a prepared statement
$sql = "DELETE FROM `last employment` WHERE `Employee id` = ?";
$stmt = $conn->prepare($sql);

// Bind the id parameter (assuming it's an integer)
$stmt->bind_param("i", $id);

// Execute the query
if ($stmt->execute()) {
    echo "<script>alert('Record deleted');</script>";
} 
?>

<meta http-equiv="refresh" content="0;url=http://localhost/myproject/reset/view_last.php">';
else {
    echo "Error deleting record: " . $stmt->error;
}

// Close the prepared statement and database connection
$stmt->close();
$conn->close();
?>

<a href="view_last.php">Back to Table</a>

