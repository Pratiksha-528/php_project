
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
    
</body>
</html>
    <?php
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "employee";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the selected values from the form
        $selected_year = $_POST['year'];
        $selected_high = $_POST['high'];

        // SQL query to select data based on user input and sort it
        $sql = "SELECT `Employee Name`, `Highest level of education`, `Year of completion` 
                FROM education 
                WHERE `Year of completion` = '$selected_year' 
                AND `Highest level of education` = '$selected_high' 
                ORDER BY `Year of completion` DESC";  // Sorting by Year of completion (Descending)

        // Execute the query
        $result = $conn->query($sql);

        // Check if there are results
        if ($result->num_rows > 0) {
            // Output the data in a table
            echo "<table border='1'><tr><th>Employee Name</th><th>Highest level of education</th><th>Year of completion</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["Employee Name"] . "</td><td>" . $row["Highest level of education"] . "</td><td>" . $row["Year of completion"] . "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "No results found for the selected year and education level.";
        }
    } else {
        // If the form is not submitted, show all data sorted by Year of completion (Descending)
        $sql = "SELECT `Employee Name`, `Highest level of education`, `Year of completion` FROM education ORDER BY `Year of completion` DESC";
        $result = $conn->query($sql);

        // Check if there are any results
        if ($result->num_rows > 0) {
            // Output the data in a table
            echo "<table border='1'><tr><th>Employee Name</th><th>Highest level of education</th><th>Year of completion</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["Employee Name"] . "</td><td>" . $row["Highest level of education"] . "</td><td>" . $row["Year of completion"] . "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "No results found.";
        }
    }

    // Close the database connection
    $conn->close();
    ?>
</body>
</html>
