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



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Form</title>
    <style>
        body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background: url('recipe project/images/background/background.png') center/cover no-repeat;
        color: #333;
        }
        /* Basic Styles */
       .banner {                       
            background-image: url('recipe project/images/banner/my-recipe-card_66051949.png'); /* banner image file */
            background-size: cover; /* Makes the image cover the entire banner */
            background-position: center; /* Centers the image */
            height: 300px; /* Adjust the height of the banner */
            position: relative; /* Important for positioning child elements */
           align-items: end; /* Align content in the center vertically */
            display: flex;
            justify-content: left; /* Align content to the left horizontally */
            color: white; /* White text for contrast */
            font-size: 26px; /* Text size */
            font-family: Arial, sans-serif; /* Font style */
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7); /* Add a shadow for better readability */
            
        }
        h1, h2 {
            text-align: center;
        }

        form {
            width: 600px;
            margin: auto;
            background-color: #c9c7c7;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .spice-button {
            background-color: #ddd;
        }
        .spice-button:hover {
            background-color: #bbb;
        }
        .spice-button.active {
            background-color: #4CAF50;
            color: white;
        }
        .spice-level-display {
            font-weight: bold;
            margin-top: 10px;
        }
        .button-container {
            display: flex;
            justify-content: space-between;
          
        }
        button, #view-recipe-button footer {
            background-color: #4CAF50;
            color: white;
            border: none;
            width: 32%;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
        }
      button:hover, #view-recipe-button:hover {
            background-color: #45a049;
        }
        #log-out-button {
            background-color: #f44336;
        }
        #log-out-button:hover {
            background-color: #e53935;
        }
        #view-recipe-button {
            background-color: #2196F3;
        }
        #view-recipe-button:hover {
            background-color: #1976D2;
        }
        form legend {
            font-size: larger;
            font-weight: bold;
            margin-bottom: 10px;
        }
 /* Spice Button Styles */
 .spice-button[data-level="No Heat"] {
            background-color: #2196F3; /* Blue */
        }
        .spice-button[data-level="Mild"] {
            background-color: #4CAF50; /* Green */
        }
        .spice-button[data-level="Medium"] {
            background-color: #FF9800; /* Orange */
        }
        .spice-button[data-level="Hot"] {
            background-color: #FF5722; /* Dark Orange */
        }
        .spice-button[data-level="Hell"] {
            background-color: #F44336; /* Red */
        }

        /* Hover Effects */
        .spice-button:hover {
            filter: brightness(1.2); /* Brightens the color slightly on hover */
        }

        /* Active (Selected) Button Style */
        .spice-button.active {
            border: 2px solid #000; /* Add a border to emphasize selection */
            transform: scale(1.05); /* Slightly increase size */
        }
        p {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"], input[type="number"], select, textarea {
            width: calc(100% - 20px);
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"], input[type="reset"] {
            background-color: #001479;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }

        input[type="submit"]:hover, input[type="reset"]:hover {
            background-color: #004d40;
        }

        .radio-group, .checkbox-group {
            margin-top: 10px;
        }

        .checkbox-group label {
            display: inline-block;
            margin-right: 20px;
        }

        textarea {
            height: 100px;
            resize: vertical;
        }
        @media (max-width: 768px) {
            form {
                width: 100%;
                padding: 15px;
            }
            .button-container {
                flex-direction: column;
                gap: 15px;
            }
            button {
                width: 100%;
            }
        }
        h4 {
            color: red;
        }
    </style>

<script> 
document.addEventListener('DOMContentLoaded', function () {
    // Spice Level button selection
    document.querySelectorAll('.spice-button').forEach(function (button) {
        button.addEventListener('click', function () {
            document.getElementById('spice-level-display').textContent = this.dataset.level;
            document.getElementById('spice-level-input').value = this.dataset.level; // Set hidden input
        });
    });

     // Validate spice level selection
     if (!selectedSpiceLevel) {
            alert('Please select a spice level!');
            return;
       
        }
         // Validate required fields
         const requiredFields = document.querySelectorAll('#recipe-form input[required]');
        let valid = true;
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                valid = false;
                field.style.borderColor = 'red';
            } else {
                field.style.borderColor = '';
            }
        });

        if (!valid) {
            alert('Please fill in all required fields.');
            return;
        }

// Spice level selection
spiceButtons.forEach(button => {
        button.addEventListener('click', () => {
            spiceButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            selectedSpiceLevel = button.getAttribute('data-level');
            spiceDisplay.textContent = `Selected: ${selectedSpiceLevel}`;
            spiceDisplay.style.color = window.getComputedStyle(button).backgroundColor;
        });
    });
    // Image upload preview
    document.getElementById('recipe-image').addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function () {
                const base64Image = reader.result;
                document.getElementById('image-preview').src = base64Image;
                document.getElementById('image-preview').style.display = 'block'; // Show preview
            };
            reader.readAsDataURL(file);
        }
    });
});
</script>
</head>
<body>
   

<h1>Recipe Form</h1>
    <h2>Submit Your Recipe</h2>
    <p>This form allows chefs to submit their recipes so they can be viewed by everyone, to keep your recipes private <a href="cookit!foodform.html" class="button">click here.</a>.</p>
   <form id="recipeForm" name="recipeForm" method="POST" action="newformhandler.php" enctype="multipart/form-data">
        <legend>Recipe Submission Form</legend>
        <h4>Fields With (*) Are Required.</h4>

        <!-- Chef's Name -->
        <p>
            <label for="chef_name">Chef's Name:*</label>
            <input type="text" name="chef_name" id="chef_name" required />
        </p>

        <!-- Recipe Name -->
        <p>
            <label for="recipe_name">Recipe Name:*</label>
            <input type="text" name="recipe_name" id="recipe_name" required />
        </p>

        <!-- Difficulty -->
        <p>
            <label for="difficulty">Difficulty:*</label>
            <select name="difficulty" id="difficulty" required>
                <option value="">Select Difficulty</option>
                <option value="easy">Easy</option>
                <option value="medium">Medium</option>
                <option value="hard">Hard</option>
            </select>
        </p>

        <!-- Servings -->
        <p>
            <label for="servings">Number of Servings:*</label>
            <input type="number" name="servings" id="servings" required />
        </p>

        <!-- Preparation Time -->
        <p>
            <label for="prep_time">Preparation Time (in minutes):*</label>
            <input type="text" name="prep_time" id="prep_time" required />
        </p>

        <!-- Spice Level -->
        <p>
            <label>Spice Level:*</label>
            <div id="spice-buttons">
                <button type="button" class="spice-button" data-level="No Heat">No Heat</button>
                <button type="button" class="spice-button" data-level="Mild">Mild</button>
                <button type="button" class="spice-button" data-level="Medium">Medium</button>
                <button type="button" class="spice-button" data-level="Hot">Hot</button>
                <button type="button" class="spice-button" data-level="Hell">Hell</button>
            </div>
            <div class="spice-level-display" id="spice-level-display">No selection yet</div>
            <!-- Hidden field to store spice level -->
            <input type="hidden" name="spice_level" id="spice-level-input" />
        </p>

        <!-- Image Upload -->
        <p>
            <label for="recipe-image">Recipe Image (Upload):*</label>
            <input type="file" name="recipe-image" id="recipe-image" accept="image/*" required />
            <img id="image-preview" src="" alt="Image Preview" style="display:none; max-width: 100%; height: auto; margin-top: 10px;">
        </p>
    <!-- Honeypot field (hidden from users) -->
    <input type="text" name="honeypot" style="display:none" value="">
        <!-- Ingredients and Instructions (You can dynamically add these if needed) -->
        <p>
            <label for="ingredients">Ingredients:*</label>
            <textarea name="ingredients" id="ingredients" required></textarea>
        </p>

        <p>
            <label for="instructions">Instructions:*</label>
            <textarea name="instructions" id="instructions" required></textarea>
        </p>

        <button type="submit">Submit Recipe</button>
   
        <!-- Navigation Links -->
        <a href="cookit!index.php" class="button">Log Out</a>
        <a href="cookit!profile.html" class="button">View Recipes</a>
   
   
   
    </form>

    <footer class="footer">
        <p>&copy; <?php echo date("Y"); ?> Cook-it</p>
        <p>Questions, problems, or concerns? Please contact us <a href="contactus.html">here.</a></p>
    </footer>
</body>
</html>
