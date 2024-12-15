<?php
$servername = "localhost";  // The MySQL server IP
$database = "mathewsteven_wdv341";  // The database name
$username = "mathewsteven_wdv341";  // The MySQL username (ensure there are no extra spaces)
$password = "12345678";  // The MySQL password

try {

  $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  echo "Connected succesfully";
  
  }
  catch(PDOException $e){
            echo "Connection Failed: " . $e->getMessage();  //this will display if an error happens during connection
  }
  
  
  ?>
