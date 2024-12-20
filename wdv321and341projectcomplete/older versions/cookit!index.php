<?php
session_start(); 
// Check if last activity was set
if (isset($_SESSION['last_activity']) && time() - $_SESSION['last_activity'] > 900) {
    // Last request was more than 15 minutes ago
    session_unset(); // Unset $_SESSION variable for the run-time
    session_destroy(); // Destroy session data in storage
    header("Location: login.php"); // Redirect to login page
}
$_SESSION['last_activity'] = time(); // Update last activity time stamp

// Check if the form has been submitted
if (isset($_POST['submit'])) {
    // The form was submitted, continue processing the form data 
   
   
   /* 

-get the data from the form 
-connect to the database 
See if you have a matching record in the user's table 
If (match =true){
Valid user
Display Admin Page 

}
Else{
Invalid username
Display error message
Display the form
}
*/
//Get the data from the form
   
   
    $inUsername = $_POST['inUsername'];   // Pull the username from the login form
    $inPassword = $_POST['inPassword'];   // Pull the password from the login form

    // Connect to the database 
    try {
        require 'dbConnect.php';             // Access to the database
       
       
       //Does the input username and password match the username and password in the database 
//SELECT for a specific set of data

//$sql = "SELECT user_username, user_password FROM wdv341_user WHERE user_username = :username AND user_userpassword = :password";

       
       
        $sql = "SELECT COUNT(*) FROM wdv341_user WHERE user_username = :username AND user_password = :password;";
        $stmt = $conn->prepare($sql);             // Prepared statement PDO - return statement object

        // Bind parameters 
        $stmt->bindParam(":username", $inUsername);
        $stmt->bindParam(":password", $inPassword);

        $stmt->execute();    // Execute the PDO prepared statement, save results in $stmt object

        // Fetch the count
        $rowCount = $stmt->fetchColumn();    // Get number of rows in result set/statement

       /*
echo "Username: $username <bra>";
echo "Password: $Password <bra>";
echo "Count: $rowCount <bra>";
*/
       
       
        if ($rowCount > 0) {
            // If login is successful
            echo "<h3> Login successful </h3>";
        } else {
            // If login fails
            echo "<h3> Invalid username or password </h3>";
        }

   //$stmt->setFetchMode(PDO::FETCH_ASSOC); // Return values as an associative array

//Prepare and execute the query
   
   
   
    } catch (PDOException $e) {
        echo "Database Failed: " . $e->getMessage();
    }
} else {
    // If the form wasn't submitted yet, show this message
    echo "<h3> Please fill in both fields. </h3>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>index Page</title>
    <style>
       
       body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #f0f0f0;
            background-image: url('recipe project/images/background/background2.png'); /* Background image */
            background-size: cover; /* Make sure the image covers the entire page */
            background-position: center; /* Center the image */
            color: black; /* Text color */
            overflow: hidden;
        }

        h1 {
            width: 100%;
            text-align: center;
            margin: 0;
            padding: 20px 0;
            
            border-radius: 5px;
            color: rgb(0, 0, 0);
            font-size: 8em;
        }

        .button-container {
            display: flex;
            gap: 60px;
            margin-top: 20px;
        }

        .button {
            padding: 15px 30px;
            font-size: 1.2em;
            color: rgb(0, 0, 0);
            background-color: #FFA500; /* Orange color */
            border: 2px solid black; /* Black border */
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
        }

        .button:hover {
            background-color: #ff8c00; /* Darker orange on hover */
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}
        
        audio {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0.5;
        }
   
     /* Cookie consent banner styling */
     #cookie-consent-banner {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #333;
            color: white;
            padding: 15px;
            text-align: center;
            display: none; /* Hidden by default */
            z-index: 1000;
        }

        #cookie-consent-banner a {
            color: #FFA500;
            text-decoration: none;
        }

        #cookie-consent-banner button {
            background-color: #FFA500;
            color: black;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            margin-left: 15px;
        }

        #cookie-consent-banner button:hover {
            background-color: #ff8c00;
        }
   
   </style>
<script>
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

 // Function to display the login form when the "Log In" button is clicked
 document.getElementById('login-btn').addEventListener('click', function() {
            document.getElementById('login-form-container').style.display = 'block';
        });

        // Function to close the login form
        function closeLoginForm() {
            document.getElementById('login-form-container').style.display = 'none';
        }

</script>
</head>
<body>
    <h1>Cook-it!</h1>
   <strong><h2>Simple Recipes When You Need Them Most</h2></strong>
  
   <?php if (isset($_POST['submit']) && $rowCount > 0): ?>
    <!-- Display this section if the login is successful -->
    <section class="adminPage">
            <h2>Admin Page</h2>
        </section>
    <?php else: ?>
     
        <div class="button-container">
        <a href="cookit!login.php" class="button">Log In</a>
        <a href="register.html" class="button">New User</a>
    </div>
 <!-- Login Form Container -->
 <div id="login-form-container" class="login-form-container">
    <h3>Login</h3>
    <form method="POST" action="index.php">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        <input type="submit" name="submit" value="Log In">
    </form>
    <button class="close-btn" onclick="closeLoginForm()">Close</button>
</div>
    <!-- Cookie Consent Banner -->
 <div id="cookie-consent-banner">
    <p>This website uses cookies to enhance your experience. <a href="cookit!privacypolicy.html" target="_blank">Learn more</a>.</p>
    <button onclick="acceptCookies()">I Accept</button>
</div>

 <!-- Background Audio -->
<audio autoplay loop>
    <source src="showreel-music-promo-advertising-opener-vlog-background-intro-theme-261601.mp3" type="audio/mpeg">
    Your browser does not support the audio element.
</audio>
</body>
</html>
