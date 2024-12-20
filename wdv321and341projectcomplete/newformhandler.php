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


if (!empty($_POST['honeypot'])) {
    // If honeypot field has a value, it's likely a bot submission
    die("Bot detected! Form submission ignored.");
}



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize user inputs
    $chefName = htmlspecialchars($_POST['chef_name']);
    $recipeName = htmlspecialchars($_POST['recipe_name']);
    $difficulty = htmlspecialchars($_POST['difficulty']);
    $servings = htmlspecialchars($_POST['servings']);
    $prepTime = htmlspecialchars($_POST['prep_time']);
    $ingredients = htmlspecialchars($_POST['ingredients']);
    $instructions = htmlspecialchars($_POST['instructions']);
    $spiceLevel = htmlspecialchars($_POST['spice_level']); // Spice level

    // Handle image upload
    $image = $_FILES['recipe-image']; // The uploaded file
    $uploadOk = 1;
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($image["name"]);
    $imageFileType = strtolower(pathinfo($image["name"], PATHINFO_EXTENSION));

    // Validate the uploaded image
    if (isset($image)) {
        // Check if the file is an image
        $check = getimagesize($image["tmp_name"]);
        if ($check === false) {
            echo "File is not an image.<br>";
            $uploadOk = 0;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.<br>";
            $uploadOk = 0;
        }
  
        // Check file size (limit to 5MB)
        if ($image["size"] > 5000000) {
            echo "Sorry, your file is too large. Maximum size is 5MB.<br>";
            $uploadOk = 0;
        }

        // Allow only certain file formats
        if (!in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.<br>";
            $uploadOk = 0;
        }
  
        // Move the uploaded file if it's valid
        if ($uploadOk == 1) {
            if (move_uploaded_file($image["tmp_name"], $target_file)) {
                echo "The file " . htmlspecialchars(basename($image["name"])) . " has been uploaded.<br>";
                // Set the image path for the database insertion
                $imagePath = $target_file;
            } else {
                echo "Sorry, there was an error uploading your file.<br>";
                $uploadOk = 0;
            }
        }
    }

    // Proceed if the file upload is successful
    if ($uploadOk == 1) {
        // Prepare the SQL statement to insert the recipe into the database
        $stmt = $conn->prepare("INSERT INTO recipes (chef_name, recipe_name, difficulty, servings, prep_time, ingredients, instructions, spice_level, image_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssiissss", $chefName, $recipeName, $difficulty, $servings, $prepTime, $ingredients, $instructions, $spiceLevel, $imagePath);

        // Execute the query
        if ($stmt->execute()) {
            echo "Recipe has been submitted.<br />";
            echo "Chef Name: $chefName<br />";
            echo "Recipe Name: $recipeName<br />";
            echo "Difficulty: $difficulty<br />";
            echo "Servings: $servings<br />";
            echo "Prep Time: $prepTime<br />";
            echo "Ingredients: $ingredients<br />";
            echo "Instructions: $instructions<br />";
            echo "Spice Level: $spiceLevel<br />";

            // Optional: Show image if uploaded successfully
            echo "<p><strong>Uploaded Image:</strong></p>";
            echo "<img src='$target_file' alt='Recipe Image' style='width: 300px;'><br>";

            // Display buttons
            echo '<form action="cookit!publicfoodform.php" method="get">';
            echo '<button type="submit" name="action" value="addAnother">Add Another Recipe</button>';
            echo '</form>';

            echo '<form action="cookit!profile.html" method="get">';
            echo '<button type="submit" name="action" value="continue">Continue</button>';
            echo '</form>';
        } else {
            echo "Error: Could not submit recipe. Please try again later.";
        }

        // Close statement
        $stmt->close();
    } else {
        // Image upload failed, show error message
        echo "Recipe submission failed due to image upload errors.<br>";
    }
} else {
    // If the form hasn't been submitted yet, display it
    echo "<p>Please submit a recipe!</p>";
}
?>
