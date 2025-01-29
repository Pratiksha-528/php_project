<?php
// Start the session
session_start();

// Destroy all session data to log the user out
session_unset();  // Clears the session variables
session_destroy();  // Destroys the session

// Redirect the user to the login page (reset_login.php)
header("Location: reset_login.php");
exit();
?>
