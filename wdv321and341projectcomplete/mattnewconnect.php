<?php /* PHP connect file

This file will allow your php application/page to the mysql database of your choice. we can use this on multiple pages. 
Use the require command to include on your page.

*/

$servername= "localhost";
$database = "mathewsteven_cookit";          //name of the database you wish to access
$username= "mathewsteven_cookit";           //username to sign in to ur mysql database on your localhost
$password = "12345678";            //default password to sign on to ur mysql database on your localhost

try {

$conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
echo "Connected succesfully";

}
catch(PDOException $e){
          echo "Connection Failed: " . $e->getMessage();  //this will display if an error happens during connection
}


?>