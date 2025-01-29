<?php
session_start();
require 'database.php';  // Your database connection setup

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);

    // Check if the username exists in the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    
        
        // Display security questions (assuming they are stored in the database)
        echo '<form action="reset_security_answer.php" method="POST">';
        echo '<label for="answer1">Security Question 1: ' . $user['security_question_1'] . '</label>';
        echo '<input type="text" name="answer1" required><br>';
        echo '<label for="answer2">Security Question 2: ' . $user['security_question_2'] . '</label>';
        echo '<input type="text" name="answer2" required><br>';
        echo '<input type="hidden" name="username" value="' . $username . '">';
        echo '<input type="submit" value="Submit Answers">';
        echo '</form>';
    } else {
        echo "No account found with that username.";
    }

    

    

    $stmt->close();
}
?>
