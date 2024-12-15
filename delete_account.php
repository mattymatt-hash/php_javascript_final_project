<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    // If the user is not logged in, redirect to the login page
    header("Location: cookit!index.php");
    exit();
}

// Get the username from the session
$username = $_SESSION['user'];

// Database connection
require 'mattconnect.php';

// SQL query to delete the user from the database
$sql = "DELETE FROM wdv341_user WHERE user_username = :username";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':username', $username);

try {
    // Execute the query to delete the user
    if ($stmt->execute()) {
        // Log the user out by destroying the session
        session_unset();
        session_destroy();
        // Redirect to login page with a message that the account was deleted
        header("Location: cookit!index.php?message=account_deleted");
        exit();
    } else {
        echo "<p class='error'>Error: Could not delete the user. Please try again later.</p>";
    }
} catch (PDOException $e) {
    echo "<p class='error'>Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>