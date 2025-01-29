<?php
require 'database.php';  // Include database connection

// Check if the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
    // Check if the email exists in the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // If the email exists, proceed with generating the reset token
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Generate a unique reset token (you can use a random string generator)
        $reset_token = bin2hex(random_bytes(50));  // 50 bytes = 100 characters
        
        // Set the expiration time (1 hour from now)
        $expiration_time = date("Y-m-d H:i:s", strtotime("+1 hour"));
        
        // Store the reset token and expiration time in the database
        $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_expiration = ? WHERE email = ?");
        $stmt->bind_param("sss", $reset_token, $expiration_time, $email);
        $stmt->execute();
        
        // Send the reset link to the user's email
        $reset_link = "http://yourdomain.com/reset_password.php?token=" . $reset_token;
        
        // Send email to the user with the reset link
        mail($email, "Password Reset Request", "Click this link to reset your password: " . $reset_link);
        
        echo "Password reset link has been sent to your email.";
    } else {
        echo "No account found with that email address.";
    }

    $stmt->close();
    $conn->close();

?>

<!-- HTML Form for Password Reset Request -->
<form action="password_reset_request.php" method="POST">
    <label for="email">Email:</label>
    <input type="email" name="email" required><br><br>
    <input type="submit" value="Request Password Reset">
</form>
