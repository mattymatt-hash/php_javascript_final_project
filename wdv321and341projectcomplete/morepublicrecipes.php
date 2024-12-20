<?php
session_start();
try {
    require 'mattnewconnect.php'; // Access the database

    $sql = "SELECT * FROM recipes";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC); // Return values as an associative array
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}

echo "<h2>Recipes</h2>";

if ($stmt->rowCount() > 0) {
    // Fetch and display all the recipes
    while ($row = $stmt->fetch()) {
        echo "<div class='recipe'>";
        echo "<h3>" . htmlspecialchars($row['recipe_name']) . "</h3>";
        echo "<p><strong>Chef:</strong> " . htmlspecialchars($row['chef_name']) . "</p>";
        echo "<p><strong>Difficulty:</strong> " . htmlspecialchars($row['difficulty']) . "</p>";
        echo "<p><strong>Servings:</strong> " . htmlspecialchars($row['servings']) . "</p>";
        echo "<p><strong>Prep Time:</strong> " . htmlspecialchars($row['prep_time']) . " minutes</p>";
        echo "<p><strong>Spice Level:</strong> " . htmlspecialchars($row['spice_level']) . "</p>";
        echo "<p><strong>Ingredients:</strong><br>" . nl2br(htmlspecialchars($row['ingredients'])) . "</p>";
        echo "<p><strong>Instructions:</strong><br>" . nl2br(htmlspecialchars($row['instructions'])) . "</p>";

        // Display image if it exists
        if (!empty($row['image_path'])) {
            echo "<img src='" . htmlspecialchars($row['image_path']) . "' alt='" . htmlspecialchars($row['recipe_name']) . "' style='width: 300px;'><br>";
        }
        
        // Add the delete button
        echo "<td><button onclick='confirmDelete(" . $row['id'] . ")'>Delete</button></td>";

        echo "</div><hr>";
    }
} else {
    echo "No recipes found!";
}
?>
<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url('recipe project/images/background/background.png') center/cover no-repeat;
            color: #333;
        }
      
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
      
       .recipe {
            background: #f4f4f4;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .recipe img {
            max-width: 100%;
            height: auto;
        }
        .banner {
            background-image: url('recipe project/images/banner/my-recipe-card_66051949.png');
            background-size: cover;
            background-position: center;
            height: 300px;
            display: flex;
            align-items: end;
            justify-content: left;
            color: white;
            font-size: 26px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        }
    </style>
    </head>
<script>
function confirmDelete(Id) {
        var result = confirm("Are you sure you want to delete this recipe?");
        if (result) {
            // Redirect to a delete script (deleteRecipe.php) with the recipeId as a parameter
            window.location.href = 'deleteRecipe.php?recipe_id=' + Id;
        }
    }
</script>
<body>
   
<?php
// Show status message (if any)
if (isset($_GET['status'])) {
    if ($_GET['status'] == 'success') {
        echo "<p style='color:green;'>Recipe deleted successfully!</p>";
    } elseif ($_GET['status'] == 'error') {
        echo "<p style='color:red;'>There was an error deleting the recipe.</p>";
    }
}
?>

</body>
<footer class="footer custom-bg text-white">
        <div class="container d-flex align-items-center justify-content-center position-relative">
            <h5><p id="copyright" class="m-0">&copy; <?php echo date("Y"); ?> Cook-it</p></h5>
        </div>
    </footer>

</html>
