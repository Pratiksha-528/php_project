<?php
session_start();
require 'database.php';  // Database connection setup

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $answer1 = $_POST['answer1'];
    $answer2 = $_POST['answer2'];

    // Fetch the user's security answers from the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Check if the answers match
    if ($answer1 === $user['security_answer_1'] && $answer2 === $user['security_answer_2']) {
        // Answers are correct, proceed to password reset
        echo '<form action="reset_password.php" method="POST">';
        echo '<label for="password">Enter New Password:</label>';
        echo '<input type="password" name="password" required><br>';
        echo '<input type="hidden" name="username" value="' . $username . '">';
        echo '<input type="submit" value="Reset Password">';
        echo '</form>';
    } else {
        echo "Incorrect answers to security questions.";
    }

    $stmt->close();
}
?>
