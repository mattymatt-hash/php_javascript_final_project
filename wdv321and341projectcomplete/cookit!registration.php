<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Sanitize input data
    $inUsername = htmlspecialchars(trim($_POST['inusername'] ?? ''));
    $inEmail = filter_var(trim($_POST['inemail'] ?? ''), FILTER_VALIDATE_EMAIL);
    $inPassword = htmlspecialchars(trim($_POST['inpassword'] ?? ''));
    $confirmPassword = htmlspecialchars(trim($_POST['confirm_password'] ?? ''));

    // Check if email is valid
    if (!$inEmail) {
        echo "<p class='error'>Please enter a valid email address.</p>";
        exit();
    }

    // Check if passwords match
    if ($inPassword !== $confirmPassword) {
        echo "<p class='error'>Passwords do not match!</p>";
        exit();
    }

    // Hash the password
    $hashedPassword = password_hash($inPassword, PASSWORD_BCRYPT);

    // Database connection
    require 'mattconnect.php';

    try {
        // Check if username or email already exists
        $checkSql = "SELECT COUNT(*) FROM wdv341_user WHERE user_username = :username OR user_email = :email";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bindParam(':username', $inUsername);
        $checkStmt->bindParam(':email', $inEmail);
        $checkStmt->execute();

        if ($checkStmt->fetchColumn() > 0) {
            echo "<p class='error'>Username or email already exists. Please choose a different one.</p>";
            exit();
        }

        // Insert new user into the database
        $sql = "INSERT INTO wdv341_user (user_username, user_email, user_password) VALUES (:username, :email, :password)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $inUsername);
        $stmt->bindParam(':email', $inEmail);
        $stmt->bindParam(':password', $hashedPassword);

        if ($stmt->execute()) {
            echo "<p>Registration successful! <a href='cookit!index.php'>Click here to log in.</a></p>";
            exit();
        } else {
            echo "<p class='error'>Error: Could not register user.</p>";
        }
    } catch (PDOException $e) {
        echo "<p class='error'>Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}





?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cook-it! Registration Page</title>
    <style>
        
        body {
           
            font-family: Arial, sans-serif;
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background: url('recipe project/images/background/background.png') center/cover no-repeat;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }
        input[type="text"], input[type="email"], input[type="password"], input[type="file"], select, textarea {
            width: 100%;
            padding: 8px;
            margin: 5px 0 15px 0;
            border-radius: 4px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
    
    </style>
</head>
<body>

<div class="container">
    <h1>Registration</h1>

    <form method="POST" action="">
        <label for="inusername">Username:</label>
        <input type="text" name="inusername" id="inusername" required>

       <label for="inemail">Email:</label>
        <input type="email" name="inemail" id="inemail" required>
       
        <label for="inpassword">Password:</label>
        <input type="password" name="inpassword" id="inpassword" required>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" name="confirm_password" id="confirm_password" required>

        <input type="submit" name="submit" value="Register">
    </form>
</div>

</body>
</html>
