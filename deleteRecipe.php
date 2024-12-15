<?php
session_start();
require 'mattnewconnect.php'; // Access the database

// Check if recipe_id is passed in the URL
if (isset($_GET['recipe_id']) && is_numeric($_GET['recipe_id'])) {
    $recipeId = intval($_GET['recipe_id']); // Make sure the ID is an integer

    try {
        // Prepare SQL to delete the recipe from the database
        $sql = "DELETE FROM recipes WHERE id = :recipe_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':recipe_id', $recipeId, PDO::PARAM_INT);

        // Execute the deletion
        if ($stmt->execute()) {
            // Redirect back with a success message
            header("Location: morepublicrecipes.php?status=success");
            exit();
        } else {
            // If deletion fails, redirect with an error message
            header("Location: morepublicrecipes.php?status=error");
            exit();
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }
} else {
    // If no recipe ID is set or is invalid, redirect back to the recipes page
    header("Location: morepublicrecipes.php?status=error");
    exit();
}
?>
