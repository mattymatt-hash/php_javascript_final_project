<?php
// Sample PHP to hash passwords and insert data into the table.
require 'dbConnect.php';

try {
    $sql = "INSERT INTO wdv341_user (user_username, user_password) VALUES (:username, :password)";
    $stmt = $conn->prepare($sql);

    // Example users
    $users = [
        ['username' => 'testuser1', 'password' => password_hash('mypassword123', PASSWORD_DEFAULT)],
        ['username' => 'testuser2', 'password' => password_hash('securepass456', PASSWORD_DEFAULT)],
    ];

    foreach ($users as $user) {
        $stmt->bindParam(':username', $user['username']);
        $stmt->bindParam(':password', $user['password']);
        $stmt->execute();
    }

    echo "Sample users added successfully!";
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
}
?>
