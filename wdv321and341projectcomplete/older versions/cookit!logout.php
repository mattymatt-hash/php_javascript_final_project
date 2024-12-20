<?php
session_start(); // Start or resume the session

// Set the validUser session variable to false
$_SESSION['validUser'] = false;

// Clear all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect the user to the login page
header("Location: login.php");
exit; // Ensure no further code is executed
?>