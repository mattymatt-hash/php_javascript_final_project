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
    <title>Login Page</title>
</head>
<body> 
    <h1>WDV341</h1>
    <h2>Login Example Page</h2>

    <?php if (isset($_POST['submit']) && $rowCount > 0): ?>
        <!-- Display this section if the login is successful -->
        <section class="adminPage">
            <h2>Admin Page</h2>
        </section>
    <?php else: ?>
        <!-- Display the form if the user hasn't logged in yet -->
        <section class="loginForm">
            <h2>Login Form</h2>
        </section>

        <form method="post" action="login.php">
            <p>
                <label for="inUsername">Username: </label>
                <input type="text" name="inUsername" id="inUsername">       
            </p>
            <p>
                <label for="inPassword">Password: </label>
                <input type="password" name="inPassword" id="inPassword">
            </p>
            <p>
                <input type="submit" name="submit" value="Submit">
                <input type="reset">
                
                <a href="logout.php">Logout</a>
            </p>
        
        </form>
    <?php endif; ?>
    
</body>
</html>