<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WDV101 Intro HTML and CSS</title>
    
<body>
<p>This page displays the confirmation of your submitted information.</p>
<?php

if (!empty($_POST['honeypot'])) {
    // If honeypot field has a value, it's likely a bot submission
    die("Bot detected! Form submission ignored.");
}




if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form values
    $chefName = htmlspecialchars($_POST['chef_name']);
    $recipeName = htmlspecialchars($_POST['recipe_name']);
    $difficulty = htmlspecialchars($_POST['difficulty']);
    $servings = htmlspecialchars($_POST['servings']);
    $prepTime = htmlspecialchars($_POST['prep_time']);
    $ingredients = htmlspecialchars($_POST['ingredients']);
    $instructions = htmlspecialchars($_POST['instructions']);
    $spiceLevel = htmlspecialchars($_POST['spice_level']); // Spice level
    $image = $_FILES['recipe-image']; // The uploaded file

   // Handle image upload
   if ($image['error'] == 0) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($image["name"]);
    if (move_uploaded_file($image["tmp_name"], $target_file)) {
        echo "The file ". htmlspecialchars(basename($image["name"])) . " has been uploaded.<br>";
    } else {
        echo "Sorry, there was an error uploading your file.<br>";
    }
}
  
    // Display confirmation message
    echo "Recipe has been submitted.<br />";
    echo "Chef Name: $chefName<br />";
    echo "Recipe Name: $recipeName<br />";
    echo "Difficulty: $difficulty<br />";
    echo "Servings: $servings<br />";
    echo "Prep Time: $prepTime<br />";
    echo "Ingredients: $ingredients<br />";
    echo "Instructions: $instructions<br />";
    echo "Spice Level: $spiceLevel<br />";
  } else 
    ?>
<p>&nbsp;</p>
</body>
</html>
