<?php
session_start();

// Check if the session timed out
if (isset($_SESSION['last_activity']) && time() - $_SESSION['last_activity'] > 900) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}
$_SESSION['last_activity'] = time();

// Database connection
require 'mattnewconnect.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['form_type']) && $_POST['form_type'] === 'login') {
        // Login logic
        $inUsername = htmlspecialchars(trim($_POST['inusername'] ?? ''));
        $inPassword = htmlspecialchars(trim($_POST['inpassword'] ?? ''));
        $inEmail = htmlspecialchars(trim($_POST['inemail'] ?? ''));

        try {
            $sql = "SELECT user_password FROM wdv341_user WHERE user_username = :username AND user_email = :email";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":username", $inUsername);
            $stmt->bindParam(":email", $inEmail);
            $stmt->execute();
            $storedPassword = $stmt->fetchColumn();

            if ($storedPassword && password_verify($inPassword, $storedPassword)) {
                session_regenerate_id(true);
                $_SESSION['user'] = $inUsername;
                header("Location: cookit!profile.html");
                exit();
            } else {
                echo "<h3>Invalid username, email, or password.</h3>";
            }
        } catch (PDOException $e) {
            echo "Database Error: " . htmlspecialchars($e->getMessage());
        }
    } elseif (isset($_POST['form_type']) && $_POST['form_type'] === 'register') {
        // Registration logic
        $inUsername = htmlspecialchars(trim($_POST['inusername'] ?? ''));
        $inEmail = filter_var(trim($_POST['inemail'] ?? ''), FILTER_VALIDATE_EMAIL);
        $inPassword = htmlspecialchars(trim($_POST['inpassword'] ?? ''));
        $confirmPassword = htmlspecialchars(trim($_POST['confirm_password'] ?? ''));

        if (!$inEmail) {
            echo "<p class='error'>Please enter a valid email address.</p>";
            exit();
        }

        if ($inPassword !== $confirmPassword) {
            echo "<p class='error'>Passwords do not match!</p>";
            exit();
        }

        $hashedPassword = password_hash($inPassword, PASSWORD_BCRYPT);

        try {
            $checkSql = "SELECT COUNT(*) FROM wdv341_user WHERE user_username = :username OR user_email = :email";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bindParam(':username', $inUsername);
            $checkStmt->bindParam(':email', $inEmail);
            $checkStmt->execute();

            if ($checkStmt->fetchColumn() > 0) {
                echo "<p class='error'>Username or email already exists. Please choose a different one.</p>";
                exit();
            }

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
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cook-it!</title>
    <style>
      body {
            font-family: Arial, sans-serif;
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background: url('recipe project/images/background/background2.png') center/cover no-repeat;
            color: #333;
        }

        h1 {
            width: 100%;
            text-align: center;
            margin: 0;
            padding: 20px 0;
            border-radius: 5px;
            color: rgb(0, 0, 0);
            font-size: 5em;
        }

       h2{
        text-align: center;
            margin: 0;
            padding: 20px 0;
            border-radius: 5px;
            color: rgb(0, 0, 0);
            font-size: 2em;
       }
       
        .button-container {
            display: flex;
            gap: 20px;
        }

        .button {
            padding: 10px 20px;
            background-color: #FFA500;
            color: #000;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
        }

        .button:hover {
            background-color: #ff8c00;
            transform: scale(1.05);
        }
       
        #newuser-form-container {
            display: none; /* Hidden by default */
            width: 300px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        form {
            display: flex;
            flex-direction: column;
        }
        form label {
            display: block;
             margin-top: 10px;
    }
    form input {
            margin-top: 5px;
            padding: 8px;
            width: 100%;
            border-radius: 3px;
            border: 1px solid #ccc;
        }
        #login-form-container {
            display: none;
            text-align: center;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
            form label {
            display: block;
             margin-top: 10px;
    }

        form input {
        margin-top: 5px;
        width: 100%;
        padding: 8px;
        border-radius: 3px;
        border: 1px solid #ccc;
   }
 .cookie-banner footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px;
        }
  .cookie-banner button {
            background-color: #FFA500;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }
    </style>
    <script>
       
       document.addEventListener("DOMContentLoaded", function() {
            const currentYear = new Date().getFullYear();
            document.getElementById('copyright').innerHTML = `&copy; ${currentYear} Cook-it`;
        });
       
       
       
       document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('login-btn').addEventListener('click', () => {
                document.getElementById('login-form-container').style.display = 'block';
            });
            document.getElementById('newuser-btn').addEventListener('click', () => {
                document.getElementById('newuser-form-container').style.display = 'block';
            });
        });

        function closeForm(id) {
            document.getElementById(id).style.display = 'none';
        }
   
    // Function to get a cookie value by name
    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
    }
 // Function to set a cookie
    function setCookie(name, value, days) {
        const date = new Date();
        date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000); // Convert days to milliseconds
        document.cookie = `${name}=${value}; expires=${date.toUTCString()}; path=/`;
    }
     // Function to handle cookie consent
   function handleCookieConsent() {
            const cookieConsent = getCookie("cookieConsent");
  if (!cookieConsent) {
                // Show the cookie consent banner if the user has not accepted cookies
                document.getElementById("cookie-consent-banner").style.display = "block";
            }
        }
  // Function to accept cookies
        function acceptCookies() {
            setCookie("cookieConsent", "accepted", 30); // Store consent for 30 days
            document.getElementById("cookie-consent-banner").style.display = "none"; // Hide the banner
        }
  // Call the function to handle cookie consent
        window.onload = function() {
            handleCookieConsent();
        };
   
     </script>
</head>
<body>
    <h1>Cook-it!</h1>
    <h2>Simple Recipes When You Need Them Most</h2>
    
    <div class="button-container">
        <button id="login-btn" class="button">Log In</button>
        <button id="newuser-btn" class="button">New User</button>
    </div>

    <!-- Login Modal -->
    <div id="login-form-container" style="display:none;">
        <h3>Login</h3>
        <form method="POST" action="">
            <input type="hidden" name="form_type" value="login">
            <label>Username:</label><input type="text" name="inusername" required>
            <label>Email:</label><input type="email" name="inemail" required>
            <label>Password:</label><input type="password" name="inpassword" required>
            <input type="submit" value="Log In">
        </form>
        <button onclick="closeForm('login-form-container')">Close</button>
    </div>
    <!-- Honeypot field (hidden from users) -->
   <input type="text" name="honeypot" style="display:none" value="">
    <!-- Registration Modal -->
    <div id="newuser-form-container" style="display:none;">
        <h3>Register</h3>
        <form method="POST" action="">
            <input type="hidden" name="form_type" value="register">
            <label>Username:</label><input type="text" name="inusername" required>
            <label>Email:</label><input type="email" name="inemail" required>
            <label>Password:</label><input type="password" name="inpassword" required>
            <label>Confirm Password:</label><input type="password" name="confirm_password" required>
            <input type="submit" value="Register">
        </form>
        <button onclick="closeForm('newuser-form-container')">Close</button>
    </div>

   <!-- <div class="cookie-banner" id="cookie-consent-banner">
        <p>This website uses cookies to enhance your experience. <a href="cookit!privacypolicy.html">Learn more</a>.</p>
        <button onclick="acceptCookies()">I Accept</button>
    </div>
 <audio autoplay loop>
      <source src="showreel-music-promo-advertising-opener-vlog-background-intro-theme-261601.mp3" type="audio/mpeg">
        Your browser does not support the audio element.
    </audio>-->
</body>

<footer class="footer custom-bg text-white">
    <div class="container d-flex align-items-center justify-content-center position-relative">
        <!-- Copyright -->
        <p id="copyright" class="m-0"></p>
    </div>
</footer>


</html>
